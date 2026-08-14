<?php namespace Seiger\sMailer\Services;

use Illuminate\Support\Facades\File;

/**
 * Convert a Builder document into portable email markup.
 *
 * The renderer deliberately consumes the persisted JSON contract rather than
 * manager DOM. This keeps previews, test messages and future deliveries on
 * the same output path.
 */
class MailingDocumentRenderer
{
    /**
     * @param array<string, mixed> $document
     * @param array{products?: array<string, list<array<string, mixed>>>, unsubscribe_url?: string} $context
     */
    public function render(array $document, array $context = []): string
    {
        $rows = '';

        foreach ((array) ($document['blocks'] ?? []) as $block) {
            if (is_array($block)) {
                $rows .= $this->renderBlock($block, $context);
            }
        }

        return '<!doctype html>'
            . '<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">'
            . '<style>@media only screen and (max-width:620px){.smailer-email{width:100%!important}.smailer-column{display:block!important;width:100%!important}}</style>'
            . '</head><body style="margin:0;padding:0;background:#f3f4f6;">'
            . '<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#f3f4f6;border-collapse:collapse;"><tr><td align="center">'
            . '<table class="smailer-email" role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="width:600px;max-width:600px;background:#ffffff;border-collapse:collapse;">'
            . $rows
            . '</table></td></tr></table></body></html>';
    }

    /** @param array<string, mixed> $block @param array<string, mixed> $context */
    protected function renderBlock(array $block, array $context, int $width = 600): string
    {
        $type = (string) ($block['type'] ?? '');

        if ($type === 'layout') {
            return $this->section($this->renderLayout($block, $context, $width), $block);
        }

        return $this->section(match ($type) {
            'title' => $this->renderTitle($block),
            'text', 'html' => (string) ($block['content'] ?? ''),
            'image', 'logo' => $this->renderImage($block),
            'divider' => $this->renderDivider($block),
            'button' => $this->renderButton($block),
            'video' => $this->renderVideo($block, $width),
            'navigation' => $this->renderNavigation($block),
            'social' => $this->renderSocial($block),
            'unsubscribe' => $this->renderUnsubscribe($block, $context),
            'product' => $this->renderProducts($block, $context),
            'spacer' => '&nbsp;',
            default => '',
        }, $block);
    }

    /** @param array<string, mixed> $block */
    protected function section(string $content, array $block): string
    {
        if ($content === '') {
            return '';
        }

        $padding = in_array((string) ($block['type'] ?? ''), ['image', 'logo'], true)
            ? implode(' ', array_map(fn (string $side): string => $this->number($block['padding' . $side] ?? 0) . 'px', ['Top', 'Right', 'Bottom', 'Left']))
            : $this->number($block['padding'] ?? 16) . 'px';
        $align = $this->alignment($block['align'] ?? 'left');
        $background = (string) (($block['type'] ?? '') === 'title' ? ($block['backgroundColor'] ?? '#ffffff') : '#ffffff');

        return '<tr><td align="' . $align . '" style="padding:' . $padding . ';background:' . $this->color($background, '#ffffff') . ';">' . $content . '</td></tr>';
    }

    /** @param array<string, mixed> $block */
    protected function renderTitle(array $block): string
    {
        $level = (string) ($block['titleLevel'] ?? 'h2');
        $level = in_array($level, ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'], true) ? $level : 'h2';
        $content = $this->escape((string) ($block['content'] ?? ''));
        $style = 'margin:0;font-family:' . $this->escapeAttribute((string) ($block['fontFamily'] ?? 'Arial, sans-serif'))
            . ';font-size:' . $this->number($block['fontSize'] ?? 32) . 'px;font-weight:' . $this->escapeAttribute((string) ($block['fontWeight'] ?? '700'))
            . ';font-style:' . $this->escapeAttribute((string) ($block['fontStyle'] ?? 'normal'))
            . ';text-decoration:' . $this->escapeAttribute((string) ($block['textDecoration'] ?? 'none'))
            . ';color:' . $this->color((string) ($block['textColor'] ?? ''), '#1f2937')
            . ';line-height:' . $this->number($block['lineHeight'] ?? 1.2, 1.2) . ';';

        return '<' . $level . ' style="' . $style . '">' . nl2br($content) . '</' . $level . '>';
    }

    /** @param array<string, mixed> $block */
    protected function renderImage(array $block): string
    {
        $source = $this->url((string) ($block['imageSrc'] ?? ''));
        if ($source === '') {
            return '';
        }

        $width = min(max($this->number($block['imageWidth'] ?? 100), 1), 600);
        $unit = ($block['imageWidthUnit'] ?? '%') === 'px' ? 'px' : '%';
        $image = '<img src="' . $this->escapeAttribute($source) . '" alt="' . $this->escapeAttribute((string) ($block['imageAlt'] ?? ''))
            . '" width="' . ($unit === 'px' ? (int) $width : '100%') . '" style="display:block;width:' . $width . $unit . ';max-width:100%;height:auto;border:0;">';
        $link = $this->url((string) ($block['imageLink'] ?? ''));

        return $link === '' ? $image : '<a href="' . $this->escapeAttribute($link) . '" target="_blank">' . $image . '</a>';
    }

    /** @param array<string, mixed> $block */
    protected function renderDivider(array $block): string
    {
        return '<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td style="border-top:'
            . min(max($this->number($block['dividerThickness'] ?? 1), 1), 12) . 'px solid '
            . $this->color((string) ($block['dividerColor'] ?? ''), '#e5e7eb') . ';font-size:0;line-height:0;">&nbsp;</td></tr></table>';
    }

    /** @param array<string, mixed> $block */
    protected function renderButton(array $block): string
    {
        $label = $this->escape((string) ($block['buttonText'] ?? ''));
        if ($label === '') {
            return '';
        }

        $href = $this->url((string) ($block['buttonLink'] ?? '')) ?: '#';
        $style = 'display:inline-block;padding:12px 20px;border-radius:' . min(max($this->number($block['buttonRadius'] ?? 6), 0), 40)
            . 'px;background:' . $this->color((string) ($block['buttonBackgroundColor'] ?? ''), '#2563eb')
            . ';color:' . $this->color((string) ($block['buttonTextColor'] ?? ''), '#ffffff')
            . ';font-family:Arial,sans-serif;font-size:16px;font-weight:700;line-height:1.2;text-decoration:none;';

        return '<a href="' . $this->escapeAttribute($href) . '" target="_blank" style="' . $style . '">' . $label . '</a>';
    }

    /** @param array<string, mixed> $block */
    protected function renderVideo(array $block, int $width): string
    {
        $url = $this->url((string) ($block['videoUrl'] ?? ''));
        $thumbnail = $this->url((string) ($block['videoThumbnailSrc'] ?? '')) ?: $this->youtubeThumbnail($url);
        $width = min(max($width, 160), 600);
        $height = (int) round($width * 9 / 16);
        $background = $thumbnail === ''
            ? '#111827'
            : '#111827 url(' . $this->escapeAttribute($thumbnail) . ') center center / cover no-repeat';
        $button = '<a href="' . $this->escapeAttribute($url ?: '#') . '" target="_blank" style="display:inline-block;width:56px;height:56px;border-radius:28px;background:rgba(255,255,255,.35);box-shadow:0 2px 12px rgba(0,0,0,.18);text-decoration:none;">'
            . '<span style="display:inline-block;margin:18px 0 0 5px;width:0;height:0;border-top:10px solid transparent;border-bottom:10px solid transparent;border-left:15px solid #1f2937;">&nbsp;</span></a>';

        return '<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" background="' . $this->escapeAttribute($thumbnail) . '" style="width:100%;border-collapse:collapse;background:' . $background . ';"><tr><td align="center" valign="middle" height="' . $height . '" style="height:' . $height . 'px;">' . $button . '</td></tr></table>';
    }

    /** @param array<string, mixed> $block */
    protected function renderNavigation(array $block): string
    {
        $links = '';
        foreach ((array) ($block['navigationLinks'] ?? []) as $item) {
            if (!is_array($item) || ($label = trim((string) ($item['label'] ?? ''))) === '') {
                continue;
            }
            $links .= '<a href="' . $this->escapeAttribute($this->url((string) ($item['url'] ?? '')) ?: '#') . '" style="margin:0 8px;color:#1f2937;font-family:Arial,sans-serif;font-size:14px;text-decoration:none;">' . $this->escape($label) . '</a>';
        }

        return $links;
    }

    /** @param array<string, mixed> $block */
    protected function renderSocial(array $block): string
    {
        $icons = '';
        foreach ((array) ($block['socialLinks'] ?? []) as $link) {
            if (!is_array($link) || ($platform = strtolower((string) ($link['platform'] ?? ''))) === '') {
                continue;
            }
            $url = $this->url((string) ($link['url'] ?? ''));
            $label = $platform === 'x' ? 'X' : ucfirst($platform);
            $size = min(max($this->number($block['socialSize'] ?? 28), 16), 56);
            $color = $this->color((string) ($link['color'] ?? ''), '#1f2937');
            $style = 'display:inline-block;margin:0 5px;width:' . $size . 'px;height:' . $size . 'px;vertical-align:middle;text-decoration:none;';
            $content = '<img src="' . $this->escapeAttribute($this->socialIconUrl($platform, $color)) . '" alt="' . $this->escapeAttribute($label)
                . '" width="' . (int) $size . '" height="' . (int) $size . '" style="display:block;width:' . (int) $size . 'px;height:' . (int) $size . 'px;border:0;color:' . $color . ';">';
            $icons .= $url === ''
                ? '<span style="' . $style . '">' . $content . '</span>'
                : '<a href="' . $this->escapeAttribute($url) . '" target="_blank" style="' . $style . '">' . $content . '</a>';
        }

        return $icons;
    }

    protected function socialIconUrl(string $platform, string $color): string
    {
        $icons = [
            'facebook' => 'brand-facebook',
            'instagram' => 'brand-instagram',
            'youtube' => 'brand-youtube',
            'linkedin' => 'brand-linkedin',
            'tiktok' => 'brand-tiktok',
            'telegram' => 'brand-telegram',
            'whatsapp' => 'brand-whatsapp',
            'x' => 'brand-x',
        ];
        $name = $icons[$platform] ?? 'brand-link';
        $baseUrl = rtrim((string) evo()->getConfig('site_url', '/'), '/');
        $source = dirname(__DIR__, 4) . '/secondnetwork/blade-tabler-icons/resources/svg/' . $name . '.svg';
        $directory = public_path('assets/cache/images/smailer');
        $filename = $name . '-' . strtolower(ltrim($color, '#')) . '.svg';
        $target = $directory . '/' . $filename;

        if (is_file($source) && !is_file($target)) {
            File::ensureDirectoryExists($directory);
            $svg = file_get_contents($source);
            if ($svg !== false) {
                File::put($target, str_replace('currentColor', $color, $svg));
            }
        }

        return $baseUrl . '/assets/cache/images/smailer/' . $filename;
    }

    /** @param array<string, mixed> $block @param array<string, mixed> $context */
    protected function renderUnsubscribe(array $block, array $context): string
    {
        $text = trim((string) ($block['unsubscribeText'] ?? ''));
        if ($text === '') {
            return '';
        }

        return '<a href="' . $this->escapeAttribute($this->url((string) ($context['unsubscribe_url'] ?? '')) ?: '#unsubscribe')
            . '" style="color:#1f2937;font-family:Arial,sans-serif;font-size:14px;text-decoration:underline;">' . $this->escape($text) . '</a>';
    }

    /** @param array<string, mixed> $block @param array<string, mixed> $context */
    protected function renderProducts(array $block, array $context): string
    {
        $products = (array) ($context['products'][$block['id'] ?? ''] ?? []);
        if ($products === []) {
            return '';
        }

        $columns = min(max($this->number($block['productColumns'] ?? 1), 1), 3);
        $width = (int) floor(100 / $columns);
        $cells = '';
        foreach (array_chunk($products, $columns) as $row) {
            $cells .= '<tr>';
            foreach ($row as $product) {
                $cells .= '<td width="' . $width . '%" valign="top" style="padding:8px;">' . $this->renderProduct($product) . '</td>';
            }
            $cells .= str_repeat('<td width="' . $width . '%">&nbsp;</td>', $columns - count($row)) . '</tr>';
        }

        return '<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">' . $cells . '</table>';
    }

    /** @param array<string, mixed> $product */
    protected function renderProduct(array $product): string
    {
        $url = $this->url((string) ($product['url'] ?? '')) ?: '#';
        $title = $this->escape((string) ($product['title'] ?? ''));
        $image = $this->url((string) ($product['image'] ?? ''));
        $output = $image === '' ? '' : '<img src="' . $this->escapeAttribute($image) . '" alt="' . $this->escapeAttribute($title) . '" width="100%" style="display:block;width:100%;height:auto;border:0;margin-bottom:8px;">';
        $output .= '<span style="display:block;color:#1f2937;font-family:Arial,sans-serif;font-size:14px;font-weight:700;line-height:1.35;">' . $title . '</span>';
        $output .= '<span style="display:block;margin-top:4px;color:#6b7280;font-family:Arial,sans-serif;font-size:14px;">' . $this->escape((string) ($product['price'] ?? '')) . '</span>';

        return '<a href="' . $this->escapeAttribute($url) . '" target="_blank" style="text-decoration:none;">' . $output . '</a>';
    }

    /** @param array<string, mixed> $block @param array<string, mixed> $context */
    protected function renderLayout(array $block, array $context, int $availableWidth): string
    {
        $columns = array_values((array) ($block['columns'] ?? []));
        if ($columns === []) {
            return '';
        }
        $columnPercent = (int) floor(100 / count($columns));
        $columnWidth = (int) floor($availableWidth / count($columns));
        $cells = '';
        foreach ($columns as $column) {
            $content = '';
            foreach ((array) ($column['blocks'] ?? []) as $child) {
                if (is_array($child)) {
                    $content .= $this->renderBlock($child, $context, $columnWidth);
                }
            }
            $cells .= '<td class="smailer-column" width="' . $columnPercent . '%" valign="top" style="width:' . $columnPercent . '%;">'
                . '<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">' . $content . '</table></td>';
        }

        return '<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr>' . $cells . '</tr></table>';
    }

    protected function youtubeThumbnail(string $url): string
    {
        if (!preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|shorts/))([A-Za-z0-9_-]{6,})~', $url, $matches)) {
            return '';
        }

        return 'https://i.ytimg.com/vi/' . $matches[1] . '/hqdefault.jpg';
    }

    protected function url(string $value): string
    {
        $value = trim($value);
        return preg_match('~^(?:https?:|mailto:|tel:|#)~i', $value) ? $value : '';
    }

    protected function color(string $value, string $fallback): string
    {
        return preg_match('/^#[0-9a-f]{3,8}$/i', trim($value)) ? trim($value) : $fallback;
    }

    protected function alignment(mixed $value): string
    {
        return in_array($value, ['left', 'center', 'right', 'justify'], true) ? (string) $value : 'left';
    }

    protected function number(mixed $value, float $fallback = 0): float
    {
        return is_numeric($value) ? (float) $value : $fallback;
    }

    protected function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    protected function escapeAttribute(string $value): string
    {
        return $this->escape($value);
    }
}
