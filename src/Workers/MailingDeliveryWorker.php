<?php namespace Seiger\sMailer\Workers;

use Illuminate\Support\Facades\Log;
use Seiger\sMailer\Components\ModulePanel;
use Seiger\sMailer\Models\Mailing;
use Seiger\sMailer\Models\Subscriber;
use Seiger\sMailer\Services\MailingDeliveryQueue;
use Seiger\sTask\Models\sTaskModel;
use Seiger\sTask\Workers\BaseWorker;

/**
 * Deliver one persisted campaign to the active subscriber audience.
 *
 * The task stores only the mailing ID. The document, dynamic product data and
 * recipients are read when the sTask worker executes, so a queued campaign
 * never uses a browser snapshot or a cached recipient list.
 */
class MailingDeliveryWorker extends BaseWorker
{
    public function identifier(): string
    {
        return 'smailer_delivery';
    }

    public function scope(): string
    {
        return 'smailer';
    }

    public function icon(): string
    {
        return '<i class="fa fa-envelope"></i>';
    }

    public function title(): string
    {
        return __('sMailer::global.delivery_task_title');
    }

    public function description(): string
    {
        return __('sMailer::global.delivery_task_description');
    }

    public function renderWidget(): string
    {
        return '';
    }

    /** @param array{mailing_id?: int} $options */
    public function taskSendOnce(sTaskModel $task, array $options = []): void
    {
        $mailingId = (int) ($options['mailing_id'] ?? 0);
        $mailing = Mailing::query()->find($mailingId);
        if (!$mailing || !is_array($mailing->document)) {
            throw new \RuntimeException('Mailing document is unavailable.');
        }

        $originalLocale = app()->getLocale();
        $mailingLocale = $mailing->lang ?: 'base';
        if ($mailingLocale !== 'base') {
            app()->setLocale($mailingLocale);
        }

        try {

        $deliveryKind = (string) ($options['delivery_kind'] ?? 'manual');
        if (in_array($deliveryKind, ['once', 'recurring'], true) && $mailing->delivery_mode !== $deliveryKind) {
            $this->markFinished($task, null, __('sMailer::global.delivery_task_cancelled'));
            return;
        }

        $task->update([
            'status' => sTaskModel::TASK_STATUS_PREPARING,
            'message' => __('sMailer::global.delivery_task_preparing'),
        ]);

        // Reuse the same document renderer and live product selection as the
        // manager preview. No rendered HTML is persisted in the campaign.
        $preview = app(ModulePanel::class)->renderPreview($mailing->document);
        if (!($preview['ok'] ?? false) || empty($preview['html'])) {
            throw new \RuntimeException('Mailing HTML could not be rendered.');
        }

        $subscribers = Subscriber::query()
            ->where('domain', $mailing->domain ?: 'default')
            ->where('lang', $mailing->lang ?: 'base')
            ->where('status', 'active');
        $total = (clone $subscribers)->count();
        $sent = 0;
        $failed = 0;
        $task->update([
            'status' => sTaskModel::TASK_STATUS_RUNNING,
            'message' => __('sMailer::global.delivery_task_sending'),
        ]);
        $this->pushProgress($task, [
            'progress' => 0,
            'processed' => 0,
            'total' => $total,
            'message' => __('sMailer::global.delivery_task_sending'),
        ]);

        foreach ($subscribers->orderBy('id')->cursor() as $subscriber) {
            $recipient = trim((string) $subscriber->email);
            $wasSent = false;

            try {
                $wasSent = (bool) evo()->sendmail([
                    'type' => 'html',
                    'from' => evo()->getConfig('site_name') . '<' . evo()->getConfig('emailsender') . '>',
                    'to' => $recipient,
                    'subject' => (string) $mailing->name,
                    'body' => (string) $preview['html'],
                ]);
            } catch (\Throwable $error) {
                Log::warning('sMailer campaign recipient delivery failed.', [
                    'task_id' => $task->id,
                    'mailing_id' => $mailing->id,
                    'subscriber_id' => $subscriber->id,
                    'error' => $error->getMessage(),
                ]);
            }

            $wasSent ? $sent++ : $failed++;
            $processed = $sent + $failed;
            $this->pushProgress($task, [
                'progress' => $total > 0 ? (int) floor($processed * 100 / $total) : 100,
                'processed' => $processed,
                'total' => $total,
                'message' => __('sMailer::global.delivery_task_sending'),
            ]);
        }

        $this->markFinished(
            $task,
            null,
            __('sMailer::global.delivery_task_finished', compact('sent', 'total', 'failed')),
        );

        if ($deliveryKind === 'recurring' && ($freshMailing = $mailing->fresh())) {
            app(MailingDeliveryQueue::class)->scheduleNextRecurring($freshMailing);
        }
        } finally {
            app()->setLocale($originalLocale);
        }
    }
}
