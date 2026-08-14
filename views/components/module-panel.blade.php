<div
    data-smailer-module-panel
    x-data="{
        storedPreviewOpen: false,
        storedPreviewHtml: '',
        storedPreviewError: '',
        deliveryOpen: false,
        deliveryMailingId: null,
        deliveryMailingName: '',
        deliveryRecipients: 0,
        deliveryError: '',
        deliverySuccess: '',
        subscriberTransferMessage: '',
        init() {
            this.subscriberTransferMessage = '';
            window.addEventListener('pageshow', () => {
                this.subscriberTransferMessage = '';
            });
        },
        async openStoredPreview(mailingId) {
            this.storedPreviewError = '';

            try {
                const payload = await $wire.previewStoredDocument(Number(mailingId));
                if (!payload?.ok) {
                    this.storedPreviewError = @js(__('sMailer::global.builder_preview')) + ' (' + (payload?.code || 'unavailable') + ')';
                    return;
                }

                this.storedPreviewHtml = payload.html || '';
                this.storedPreviewOpen = true;
            } catch (error) {
                this.storedPreviewError = @js(__('sMailer::global.builder_preview')) + ' (unavailable)';
            }
        },
        async openOnceDelivery(mailingId) {
            this.deliveryError = '';
            this.deliverySuccess = '';
            try {
                const payload = await $wire.prepareOnceDelivery(Number(mailingId));
                if (!payload?.ok) {
                    this.deliveryError = @js(__('sMailer::global.mailing_send_once_failed'));
                    return;
                }
                this.deliveryMailingId = payload.mailingId;
                this.deliveryMailingName = payload.name || '';
                this.deliveryRecipients = payload.recipients || 0;
                this.deliveryOpen = true;
            } catch (error) {
                this.deliveryError = @js(__('sMailer::global.mailing_send_once_failed'));
            }
        },
        async queueOnceDelivery() {
            if (!this.deliveryMailingId) return;
            this.deliveryError = '';
            try {
                const payload = await $wire.queueOnceDelivery(Number(this.deliveryMailingId));
                if (!payload?.ok) {
                    this.deliveryError = payload?.code === 'already_queued'
                        ? @js(__('sMailer::global.mailing_send_once_already_queued'))
                        : @js(__('sMailer::global.mailing_send_once_failed'));
                    return;
                }
                this.deliveryOpen = false;
                this.deliverySuccess = @js(__('sMailer::global.mailing_send_once_queued')) + ' #' + payload.taskId;
            } catch (error) {
                this.deliveryError = @js(__('sMailer::global.mailing_send_once_failed'));
            }
        },
        openSubscriberImport() {
            this.$refs.subscriberCsv?.click();
        },
        async importSubscribers(event) {
            const file = event.target.files?.[0];
            if (!file) return;

            try {
                const result = await $wire.importSubscribers(await file.text());
                this.subscriberTransferMessage = result?.ok
                    ? `{{ __('sMailer::global.subscribers_import_complete') }}: {{ __('sMailer::global.subscribers_import_created') }} ${result.created || 0}, {{ __('sMailer::global.subscribers_import_updated') }} ${result.updated || 0}, {{ __('sMailer::global.subscribers_import_skipped') }} ${result.skipped || 0}`
                    : `{{ __('sMailer::global.subscribers_import_csv') }} (${result?.code || 'unavailable'})`;
                this.$dispatch('evo-ui:module-tab-refresh', { tab: 'subscribers' });
            } catch (error) {
                this.subscriberTransferMessage = `{{ __('sMailer::global.subscribers_import_csv') }} (unavailable)`;
            }

            event.target.value = '';
        },
        async exportSubscribers() {
            try {
                const result = await $wire.exportSubscribers();
                if (!result?.ok) return;
                const url = URL.createObjectURL(new Blob([result.csv || ''], { type: 'text/csv;charset=utf-8' }));
                const link = document.createElement('a');
                link.href = url;
                link.download = 'subscribers.csv';
                link.click();
                URL.revokeObjectURL(url);
            } catch (error) {}
        },
    }"
    x-on:smailer:preview-mailing.window="openStoredPreview($event.detail.mailingId)"
    x-on:smailer:queue-mailing.window="openOnceDelivery($event.detail.mailingId)"
    x-on:smailer-subscribers-import.window="openSubscriberImport()"
    x-on:smailer-subscribers-export.window="exportSubscribers()"
>
    <input x-ref="subscriberCsv" type="file" accept=".csv,text/csv" class="evo-ui-sr-only" @change="importSubscribers($event)">
    @if($screen === 'editor')
        @include('sMailer::components.campaign-editor', [
            'collectionUrl' => $context['collectionUrl'] ?? '',
            'mailing' => $context['mailing'] ?? null,
        ])
    @else
    <x-evo::module-tab-shell :tabs="$tabs" model="activeTab" :label="__('sMailer::global.module_navigation')">
        <div x-show="activeTab === 'overview'" x-cloak>
            <section class="evo-ui-dashboard">
                <div class="evo-ui-dashboard__cards">
                    <x-evo::card
                        class="evo-ui-dashboard-card evo-ui-dashboard-card--span-2"
                        label="sMailer::global.overview_subscribers"
                        icon="users"
                        data-evo-dashboard-card-status="primary"
                    >
                        <div class="evo-ui-dashboard-card__stats">
                            <span class="evo-ui-dashboard-card__stat">
                                <strong>{{ $subscriberMetrics['total'] }}</strong>
                                <span>{{ __('sMailer::global.subscriber_total') }}</span>
                            </span>
                        </div>
                    </x-evo::card>

                    <x-evo::card
                        class="evo-ui-dashboard-card evo-ui-dashboard-card--span-2"
                        label="sMailer::global.overview_active"
                        icon="user-check"
                        data-evo-dashboard-card-status="success"
                    >
                        <div class="evo-ui-dashboard-card__stats">
                            <span class="evo-ui-dashboard-card__stat">
                                <strong>{{ $subscriberMetrics['active'] }}</strong>
                                <span>{{ __('sMailer::global.subscriber_active') }}</span>
                            </span>
                        </div>
                    </x-evo::card>

                    <x-evo::card
                        class="evo-ui-dashboard-card evo-ui-dashboard-card--span-2"
                        label="sMailer::global.overview_campaigns"
                        icon="speakerphone"
                        data-evo-dashboard-card-status="info"
                    >
                        <div class="evo-ui-dashboard-card__stats">
                            <span class="evo-ui-dashboard-card__stat">
                                <strong>&mdash;</strong>
                                <span>{{ __('sMailer::global.metric_unavailable') }}</span>
                            </span>
                        </div>
                    </x-evo::card>

                    <x-evo::card
                        class="evo-ui-dashboard-card evo-ui-dashboard-card--span-2"
                        label="sMailer::global.overview_scheduled_delivery"
                        icon="calendar-time"
                        data-evo-dashboard-card-status="info"
                    >
                        <div class="evo-ui-dashboard-card__stats">
                            <span class="evo-ui-dashboard-card__stat">
                                <strong>&mdash;</strong>
                                <span>{{ __('sMailer::global.metric_unavailable') }}</span>
                            </span>
                        </div>
                    </x-evo::card>

                    <x-evo::card
                        class="evo-ui-dashboard-card evo-ui-dashboard-card--span-2"
                        label="sMailer::global.overview_sent"
                        icon="mail-check"
                        data-evo-dashboard-card-status="success"
                    >
                        <div class="evo-ui-dashboard-card__stats">
                            <span class="evo-ui-dashboard-card__stat">
                                <strong>&mdash;</strong>
                                <span>{{ __('sMailer::global.metric_unavailable') }}</span>
                            </span>
                        </div>
                    </x-evo::card>

                    <x-evo::card
                        class="evo-ui-dashboard-card evo-ui-dashboard-card--span-2"
                        label="sMailer::global.overview_failed"
                        icon="mail-x"
                        data-evo-dashboard-card-status="danger"
                    >
                        <div class="evo-ui-dashboard-card__stats">
                            <span class="evo-ui-dashboard-card__stat">
                                <strong>&mdash;</strong>
                                <span>{{ __('sMailer::global.metric_unavailable') }}</span>
                            </span>
                        </div>
                    </x-evo::card>
                </div>

                <section class="evo-ui-dashboard__body">
                    <x-evo::card label="sMailer::global.recent_activity" icon="activity">
                        <p>{{ __('sMailer::global.recent_activity_placeholder') }}</p>
                    </x-evo::card>
                </section>
            </section>
        </div>

        <div x-show="activeTab === 'mailings'" x-cloak data-smailer-mailings-workspace>
            <span class="smailer-module-notice smailer-module-notice--error" x-show="deliveryError" x-text="deliveryError" x-cloak></span>
            <span class="smailer-module-notice smailer-module-notice--success" x-show="deliverySuccess" x-text="deliverySuccess" x-cloak></span>
            <livewire:evo-ui.module-table
                preset="smailer.mailings"
                :context="[
                    'module' => 'smailer',
                    'tab' => 'mailings',
                    'collectionUrl' => $context['collectionUrl'] ?? '',
                ]"
                wire:key="smailer-mailings-table"
            />
        </div>

        <span class="smailer-mailing-preview-error" x-show="storedPreviewError" x-text="storedPreviewError" x-cloak></span>
        <div class="smailer-mailing-preview-backdrop" x-show="storedPreviewOpen" x-cloak @keydown.escape.window="storedPreviewOpen = false" @click.self="storedPreviewOpen = false">
            <section class="smailer-mailing-preview-dialog" role="dialog" aria-modal="true" aria-label="{{ __('sMailer::global.builder_preview') }}">
                <header class="smailer-mailing-preview-header">
                    <strong>{{ __('sMailer::global.builder_preview') }}</strong>
                    <button class="evo-ui-btn evo-ui-btn--icon" type="button" @click="storedPreviewOpen = false" aria-label="{{ __('sMailer::global.builder_preview') }}">&times;</button>
                </header>
                <iframe class="smailer-mailing-preview-frame" :srcdoc="storedPreviewHtml" title="{{ __('sMailer::global.builder_preview') }}"></iframe>
            </section>
        </div>

        <div class="smailer-mailing-preview-backdrop" x-show="deliveryOpen" x-cloak @keydown.escape.window="deliveryOpen = false" @click.self="deliveryOpen = false">
            <section class="smailer-delivery-dialog" role="dialog" aria-modal="true" aria-label="{{ __('sMailer::global.mailing_send_once') }}">
                <header class="smailer-mailing-preview-header">
                    <strong>{{ __('sMailer::global.mailing_send_once') }}</strong>
                    <button class="evo-ui-btn evo-ui-btn--icon" type="button" @click="deliveryOpen = false" aria-label="{{ __('sMailer::global.builder_test_cancel') }}">&times;</button>
                </header>
                <div class="smailer-delivery-dialog__body">
                    <p>{{ __('sMailer::global.mailing_send_once_confirm') }}</p>
                    <strong x-text="deliveryMailingName"></strong>
                    <p>{{ __('sMailer::global.mailing_send_once_recipients') }}: <strong x-text="deliveryRecipients"></strong></p>
                </div>
                <footer class="smailer-delivery-dialog__actions">
                    <button class="evo-ui-btn" type="button" @click="deliveryOpen = false">{{ __('sMailer::global.builder_test_cancel') }}</button>
                    <button class="evo-ui-btn evo-ui-btn--success" type="button" @click="queueOnceDelivery()">{{ __('sMailer::global.mailing_send_once_confirm_action') }}</button>
                </footer>
            </section>
        </div>

        <div x-show="activeTab === 'subscribers'" x-cloak>
            <span class="smailer-module-notice smailer-module-notice--success" x-show="subscriberTransferMessage" x-text="subscriberTransferMessage" x-cloak></span>
            <livewire:evo-ui.module-table
                preset="smailer.subscribers"
                :context="[
                    'module' => 'smailer',
                    'tab' => 'subscribers',
                ]"
                wire:key="smailer-subscribers-table"
            />
        </div>

        <div x-show="activeTab === 'settings'" x-cloak>
            <section class="evo-ui-settings-grid">
                <x-evo::card label="sMailer::global.global_settings_group" icon="settings">
                    <p>{{ __('sMailer::global.global_settings_placeholder') }}</p>
                </x-evo::card>
            </section>
        </div>
    </x-evo::module-tab-shell>
    @endif

    <style>
        [data-smailer-module-panel] .smailer-mailing-preview-backdrop { align-items: center; background: rgba(15, 23, 42, .72); display: flex; inset: 0; justify-content: center; padding: 24px; position: fixed; z-index: 1000; }
        [data-smailer-module-panel] .smailer-mailing-preview-dialog { background: var(--evo-ui-surface, #fff); border: 1px solid var(--evo-ui-border, #374151); border-radius: 10px; box-shadow: 0 24px 80px rgba(0, 0, 0, .45); display: flex; flex-direction: column; height: min(900px, calc(100vh - 48px)); max-width: 980px; overflow: hidden; width: min(100%, 980px); }
        [data-smailer-module-panel] .smailer-mailing-preview-header { align-items: center; border-bottom: 1px solid var(--evo-ui-border, #374151); display: flex; justify-content: space-between; padding: 12px 16px; }
        [data-smailer-module-panel] .smailer-mailing-preview-frame { background: #f3f4f6; border: 0; flex: 1; min-height: 0; width: 100%; }
        [data-smailer-module-panel] .smailer-delivery-dialog { background: var(--evo-ui-surface, #fff); border: 1px solid var(--evo-ui-border, #374151); border-radius: 10px; box-shadow: 0 24px 80px rgba(0, 0, 0, .45); max-width: 480px; overflow: hidden; width: min(100%, 480px); }
        [data-smailer-module-panel] .smailer-delivery-dialog__body { padding: 20px 16px; }
        [data-smailer-module-panel] .smailer-delivery-dialog__actions { border-top: 1px solid var(--evo-ui-border, #374151); display: flex; gap: 8px; justify-content: flex-end; padding: 12px 16px; }
        [data-smailer-module-panel] .smailer-module-notice { display: block; margin: 0 0 12px; padding: 10px 12px; }
        [data-smailer-module-panel] .smailer-module-notice--error { background: color-mix(in srgb, var(--evo-ui-danger, #ef4444) 14%, transparent); border: 1px solid var(--evo-ui-danger, #ef4444); color: var(--evo-ui-danger, #ef4444); }
        [data-smailer-module-panel] .smailer-module-notice--success { background: color-mix(in srgb, var(--evo-ui-success, #22c55e) 14%, transparent); border: 1px solid var(--evo-ui-success, #22c55e); color: var(--evo-ui-success, #22c55e); }
        [data-smailer-module-panel] .smailer-mailing-preview-error { color: var(--evo-ui-danger, #ef4444); display: block; margin-top: 12px; }
        [data-smailer-module-panel] .smailer-subscriber-transfer-message { display: block; margin-top: 12px; }
    </style>
</div>
