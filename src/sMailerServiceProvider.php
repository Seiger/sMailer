<?php namespace Seiger\sMailer;

use EvolutionCMS\ServiceProvider;
use Seiger\sMailer\Console\MailsSendCommand;
use Illuminate\Console\Scheduling\Schedule;

class sMailerServiceProvider extends ServiceProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        'MailsSendCommand' => MailsSendCommand::class,
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Only Manager
        if (IN_MANAGER_MODE) {
            // MultiLang
            $this->loadTranslationsFrom(dirname(__DIR__) . '/lang', 'sMailer');

            // For use config
            $this->publishes([
                dirname(__DIR__) . '/config/sMailerAlias.php' => config_path('app/aliases/sMailer.php', true),
                dirname(__DIR__) . '/config/sMailerSettings.php' => config_path('seiger/settings/sMailer.php', true),
                dirname(__DIR__) . '/images/noimage.png' => public_path('assets/images/noimage.png'),
                dirname(__DIR__) . '/images/seigerit-blue.svg' => public_path('assets/site/seigerit-blue.svg'),
            ]);
        }

        // Views
        $this->loadViewsFrom(dirname(__DIR__) . '/views', 'sMailer');

        // Commands
        if (count($this->commands)) {
            $this->app->booted(function () {
                $this->defineConsoleSchedule();
            });
        }

        $this->app->singleton(\Seiger\sMailer\sMailer::class);
        $this->app->alias(\Seiger\sMailer\sMailer::class, 'sMailer');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Only Manager
        if (IN_MANAGER_MODE) {
            // Add module to Evo. Module ID is md5('sMailer').
            $lang = 'en';
            if (isset($_SESSION['mgrUsrConfigSet']['manager_language'])) {
                $lang = $_SESSION['mgrUsrConfigSet']['manager_language'];
            } else {
                if (is_file(evo()->getSiteCacheFilePath())) {
                    $siteCache = file_get_contents(evo()->getSiteCacheFilePath());
                    preg_match('@\$c\[\'manager_language\'\]="\w+@i', $siteCache, $matches);
                    if (count($matches)) {
                        $lang = str_replace('$c[\'manager_language\']="', '', $matches[0]);
                    }
                }
            }
            $lang = include_once dirname(__DIR__) . '/lang/' . $lang . '/global.php';
            $this->app->registerModule($lang['mailer'], dirname(__DIR__) . '/module/sMailerModule.php', $lang['mailer_icon']);
        }

        // Commands
        if ($this->app->runningInConsole() && count($this->commands)) {
            $this->commands($this->commands);
        }
    }

    /**
     * Define the application's command schedule.
     *
     * @note check timezones list timezone_identifiers_list()
     *
     * @return void
     */
    protected function defineConsoleSchedule()
    {
        $this->app->singleton(Schedule::class, function ($app) {
            return tap(new Schedule('Europe/Kyiv'), function ($schedule) {
                $this->schedule($schedule->useCache('file'));
            });
        });
    }

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    public function schedule(Schedule $schedule)
    {
        foreach ($this->commands as $command) {
            (new $command)->schedule($schedule);
        }
    }
}
