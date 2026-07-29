<?php namespace Seiger\sMailer\Components;

use EvoUI\Components\Component;

/**
 * Own the interactive sMailer manager foundation.
 *
 * Business tabs will be added here only when their application services and
 * persistence contracts are ready, keeping the initial 2.x surface truthful.
 *
 * @since 2.0.0
 */
class ModulePanel extends Component
{
    /** @var list<array<string, mixed>> */
    public array $rawTabs = [];

    /** @var array<string, mixed> */
    public array $context = [];

    public string $activeTab = 'overview';

    /**
     * Initialize the manager panel with server-defined navigation.
     *
     * @param list<array<string, mixed>> $tabs
     * @param string $activeTab
     * @param array<string, mixed> $context
     * @return void
     * @since 2.0.0
     */
    public function mount(array $tabs = [], string $activeTab = 'overview', array $context = []): void
    {
        $this->rawTabs = $tabs;
        $this->context = $context;
        $this->activeTab = $this->normalizeTab($activeTab);
    }

    /**
     * Switch to an allowed manager tab.
     *
     * @param string $tab
     * @return void
     * @since 2.0.0
     */
    public function switchTab(string $tab): void
    {
        $this->activeTab = $this->normalizeTab($tab);
    }

    /**
     * Render the package-owned EvoUI panel.
     *
     * @return mixed
     * @since 2.0.0
     */
    public function render(): mixed
    {
        return view('sMailer::components.module-panel', [
            'tabs' => $this->navigationTabs(),
            'activeTab' => $this->activeTab,
        ]);
    }

    /**
     * Keep client-provided tab state inside the declared navigation contract.
     *
     * @param string $tab
     * @return string
     * @since 2.0.0
     */
    protected function normalizeTab(string $tab): string
    {
        $allowed = collect($this->rawTabs)
            ->pluck('key')
            ->filter()
            ->values()
            ->all();

        return in_array($tab, $allowed, true) ? $tab : ($allowed[0] ?? 'overview');
    }

    /**
     * Convert package tab metadata into EvoUI wire navigation actions.
     *
     * @return list<array<string, mixed>>
     * @since 2.0.0
     */
    protected function navigationTabs(): array
    {
        return collect($this->rawTabs)
            ->map(function (array $tab): array {
                $key = (string)($tab['key'] ?? '');
                $tab['active'] = $key === $this->activeTab;
                $tab['type'] = 'wire';
                $tab['method'] = 'switchTab';
                $tab['argument'] = $key;

                return $tab;
            })
            ->values()
            ->all();
    }
}
