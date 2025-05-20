<?php namespace Seiger\sMailer\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Seiger\sMailer\Controllers\sMailerController;
use Seiger\sMailer\Models\sMailerQueue;
use Seiger\sMailer\Models\sMailerUser;

class MailsOnceSendCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'smailer:once-send {email? : Use Email if need send it anybody}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Send none periodic email mesage for website subscribers.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $logText = '';
        \View::getFinder()->setPaths([evo()->resourcePath('modules/smailer')]);

        if (config('seiger.settings.sMailer.once.published', 0) == 1) {
            $param = [];
            $param['type'] = 'html';
            $param['from'] = evo()->getConfig('site_name') . '<' . evo()->getConfig('emailsender') . '>';
            $param['subject'] = config('seiger.settings.sMailer.once.subject', '');
            $message = \View::make('onceTemplate')->render();

            if ($email = $this->argument('email')) {
                $param['body'] = $message;
                $param['to'] = $email;
                if (evo()->sendmail($param)) {
                    $logText .= 'Once mail succes send to ' . $email . '. ';
                } else {
                    $logText .= 'Somsing went wrong with ' . $email . ' once mail. ';
                }
            } else {
                $start = evo()->now()->setSeconds(0)->setMilliseconds(0);
                $end = $start->copy()->addMinutes(5)->subMillisecond();
                $at = \Carbon\Carbon::parse(config('seiger.settings.sMailer.once.datetime', 'yesterday'));

                if ($start <= $at && $at < $end) {
                    $subscribers = sMailerUser::whereBlocked(0)->whereSubscribe(1)->get();
                    if ($subscribers) {
                        foreach ($subscribers as $subscriber) {
                            sMailerQueue::insert([
                                'id' => $subscriber->id,
                                'email' => $subscriber->email,
                                'type' => 'once'
                            ]);
                        }
                        $logText .= 'Once mailing subscribers queue is ready.'."\n";
                    } else {
                        $logText .= 'Once mailing subscribers list is empty.'."\n";
                    }
                }

                $queues = sMailerQueue::whereType('once')->limit(config('seiger.settings.sMailer.config.take_of_each', 10))->get();
                if ($queues && trim($message)) {
                    foreach ($queues as $queue) {
                        $unsubscribe_link = config('seiger.settings.sMailer.config.site_url', '/') . 'unsubscribe/' . $queue->id . '-' . $queue->email;
                        $message = str_replace('[+unsubscribe_link+]', $unsubscribe_link, $message);
                        $param['body'] = $message;
                        $param['to'] = $queue->email;
                        sMailerQueue::whereId($queue->id)->whereType($queue->type)->delete();
                        //if (evo()->sendmail($param)) {
                            $logText .= 'Once mail succes send to ' . $queue->email . ".\n";
                        //} else {
                        //    $logText .= 'Somsing went wrong with ' . $queue->email . ' once mail.'."\n";
                        //}
                    }

                    if ($queues->count() < 1 && $at < $start && config('seiger.settings.sMailer.once.published', 0) == 1) {
                        $once = config('seiger.settings.sMailer.once', []);
                        $once['published'] = 0;
                        (new sMailerController())->updateConfigure(['once' => $once]);
                    }
                }
            }

            if (trim($logText)) {
                Log::info($logText);
            }
        }

        return $this->info($logText);
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    public function schedule(Schedule $schedule)
    {
        $schedule->command(static::class)->everyFiveMinutes();
    }
}
