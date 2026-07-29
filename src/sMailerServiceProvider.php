<?php namespace Seiger\sMailer;

use EvolutionCMS\ServiceProvider;
use EvoUI\EvoUI;
use Seiger\sMailer\Components\ModulePanel;

/**
 * Register the sMailer 2.x package foundation with Evolution CMS.
 *
 * The provider owns only package discovery and the EvoUI manager boundary.
 * Campaign delivery, persistence, and sTask orchestration are intentionally
 * deferred to later 2.x increments.
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
