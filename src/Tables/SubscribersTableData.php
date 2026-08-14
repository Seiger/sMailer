<?php namespace Seiger\sMailer\Tables;

use EvoUI\Contracts\ModuleTableProvider;
use Seiger\sMailer\Models\Subscriber;

/** Provide the package-owned subscriber directory through EvoUI. */
class SubscribersTableData implements ModuleTableProvider
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
            ->map(fn (Subscriber $subscriber): array => $this->row($subscriber))
            ->all();
    }

    /** @return list<array<string, mixed>> */
    public function filterGroups(): array
    {
        return [[
            'key' => 'status',
            'items' => [
                ['id' => 1, 'label' => __('sMailer::global.subscriber_status_active')],
                ['id' => 2, 'label' => __('sMailer::global.subscriber_status_unsubscribed')],
                ['id' => 3, 'label' => __('sMailer::global.subscriber_status_blocked')],
            ],
        ]];
    }

    /**
     * Fulfil the shared table-provider contract without exposing subscriber
     * creation or edits before their lifecycle workflow is implemented.
     */
    public function saveModal(array $data, ?int $recordId = null, string $mode = 'create'): int
    {
        return 0;
    }

    /** Toggle an administrator-controlled subscription without deleting the recipient. */
    public function toggleSubscription(int $subscriberId): void
    {
        $subscriber = Subscriber::query()->find($subscriberId);

        if (!$subscriber || $subscriber->status === 'blocked') {
            return;
        }

        if ($subscriber->status === 'unsubscribed') {
            $subscriber->update([
                'status' => 'active',
                'subscribed_at' => now(),
                'unsubscribed_at' => null,
            ]);

            return;
        }

        $subscriber->update([
            'status' => 'unsubscribed',
            'unsubscribed_at' => now(),
        ]);
    }

    public function deleteName(int $subscriberId): string
    {
        return (string) (Subscriber::query()->find($subscriberId)?->email ?? '');
    }

    public function deleteRow(int $subscriberId): void
    {
        Subscriber::query()->whereKey($subscriberId)->delete();
    }

    protected function query()
    {
        $query = Subscriber::query();
        $search = trim((string) ($this->state['search'] ?? ''));

        if ($search !== '') {
            $query->where(function ($query) use ($search): void {
                $query->where('email', 'like', '%' . addcslashes($search, '%_\\') . '%')
                    ->orWhere('name', 'like', '%' . addcslashes($search, '%_\\') . '%');
            });
        }

        $status = array_map('intval', (array) ((array) ($this->state['filters'] ?? [])['status'] ?? []));
        if ($status !== []) {
            $query->whereIn('status', array_values(array_intersect_key([
                1 => 'active',
                2 => 'unsubscribed',
                3 => 'blocked',
            ], array_flip($status))));
        }

        $sorts = ['domain', 'lang', 'email', 'status', 'subscribed_at', 'created_at'];
        $sort = (string) ($this->state['sort'] ?? 'subscribed_at');
        $direction = (string) ($this->state['direction'] ?? 'desc');

        return $query->orderBy(
            in_array($sort, $sorts, true) ? $sort : 'subscribed_at',
            $direction === 'asc' ? 'asc' : 'desc',
        );
    }

    /** @return array<string, mixed> */
    protected function row(Subscriber $subscriber): array
    {
        return [
            'id' => $subscriber->id,
            'wire_key' => 'smailer-subscriber-' . $subscriber->id,
            'email' => $subscriber->email,
            'domain' => $subscriber->domain ?: 'default',
            'domain_display' => app(\Seiger\sMailer\Services\DomainContext::class)->label($subscriber->domain ?: 'default'),
            'lang' => $subscriber->lang ?: 'base',
            'name' => $subscriber->name ?: '—',
            'is_unsubscribed' => $subscriber->status === 'unsubscribed',
            'subscription_disabled' => $subscriber->status === 'blocked',
            'status_display' => [[
                'label' => __('sMailer::global.subscriber_status_' . $subscriber->status),
                'icon' => match ($subscriber->status) {
                    'active' => 'circle-check',
                    'unsubscribed' => 'mail-off',
                    default => 'lock',
                },
            ]],
            'subscribed_at' => $subscriber->subscribed_at?->format('d.m.Y H:i')
                ?? $subscriber->created_at?->format('d.m.Y H:i')
                ?? '—',
        ];
    }
}
