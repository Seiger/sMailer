<?php namespace Seiger\sMailer\Services;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Seiger\sMailer\Models\Mailing;
use Seiger\sMailer\Workers\MailingDeliveryWorker;
use Seiger\sTask\Models\sTaskModel;
use Seiger\sTask\Models\sWorker;
use Seiger\sTask\Services\WorkerService;

/** Create immediate and scheduled sMailer tasks through the native sTask queue. */
class MailingDeliveryQueue
{
    public function enqueue(int $mailingId, ?CarbonInterface $startAt = null, string $deliveryKind = 'manual'): sTaskModel
    {
        $identifier = (new MailingDeliveryWorker())->identifier();
        $existing = sTaskModel::query()
            ->where('identifier', $identifier)
            ->incomplete()
            ->get()
            ->first(fn (sTaskModel $task): bool => (int) data_get($task->meta, 'mailing_id') === $mailingId);

        if ($existing) {
            if ($existing->isRunning()) {
                throw new \RuntimeException('Campaign delivery is already running.');
            }

            $existing->update([
                'start_at' => $startAt,
                'meta' => array_merge((array) $existing->meta, ['delivery_kind' => $deliveryKind]),
            ]);
            return $existing;
        }

        $this->ensureWorker($identifier);
        $task = app(MailingDeliveryWorker::class)->createTask('send_once', [
            'mailing_id' => $mailingId,
            'delivery_kind' => $deliveryKind,
        ]);
        if ($startAt) {
            $task->update(['start_at' => $startAt]);
        }

        return $task;
    }

    public function enqueueRecurring(Mailing $mailing): sTaskModel
    {
        $task = $this->enqueue((int) $mailing->id, $this->nextRecurringAt($mailing), 'recurring');
        $mailing->update(['scheduled_at' => $task->start_at]);

        return $task;
    }

    public function scheduleNextRecurring(Mailing $mailing): ?sTaskModel
    {
        if ($mailing->delivery_mode !== 'recurring') {
            return null;
        }

        return $this->enqueueRecurring($mailing);
    }

    /** Remove a future scheduled delivery when its campaign is changed back to manual. */
    public function cancelScheduled(int $mailingId): void
    {
        $identifier = (new MailingDeliveryWorker())->identifier();
        sTaskModel::query()
            ->where('identifier', $identifier)
            ->queued()
            ->get()
            ->filter(fn (sTaskModel $task): bool => $task->start_at !== null && (int) data_get($task->meta, 'mailing_id') === $mailingId)
            ->each(fn (sTaskModel $task) => $task->delete());
    }

    /** Calculate the next run from the recurring configuration stored in the mailing document. */
    public function nextRecurringAt(Mailing $mailing): Carbon
    {
        $recurrence = (array) data_get($mailing->document, 'delivery.recurrence', []);
        $frequency = (string) ($recurrence['frequency'] ?? 'daily');
        $time = (string) ($recurrence['time'] ?? '09:00');
        if (!preg_match('/^(?:[01]\\d|2[0-3]):[0-5]\\d$/', $time)) {
            throw new \InvalidArgumentException('Recurring delivery time is invalid.');
        }

        [$hour, $minute] = array_map('intval', explode(':', $time));
        $now = now();

        if ($frequency === 'daily') {
            $next = $now->copy()->hour($hour)->minute($minute)->second(0);
            return $next->isFuture() ? $next : $next->addDay();
        }

        if ($frequency === 'weekly') {
            $weekday = (string) ($recurrence['weekday'] ?? 'monday');
            $days = ['sunday' => 0, 'monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4, 'friday' => 5, 'saturday' => 6];
            if (!array_key_exists($weekday, $days)) {
                throw new \InvalidArgumentException('Recurring delivery weekday is invalid.');
            }

            for ($offset = 0; $offset <= 7; $offset++) {
                $next = $now->copy()->addDays($offset)->hour($hour)->minute($minute)->second(0);
                if ((int) $next->format('w') === $days[$weekday] && $next->isFuture()) {
                    return $next;
                }
            }
        }

        if ($frequency === 'monthly') {
            $day = max(1, min(31, (int) ($recurrence['day_of_month'] ?? 1)));
            $next = $now->copy()->startOfMonth()->day(min($day, $now->daysInMonth))->hour($hour)->minute($minute)->second(0);
            if (!$next->isFuture()) {
                $next->addMonthNoOverflow()->startOfMonth()->day(min($day, $next->daysInMonth))->hour($hour)->minute($minute)->second(0);
            }
            return $next;
        }

        throw new \InvalidArgumentException('Recurring delivery frequency is invalid.');
    }

    protected function ensureWorker(string $identifier): void
    {
        $worker = sWorker::query()->firstOrNew(['identifier' => $identifier]);
        $worker->fill([
            'scope' => 'smailer',
            'class' => MailingDeliveryWorker::class,
            'active' => true,
            'hidden' => true,
            'settings' => [],
        ]);
        if (!$worker->exists) {
            $worker->position = ((int) sWorker::query()->max('position')) + 1;
        }
        $worker->save();
        app(WorkerService::class)->clearCache($identifier);
    }
}
