<?php
namespace EvolutionCMS {
    class ServiceProvider {
        public array $calls = [];
        public function __construct(public $app) {}
        public function __call($name, $args) { $this->calls[] = [$name, $args]; }
    }
}
namespace {
    define('IN_MANAGER_MODE', ($argv[1] ?? '') === 'manager');
    require dirname(__DIR__) . '/src/sMailerServiceProvider.php';
    class ProviderProbe extends \Seiger\sMailer\sMailerServiceProvider {
        protected function cacheEmailAssets(): void { $this->calls[] = ['icons', []]; }
        protected function registerEvoUIComponents(): void { $this->calls[] = ['components', []]; }
    }
    $app = new class(($argv[1] ?? '') === 'console') {
        public function __construct(private bool $console) {}
        public function runningInConsole(): bool { return $this->console; }
    };
    $provider = new ProviderProbe($app);
    $provider->boot();
    $names = array_column($provider->calls, 0);
    foreach (['loadTranslationsFrom', 'loadViewsFrom', 'loadRoutesFrom', 'loadMigrationsFrom'] as $required) {
        if (!in_array($required, $names, true)) throw new \RuntimeException("Missing $required");
    }
    $hasUi = IN_MANAGER_MODE || $app->runningInConsole();
    foreach (['icons', 'components', 'mergeConfigFrom'] as $ui) {
        if (in_array($ui, $names, true) !== $hasUi) throw new \RuntimeException("Wrong context for $ui");
    }
    echo 'PASS ' . $argv[1] . ': routes retained; UI work correctly scoped.' . PHP_EOL;
}
