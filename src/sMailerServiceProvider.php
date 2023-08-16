<?php namespace Seiger\sMailer;

use EvolutionCMS\ServiceProvider;

class sMailerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Only Manager
        if (IN_MANAGER_MODE) {
            // Add custom routes for package
            //include(__DIR__.'/Http/routes.php');

            // MultiLang
            //$this->loadTranslationsFrom(dirname(__DIR__) . '/lang', 'sMailer');

            // For use config
            $this->publishes([
                dirname(__DIR__) . '/config/sMailerAlias.php' => config_path('app/aliases/sMailer.php', true),
                dirname(__DIR__) . '/config/sMailerSettings.php' => config_path('seiger/smailer/sMailer.php', true),
                dirname(__DIR__) . '/images/noimage.png' => public_path('assets/images/noimage.png'),
                dirname(__DIR__) . '/images/seigerit-yellow.svg' => public_path('assets/site/seigerit-yellow.svg'),
            ]);
        }

        // Views
        $this->loadViewsFrom(dirname(__DIR__) . '/views', 'sMailer');

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
        // Add plugins to Evo
        //$this->loadPluginsFrom(dirname(__DIR__) . '/plugins/');
    }
}
