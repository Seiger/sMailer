<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$failures = [];
$tests = 0;

$read = static function (string $path) use ($root): string {
    $fullPath = $root . '/' . ltrim($path, '/');

    if (!is_file($fullPath)) {
        throw new RuntimeException("Missing file: {$path}");
    }

    return (string)file_get_contents($fullPath);
};

$assert = static function (bool $condition, string $message) use (&$failures, &$tests): void {
    $tests++;

    if (!$condition) {
        $failures[] = $message;
    }
};

$contains = static function (string $haystack, string $needle, string $message) use ($assert): void {
    $assert(str_contains($haystack, $needle), $message);
};

$notContains = static function (string $haystack, string $needle, string $message) use ($assert): void {
    $assert(!str_contains($haystack, $needle), $message);
};

$composer = json_decode($read('composer.json'), true);
$assert(is_array($composer), 'composer.json must contain valid JSON.');
$assert(($composer['name'] ?? null) === 'seiger/smailer', 'Composer package name must stay seiger/smailer.');
$assert(($composer['require']['php'] ?? null) === '>=8.4', 'sMailer 2.x must require PHP 8.4 or newer.');
$assert(($composer['require']['evolution-cms/evolution'] ?? null) === '^3.5.7', 'sMailer 2.x must require Evolution CMS 3.5.7.');
$assert(($composer['require']['evolution-cms/evo-ui'] ?? null) === '*', 'sMailer 2.x must require the available EvoUI runtime.');
$assert(($composer['require']['seiger/stask'] ?? null) === '*', 'sMailer 2.x must include the available sTask runtime.');
$assert(
    ($composer['extra']['laravel']['aliases']['sMailer'] ?? null) === 'Seiger\\sMailer\\Facades\\sMailer',
    'Composer facade alias must resolve to the package facade.'
);

$provider = $read('src/sMailerServiceProvider.php');
$contains($provider, 'use EvoUI\\EvoUI;', 'Provider must use the EvoUI boundary.');
$contains($provider, "registerComponent(\n            'smailer.module-panel'", 'Provider must register the package component through EvoUI.');
$contains($provider, "MODULE_ICON = 'tabler-mail-fast'", 'Manager module must use the approved Tabler mail-fast icon.');
$notContains($provider, 'Schedule', 'The 2.x foundation must not register the legacy scheduler.');
$notContains($provider, 'MailsSendCommand', 'The 2.x foundation must not register legacy delivery commands.');
$notContains($provider, 'Supervisor', 'The 2.x foundation must not add supervisor integration.');

$module = $read('module/sMailerModule.php');
$contains($module, 'app(ModuleController::class)->index()->render()', 'Evolution module entry must stay thin.');

$component = $read('src/Components/ModulePanel.php');
$contains($component, 'class ModulePanel extends Component', 'Manager panel must extend the EvoUI component boundary.');
$contains($component, '@since 2.0.0', 'New component API must declare its 2.x lifecycle.');

$facade = $read('src/Facades/sMailer.php');
$contains($facade, "return 'sMailer';", 'Package facade must resolve the registered service binding.');

$shell = $read('views/module/shell.blade.php');
$contains($shell, "EvoUI\\Support\\ManagerContext", 'Manager shell must use the EvoUI theme bridge.');
$contains($shell, "@include('evo::partials.assets')", 'Manager shell must load shared EvoUI assets.');
$contains($shell, '<livewire:smailer.module-panel', 'Manager shell must mount the sMailer component.');

$panel = $read('views/components/module-panel.blade.php');
$contains($panel, '<x-evo::module-tab-shell', 'Manager component must use the shared EvoUI tab shell.');
$contains($panel, '<x-evo::card', 'Foundation view must use a shared EvoUI card.');
$contains($panel, 'icon="mail-fast"', 'EvoUI foundation card must use the approved mail-fast icon.');

if ($failures !== []) {
    fwrite(STDERR, implode(PHP_EOL, $failures) . PHP_EOL);
    exit(1);
}

echo "sMailer foundation contract passed ({$tests} checks)." . PHP_EOL;
