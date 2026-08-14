<?php namespace Seiger\sMailer\Services;

/** Resolve the current site without requiring the optional sMultisite package. */
class DomainContext
{
    public function current(): string
    {
        return trim((string) evo()->getConfig('site_key', 'default')) ?: 'default';
    }

    public function hasMultisite(): bool
    {
        return class_exists('Seiger\\sMultisite\\Facades\\sMultisite') && app()->bound('sMultisite');
    }

    /** @return list<array{value: string, label: string}> */
    public function options(): array
    {
        if (!$this->hasMultisite()) {
            return [];
        }

        try {
            return array_values(array_map(
                fn (array $domain): array => [
                    'value' => (string) ($domain['key'] ?? 'default'),
                    'label' => (string) ($domain['site_name'] ?? $domain['key'] ?? 'default'),
                ],
                \Seiger\sMultisite\Facades\sMultisite::domains(),
            ));
        } catch (\Throwable) {
            return [];
        }
    }

    public function label(string $key): string
    {
        foreach ($this->options() as $option) {
            if ($option['value'] === $key) {
                return $option['label'];
            }
        }

        return $key;
    }
}
