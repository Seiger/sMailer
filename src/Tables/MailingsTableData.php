<?php namespace Seiger\sMailer\Tables;

use EvoUI\Contracts\ModuleTableProvider;
use Carbon\Carbon;
use Seiger\sMailer\Models\Mailing;
use Seiger\sMailer\Services\MailingDeliveryQueue;

/**
 * Provide persisted Mailing documents through the shared EvoUI table contract.
 *
 * @since 2.0.0
 */
class MailingsTableData implements ModuleTableProvider
{
    /**
     * @param array<string, mixed> $context
     * @param array<string, mixed> $state
     * @param array<string, mixed> $config
     */
    public function __construct(
        protected array $context = [],
        protected array $state = [],
        protected array $config = [],
    ) {}

    public function total(): int
    {
        return $this->query()->count();
    }

    /** @return list<array<string, mixed>> */
    public function rows(int $page, int $perPage): array
    {
        return $this->query()
            ->forPage($page, $perPage)
            ->get()
            ->map(fn (Mailing $mailing): array => $this->row($mailing))
            ->all();
    }

    /** @return list<array<string, mixed>> */
    public function filterGroups(): array
    {
        return [
            [
                'key' => 'status',
                'items' => [
                    ['id' => 1, 'label' => __('sMailer::global.mailing_status_draft')],
                    ['id' => 2, 'label' => __('sMailer::global.mailing_status_ready')],
                    ['id' => 3, 'label' => __('sMailer::global.mailing_status_active')],
                    ['id' => 4, 'label' => __('sMailer::global.mailing_status_paused')],
                ],
            ],
            [
                'key' => 'delivery_mode',
                'items' => [
                    ['id' => 1, 'label' => __('sMailer::global.delivery_manual')],
                    ['id' => 2, 'label' => __('sMailer::global.delivery_once')],
                    ['id' => 3, 'label' => __('sMailer::global.delivery_recurring')],
                ],
            ],
        ];
    }

    /** @return array{name: string, domain: string, lang: string, status: string, delivery_mode: string, scheduled_at: string, recurrence_frequency: string, recurrence_time: string, recurrence_weekday: string, recurrence_day_of_month: int} */
    public function modalDefaults(): array
    {
        return [
            'name' => '',
            'domain' => 'default',
            'lang' => 'base',
            'status' => 'draft',
            'delivery_mode' => 'manual',
            'scheduled_at' => '',
            'recurrence_frequency' => 'daily',
            'recurrence_time' => '09:00',
            'recurrence_weekday' => 'monday',
            'recurrence_day_of_month' => 1,
        ];
    }

    /** @return array{name: string, domain: string, lang: string, status: string, delivery_mode: string, scheduled_at: string, recurrence_frequency: string, recurrence_time: string, recurrence_weekday: string, recurrence_day_of_month: int} */
    public function modalData(int $mailingId): array
    {
        $mailing = Mailing::query()->find($mailingId);

        if (!$mailing) {
            return $this->modalDefaults();
        }

        $recurrence = (array) data_get($mailing->document, 'delivery.recurrence', []);

        return [
            'name' => (string) $mailing->name,
            'domain' => (string) ($mailing->domain ?: 'default'),
            'lang' => (string) ($mailing->lang ?: 'base'),
            'status' => (string) $mailing->status,
            'delivery_mode' => (string) $mailing->delivery_mode,
            'scheduled_at' => $mailing->scheduled_at?->format('Y-m-d\\TH:i') ?? '',
            'recurrence_frequency' => (string) ($recurrence['frequency'] ?? 'daily'),
            'recurrence_time' => (string) ($recurrence['time'] ?? '09:00'),
            'recurrence_weekday' => (string) ($recurrence['weekday'] ?? 'monday'),
            'recurrence_day_of_month' => max(1, min(31, (int) ($recurrence['day_of_month'] ?? 1))),
        ];
    }

    /** Persist only campaign metadata; the Builder owns the template document. */
    public function saveModal(array $data, ?int $recordId = null, string $mode = 'create'): int
    {
        if ($mode !== 'edit' || !$recordId) {
            return 0;
        }

        $deliveryMode = (string) data_get($data, 'delivery_mode', 'manual');
        $scheduledAt = null;
        $recurrence = null;
        if ($deliveryMode === 'once') {
            $value = trim((string) data_get($data, 'scheduled_at'));
            if ($value === '') {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'scheduled_at' => __('sMailer::global.mailing_scheduled_at_required'),
                ]);
            }
            $scheduledAt = Carbon::parse($value);
            if (!$scheduledAt->isFuture()) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'scheduled_at' => __('sMailer::global.mailing_scheduled_at_future'),
                ]);
            }
        }

        $mailing = Mailing::query()->findOrFail($recordId);
        $document = is_array($mailing->document) ? $mailing->document : [];
        if ($deliveryMode === 'recurring') {
            $frequency = (string) data_get($data, 'recurrence_frequency', 'daily');
            $time = (string) data_get($data, 'recurrence_time', '');
            $weekday = (string) data_get($data, 'recurrence_weekday', 'monday');
            $dayOfMonth = (int) data_get($data, 'recurrence_day_of_month', 1);

            if (!in_array($frequency, ['daily', 'weekly', 'monthly'], true)
                || !preg_match('/^(?:[01]\\d|2[0-3]):[0-5]\\d$/', $time)
                || ($frequency === 'weekly' && !in_array($weekday, ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'], true))
                || ($frequency === 'monthly' && ($dayOfMonth < 1 || $dayOfMonth > 31))) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'recurrence_frequency' => __('sMailer::global.mailing_recurrence_invalid'),
                ]);
            }

            $recurrence = [
                'frequency' => $frequency,
                'time' => $time,
                'weekday' => $weekday,
                'day_of_month' => $dayOfMonth,
            ];
            data_set($document, 'delivery.recurrence', $recurrence);
        } else {
            data_forget($document, 'delivery.recurrence');
        }

        $domain = array_key_exists('domain', $data)
            ? trim((string) data_get($data, 'domain')) ?: 'default'
            : ($mailing->domain ?: 'default');
        $lang = array_key_exists('lang', $data)
            ? trim((string) data_get($data, 'lang')) ?: 'base'
            : ($mailing->lang ?: 'base');

        $mailing->update([
            'name' => trim((string) data_get($data, 'name')),
            'domain' => $domain,
            'lang' => $lang,
            'status' => (string) data_get($data, 'status', 'draft'),
            'delivery_mode' => $deliveryMode,
            'scheduled_at' => $scheduledAt,
            'document' => $document,
        ]);

        if ($deliveryMode === 'once') {
            app(MailingDeliveryQueue::class)->enqueue((int) $mailing->id, $scheduledAt, 'once');
        }

        if ($deliveryMode === 'recurring') {
            app(MailingDeliveryQueue::class)->enqueueRecurring($mailing);
        } elseif ($deliveryMode === 'manual') {
            app(MailingDeliveryQueue::class)->cancelScheduled((int) $mailing->id);
        }

        return (int) $mailing->id;
    }

    /**
     * Build the manager-native route for a new or existing campaign editor.
     *
     * @param array<string, mixed> $action
     */
    public function editorUrl(array $action = [], ?int $mailingId = null): string
    {
        return $this->screenUrl('editor', $mailingId);
    }

    protected function screenUrl(string $screen, ?int $mailingId = null): string
    {
        $url = (string) ($this->context['collectionUrl'] ?? '');

        if ($url === '') {
            return '#';
        }

        $separator = str_contains($url, '?') ? '&' : '?';
        $mailing = $mailingId && $mailingId > 0 ? (string) $mailingId : 'new';

        return $url . $separator . http_build_query([
            'smailer_screen' => $screen,
            'smailer_mailing' => $mailing,
        ]);
    }

    protected function query()
    {
        $query = Mailing::query()->orderByDesc('updated_at');
        $search = trim((string) ($this->state['search'] ?? ''));
        if ($search !== '') {
            $query->where('name', 'like', '%' . addcslashes($search, '%_\\') . '%');
        }

        $filters = (array) ($this->state['filterState'] ?? []);
        $status = array_map('intval', (array) ($filters['status'] ?? []));
        if ($status !== []) {
            $statuses = array_intersect_key([
                1 => 'draft',
                2 => 'ready',
                3 => 'active',
                4 => 'paused',
            ], array_flip($status));
            $query->whereIn('status', $statuses);
        }

        $deliveryModes = array_map('intval', (array) ($filters['delivery_mode'] ?? []));
        if ($deliveryModes !== []) {
            $modes = array_intersect_key([
                1 => 'manual',
                2 => 'once',
                3 => 'recurring',
            ], array_flip($deliveryModes));
            $query->whereIn('delivery_mode', $modes);
        }

        return $query;
    }

    /** @return array<string, mixed> */
    protected function row(Mailing $mailing): array
    {
        return [
            'id' => $mailing->id,
            'wire_key' => 'smailer-mailing-' . $mailing->id,
            'name' => $mailing->name,
            'domain' => $mailing->domain ?: 'default',
            'domain_display' => app(\Seiger\sMailer\Services\DomainContext::class)->label($mailing->domain ?: 'default'),
            'lang' => $mailing->lang ?: 'base',
            'description' => __('sMailer::global.mailing_layout_preview_description'),
            'status' => $mailing->status,
            'status_display' => [
                [
                    'label' => __('sMailer::global.mailing_status_' . $mailing->status),
                    'icon' => 'circle-dashed',
                ],
            ],
            'delivery_mode' => $mailing->delivery_mode,
            'delivery_display' => [
                [
                    'label' => __('sMailer::global.delivery_' . $mailing->delivery_mode),
                    'icon' => 'hand-stop',
                ],
                [
                    'label' => $mailing->scheduled_at
                        ? __('sMailer::global.delivery_scheduled')
                        : __('sMailer::global.delivery_unscheduled'),
                    'icon' => $mailing->scheduled_at ? 'calendar-check' : 'calendar-off',
                ],
            ],
            'next_delivery' => $mailing->scheduled_at?->format('d.m.Y H:i') ?? '—',
            'editor_url' => $this->editorUrl([], $mailing->id),
            'preview_action' => "\$dispatch('smailer:preview-mailing', { mailingId: " . (int) $mailing->id . ' })',
            'queue_action' => "\$dispatch('smailer:queue-mailing', { mailingId: " . (int) $mailing->id . ' })',
        ];
    }
}
