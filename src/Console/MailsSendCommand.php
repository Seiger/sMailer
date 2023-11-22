<?php namespace Seiger\sMailer\Console;

use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MailsSendCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'smailer:mails-send {email? : Use Email if need send it anybody}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Send email mesage for website subscribers.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $logText = '';
        \View::getFinder()->setPaths([evo()->resourcePath('modules/smailer')]);

        if (config('seiger.settings.sMailer.periodic.published', 0) == 1) {
            $param = [];
            $param['type'] = 'html';
            $param['from'] = evo()->getConfig('site_name') . '<' . evo()->getConfig('emailsender') . '>';
            $param['subject'] = config('seiger.settings.sMailer.periodic.subject', '');
            $message = \View::make('periodicTemplate')->render();

            if ($email = $this->argument('email')) {
                $param['body'] = $message;
                $param['to'] = $email;
                if (evo()->sendmail($param)) {
                    $logText .= 'Periodic mail succes send to ' . $email . '. ';
                } else {
                    $logText .= 'Somsing went wrong with ' . $email . ' periodic mail. ';
                }
            } else {
                $start = evo()->now()->subMinute();
                $end = evo()->now()->addMinutes(5);
                $day = config('seiger.settings.sMailer.periodic.day', 'Monday');
                $time = str_replace(':', '', config('seiger.settings.sMailer.periodic.time', '09:00'));

                if ($day == $start->dayName && $start->format('Hi') < $time && $end->format('Hi') > $time) {
                    $subscribers = DB::table('s_mailer_users')->whereBlocked(0)->whereSubscribe(1)->get();
                    if ($subscribers) {
                        foreach ($subscribers as $subscriber) {
                            DB::table('s_mailer_queue')->insert(                                [
                                'id' => $subscriber->id,
                                'email' => $subscriber->email,
                                'type' => 'periodic'
                            ]                            );
                        }
                        $logText .= 'Periodic mailing subscribers queue is ready.'."\n";
                    } else {
                        $logText .= 'Periodic mailing subscribers list is empty.'."\n";
                    }
                }

                $queues = DB::table('s_mailer_queue')->limit(config('seiger.settings.sMailer.config.take_of_each', 10))->get();
                if ($queues) {
                    foreach ($queues as $queue) {
                        $unsubscribe_link = config('seiger.settings.sMailer.config.site_url', '/') . 'unsubscribe/' . $queue->id . '-' . $queue->email;
                        $message = str_replace('[+unsubscribe_link+]', $unsubscribe_link, $message);
                        $param['body'] = $message;
                        $param['to'] = $queue->email;
                        DB::table('s_mailer_queue')->whereId($queue->id)->whereType($queue->type)->delete();
                        if (evo()->sendmail($param)) {
                            $logText .= 'Periodic mail succes send to ' . $queue->email . ".\n";
                        } else {
                            $logText .= 'Somsing went wrong with ' . $queue->email . ' periodic mail.'."\n";
                        }
                    }
                }
            }
        } else {
            $logText .= 'Periodic mailing is off. ';
        }

        if (trim($logText)) {
            Log::info($logText);
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
