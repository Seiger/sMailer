<x-evo::module-tab-shell :tabs="$tabs" model="activeTab">
    <div x-show="activeTab === 'overview'" x-cloak>
        <section class="evo-ui-dashboard-grid">
            <x-evo::card :label="__('sMailer::global.foundation_title')" icon="mail-fast">
                <p>{{ __('sMailer::global.foundation_description') }}</p>
            </x-evo::card>
        </section>
    </div>
</x-evo::module-tab-shell>
