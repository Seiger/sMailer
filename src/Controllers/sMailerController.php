<?php namespace Seiger\sMailer\Controllers;

use EvolutionCMS\Facades\UrlProcessor;
use EvolutionCMS\Models\SiteContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class sMailerController
{
    public $url;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->url = $this->moduleUrl();
    }

    /**
     * Update settings configuration
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function updateConfigure()
    {
        $configs = config('seiger.settings.sMailer', []);
        $updateRequest = request()->only(['config', 'periodic']);

        // Merge config
        if (is_array($updateRequest) && count($updateRequest)) {
            foreach ($updateRequest as $tab => $values) {
                foreach ($values as $key => $item) {
                    if (is_scalar($item)) {
                        $configs[$tab][$key] = $item;
                    } elseif ($key == 'icons' && is_array($item) && isset($item['file']) && isset($item['link'])) {
                        $i = 0;
                        $configs[$tab][$key] = [];
                        foreach ($item as $v) {
                            $configs[$tab][$key][] = ['file' => $item['file'][$i], 'link' => $item['link'][$i]];
                            $i++;
                        }
                    }
                }
            }
        }

        // Formatted config
        $string = '<?php return [' . "\n";
        foreach ($configs as $tab => $config) {
            $string .= "\t" . '"'.$tab.'" => [' . "\n";
            foreach ($config as $key => $value) {
                $string .= $this->configRecursive($key, $value);
            }
            $string .= "\t" . '],' . "\n";
        }
        $string .= '];';

        // Save config
        $handle = fopen(EVO_CORE_PATH . 'custom/config/seiger/settings/sMailer.php', "w");
        fwrite($handle, $string);
        fclose($handle);

        return evo()->clearCache('full');
    }

    /**
     * Default language
     *
     * @return string
     */
    public function langDefault(): string
    {
        return evo()->getConfig('s_lang_default', 'base');
    }

    /**
     * Languages list
     *
     * @return array
     */
    public function langList(): array
    {
        $lang = evo()->getConfig('s_lang_config', '');
        if (trim($lang)) {
            $lang = explode(',', $lang);
        } else {
            $lang = ['base'];
        }
        return $lang;
    }

    /**
     * Connecting the visual editor to the required fields
     *
     * @param string $ids List of id fields separated by commas
     * @param string $height Window height
     * @param string $editor Which editor to use TinyMCE5, Codemirror
     * @return string
     */
    public function textEditor(string $ids, string $height = '500px', string $editor = ''): string
    {
        $theme = null;
        $elements = [];
        $options = [];
        $ids = explode(",", $ids);

        if (!trim($editor)) {
            $editor = evo()->getConfig('which_editor', 'TinyMCE5');
        }
        if ($editor == 'TinyMCE5') {
            $theme = evo()->getConfig('sart_tinymce5_theme', 'custom');
        }

        foreach ($ids as $id) {
            $elements[] = trim($id);
            if ($theme) {
                $options[trim($id)]['theme'] = $theme;
            }
        }

        return implode("", evo()->invokeEvent('OnRichTextEditorInit', [
            'editor' => $editor,
            'elements' => $elements,
            'height' => $height,
            'contentType' => 'htmlmixed',
            'options' => $options
        ]));
    }

    /**
     * Module url
     *
     * @return string
     */
    protected function moduleUrl(): string
    {
        return 'index.php?a=112&id=' . md5(__('sMailer::global.mailer'));
    }

    /**
     * Display render
     *
     * @param string $tpl
     * @param array $data
     * @return bool
     */
    public function view(string $tpl, array $data = [])
    {
        return \View::make('sMailer::'.$tpl, $data);
    }

    protected function configRecursive($key, $value, $dept = 2)
    {
        $string = '';
        $depts = ["", "\t", "\t\t", "\t\t\t", "\t\t\t\t", "\t\t\t\t\t", "\t\t\t\t\t\t"];
        $key = is_string($key) ? '"' . addslashes($key) . '"' : $key;

        if (is_scalar($value)) {
            $value = is_string($value) ? '"' . addslashes($value) . '"' : $value;
            $string .= $depts[$dept] . $key . ' => ' . $value . ',' . "\n";
        } else {
            $string .= $depts[$dept] . $key.' => [' . "\n";
            foreach ($value as $k => $v) {
                $string .= $this->configRecursive($k, $v, $dept + 1);
            }
            $string .= $depts[$dept] . '],' . "\n";
        }

        return $string;
    }
}
