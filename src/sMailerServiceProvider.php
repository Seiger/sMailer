<?php namespace Seiger\sMailer;

use EvolutionCMS\ServiceProvider;
use EvoUI\EvoUI;
use Illuminate\Support\Facades\File;
use Seiger\sMailer\Components\ModulePanel;

/**
 * Register the sMailer 2.x package foundation with Evolution CMS.
 *
 * The provider owns only package discovery and the EvoUI manager boundary.
 * Campaign persistence lives in the package. One-time delivery is delegated
 * to sTask; this provider does not register a competing scheduler or queue.
 *
 * @since 2.0.0
 */
class sMailerServiceProvider extends ServiceProvider
{
    public const MODULE_ICON = 'tabler-mail-fast';

    /**
     * Load the package UI resources after the Evolution application boots.
     *
     * EvoUI component registration is skipped during transitional Composer
     * discovery and becomes available on the next normal application boot.
     *
     * @return void
     * @since 2.0.0
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(dirname(__DIR__) . '/lang', 'sMailer');
        $this->loadViewsFrom(dirname(__DIR__) . '/views', 'sMailer');
        $this->loadMigrationsFrom(dirname(__DIR__) . '/database/migrations');
        $this->mergeConfigFrom(dirname(__DIR__) . '/config/mailings/table.php', 'smailer.mailings.table');
        $this->mergeConfigFrom(dirname(__DIR__) . '/config/subscribers/table.php', 'smailer.subscribers.table');
        $this->loadRoutesFrom(dirname(__DIR__) . '/src/Http/routes.php');
        $this->cacheEmailAssets();
        $this->registerEvoUIComponents();
    }

    /**
     * Bind the package API and expose its manager module when manager mode is active.
     *
     * No scheduler or delivery command is registered by the 2.x foundation.
     *
     * @return void
     * @since 2.0.0
     */
    public function register(): void
    {
        $this->app->singleton(sMailer::class);
        $this->app->alias(sMailer::class, 'sMailer');

        if (defined('IN_MANAGER_MODE') && IN_MANAGER_MODE) {
            $this->registerManagerModule();
        }
    }

    /**
     * Declare the interactive manager component through the EvoUI runtime boundary.
     *
     * @return void
     * @since 2.0.0
     */
    protected function registerEvoUIComponents(): void
    {
        if (!$this->app->bound(EvoUI::class)) {
            return;
        }

        $this->app->make(EvoUI::class)->registerComponent(
            'smailer.module-panel',
            ModulePanel::class
        );
    }

    /**
     * Make publicly reachable social icon files available without relying on
     * inline SVG, which many mail clients remove.
     */
    protected function cacheEmailAssets(): void
    {
        $sourceDirectory = dirname(__DIR__, 3) . '/secondnetwork/blade-tabler-icons/resources/svg';
        $targetDirectory = public_path('assets/cache/images/smailer');

        if (!is_dir($sourceDirectory)) {
            return;
        }

        File::ensureDirectoryExists($targetDirectory);
        foreach ([
            'brand-facebook', 'brand-instagram', 'brand-youtube', 'brand-linkedin',
            'brand-tiktok', 'brand-telegram', 'brand-whatsapp', 'brand-x',
        ] as $icon) {
            $source = $sourceDirectory . '/' . $icon . '.svg';
            $target = $targetDirectory . '/' . $icon . '.svg';
            if (is_file($source) && !is_file($target)) {
                File::copy($source, $target);
            }
        }
    }

    /**
     * Register the localized sMailer manager entry without booting legacy UI code.
     *
     * @return void
     * @since 2.0.0
     */
    protected function registerManagerModule(): void
    {
        $language = (string)($_SESSION['mgrUsrConfigSet']['manager_language'] ?? 'en');
        $languageFile = dirname(__DIR__) . "/lang/{$language}/global.php";
        $fallbackFile = dirname(__DIR__) . '/lang/en/global.php';
        $labels = include is_file($languageFile) ? $languageFile : $fallbackFile;

        $this->app->registerModule(
            $labels['module_title'] ?? $labels['mailer'] ?? 'sMailer',
            dirname(__DIR__) . '/module/sMailerModule.php',
            $labels['module_icon'] ?? self::MODULE_ICON
        );
    }
}
