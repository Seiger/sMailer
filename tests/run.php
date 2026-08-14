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
$contains($provider, "'smailer.mailings.table'", 'Provider must register the Mailings EvoUI table preset.');
$contains($provider, "MODULE_ICON = 'tabler-mail-fast'", 'Manager module must use the approved Tabler mail-fast icon.');
$notContains($provider, 'Schedule', 'The 2.x foundation must not register the legacy scheduler.');
$notContains($provider, 'MailsSendCommand', 'The 2.x foundation must not register legacy delivery commands.');
$notContains($provider, 'Supervisor', 'The 2.x foundation must not add supervisor integration.');

$module = $read('module/sMailerModule.php');
$contains($module, 'app(sMailerController::class)->index()->render()', 'Evolution module entry must stay thin.');

$component = $read('src/Components/ModulePanel.php');
$contains($component, 'class ModulePanel extends Component', 'Manager panel must extend the EvoUI component boundary.');
$contains($component, '@since 2.0.0', 'New component API must declare its 2.x lifecycle.');
$contains($component, 'public function saveDocument', 'Manager panel must own Builder document persistence.');
$contains($component, 'subscriberMetrics', 'Manager panel must expose subscriber overview metrics.');
$contains($component, 'loadSubscriberMetrics', 'Manager panel must load the package-owned subscriber metrics.');
$contains($component, 'Validator::make', 'Manager panel must validate Builder documents before saving.');
$contains($component, "'document.blocks' => ['present', 'array', 'max:200']", 'An empty Builder draft must remain saveable.');
$contains($component, 'Mailing::query()->findOrFail', 'Manager panel must resolve existing mailings through the package model.');
$contains($component, 'new Mailing()', 'Manager panel must create new mailing documents through the package model.');
$contains($component, "'code' => 'database'", 'Manager panel must surface a safe database persistence diagnostic.');
$contains($component, "'code' => 'validation'", 'Manager panel must surface a safe document validation diagnostic.');
$contains($component, 'public function queueOnceDelivery', 'Manager panel must queue campaign delivery through sTask.');
$contains($component, 'MailingDeliveryWorker', 'Manager panel must register the package-owned sTask delivery worker.');
$contains($component, 'MailingDeliveryQueue', 'Manager panel must use the package delivery queue service.');

$facade = $read('src/Facades/sMailer.php');
$contains($facade, "return 'sMailer';", 'Package facade must resolve the registered service binding.');

$shell = $read('views/module/shell.blade.php');
$contains($shell, "EvoUI\\Support\\ManagerContext", 'Manager shell must use the EvoUI theme bridge.');
$contains($shell, "@include('evo::partials.assets')", 'Manager shell must load shared EvoUI assets.');
$contains($shell, '<livewire:smailer.module-panel', 'Manager shell must mount the sMailer component.');

$panel = $read('views/components/module-panel.blade.php');
$contains($panel, 'data-smailer-module-panel', 'Manager component must keep one Livewire root element.');
$contains(
    $panel,
    '<x-evo::module-tab-shell :tabs="$tabs" model="activeTab"',
    'Manager component must use the shared EvoUI tab shell with active tab state.'
);
$contains($panel, '<x-evo::card', 'Foundation view must use a shared EvoUI card.');
$contains($panel, 'smailer:queue-mailing', 'Mailings table must be able to request a one-time delivery confirmation.');
$notContains($panel, 'name="mail-fast"', 'The package icon must not create a duplicate header above module tabs.');
$notContains($panel, 'class="evo-ui__header"', 'Module tabs must start without a package header.');

$controller = $read('src/Controllers/sMailerController.php');
$contains($controller, "'screen' => \$screen", 'Module controller must expose the requested manager screen.');
$contains($controller, "'collectionUrl' => \$collectionUrl", 'Module controller must preserve the collection route for the editor back action.');
$contains($controller, "request()->string('smailer_tab')->value()", 'Module controller must restore the requested collection tab.');
$contains($controller, "\$query['smailer_tab'] = 'mailings'", 'Editor back navigation must target the Mailings tab.');
$contains($controller, "'activeTab' => \$activeTab", 'Module controller must pass the requested active tab to the EvoUI panel.');
$expectedTabs = [
    "'key' => 'overview'",
    "'key' => 'mailings'",
    "'key' => 'subscribers'",
    "'key' => 'settings'",
];
$lastPosition = -1;

foreach ($expectedTabs as $tab) {
    $position = strpos($controller, $tab);
    $assert($position !== false, "Manager navigation must declare {$tab}.");
    $assert($position > $lastPosition, "Manager navigation must keep {$tab} in the approved order.");
    $lastPosition = $position;
}

foreach (['layout-dashboard', 'mail', 'users', 'settings'] as $icon) {
    $contains($controller, "'icon' => '{$icon}'", "Manager navigation must use the {$icon} Tabler icon.");
}

foreach (['overview', 'mailings', 'subscribers', 'settings'] as $tab) {
    $contains($panel, "activeTab === '{$tab}'", "Manager panel must render the {$tab} placeholder.");
}

$notContains($controller, "'key' => 'periodic'", 'Periodic campaigns must not remain a visible navigation section.');
$notContains($controller, "'key' => 'once'", 'One-off campaigns must not remain a visible navigation section.');
$notContains($panel, "activeTab === 'periodic'", 'The obsolete Periodic prototype must not remain visible.');
$notContains($panel, "activeTab === 'once'", 'The obsolete One-off prototype must not remain visible.');

$translationKeys = [
    'module_title',
    'module_navigation',
    'workspace_description',
    'overview',
    'mailings',
    'subscribers',
    'settings',
    'metric_unavailable',
    'overview_placeholder_description',
    'overview_subscribers',
    'overview_active',
    'overview_campaigns',
    'overview_scheduled_delivery',
    'overview_sent',
    'overview_failed',
    'recent_activity',
    'recent_activity_placeholder',
    'mailings_workspace_title',
    'mailings_workspace_description',
    'create_mailing',
    'create_mailing_unavailable',
    'search_mailings',
    'edit_mailing',
    'duplicate_mailing',
    'delete_mailing',
    'mailings_table_label',
    'mailing_name',
    'mailing_status',
    'mailing_delivery_mode',
    'mailing_next_delivery',
    'mailings_empty_title',
    'mailings_empty_description',
    'mailing_layout_preview',
    'mailing_layout_preview_description',
    'mailing_status_draft',
    'mailing_status_ready',
    'mailing_status_active',
    'mailing_status_paused',
    'delivery_manual',
    'delivery_once',
    'delivery_recurring',
    'delivery_unscheduled',
    'campaign_editor_destination',
    'campaign_editor_destination_description',
    'campaign_builder_workspace',
    'builder_palette',
    'builder_palette_description',
    'builder_canvas',
    'builder_canvas_description',
    'builder_inspector',
    'builder_inspector_description',
    'builder_visual_only',
    'builder_local_only',
    'builder_block_content',
    'builder_remove_block',
    'builder_edit_block',
    'builder_duplicate_block',
    'builder_move_block_up',
    'builder_move_block_down',
    'builder_block_settings',
    'builder_block_padding',
    'builder_block_alignment',
    'builder_alignment_left',
    'builder_alignment_center',
    'builder_alignment_right',
    'builder_alignment_justify',
    'builder_title_level',
    'builder_title_level_paragraph',
    'builder_title_size',
    'builder_title_text_color',
    'builder_title_background_color',
    'builder_toolbar',
    'builder_undo',
    'builder_redo',
    'builder_preview',
    'builder_preview_modes',
    'builder_preview_desktop',
    'builder_preview_mobile',
    'builder_test',
    'builder_save',
    'builder_publish',
    'builder_block_text',
    'builder_block_title',
    'builder_block_image',
    'builder_block_video',
    'builder_block_button',
    'builder_block_logo',
    'builder_block_divider',
    'builder_block_product',
    'builder_block_navigation',
    'builder_block_spacer',
    'builder_block_social',
    'builder_block_html',
    'builder_block_unsubscribe',
    'builder_unsubscribe_default_text',
    'builder_unsubscribe_text',
    'builder_image_select',
    'builder_image_no_file',
    'builder_image_remove',
    'builder_image_alt',
    'builder_image_link',
    'builder_image_width',
    'builder_image_shape',
    'builder_image_shape_square',
    'builder_image_shape_rounded',
    'builder_image_padding_top',
    'builder_image_padding_bottom',
    'builder_image_padding_left',
    'builder_image_padding_right',
    'builder_image_responsive',
    'mailing_untitled',
    'builder_save_success',
    'builder_save_failed',
    'edit_template',
    'campaign_details',
    'campaign_name',
    'campaign_save',
    'builder_blank_canvas_title',
    'builder_blank_canvas_description',
    'builder_no_selection_title',
    'builder_no_selection_description',
    'global_settings_group',
    'global_settings_placeholder',
    'subscriber_total',
    'subscriber_active',
    'subscriber_blocked',
    'subscriber_unsubscribed',
    'subscriber_directory_group',
    'subscribers_directory_placeholder',
    'search_subscribers',
    'subscriber_email',
    'subscriber_name',
    'subscriber_status',
    'subscriber_status_active',
    'subscriber_status_unsubscribed',
    'subscriber_status_blocked',
    'subscriber_subscribed_at',
];

foreach (['de', 'en', 'fr', 'pl', 'ru', 'uk'] as $locale) {
    $translations = require $root . "/lang/{$locale}/global.php";
    $assert(is_array($translations), "The {$locale} translation file must return an array.");
    $assert(
        ($translations['module_icon'] ?? null) === 'tabler-mail-fast',
        "The {$locale} translation file must keep the package module icon."
    );

    foreach ($translationKeys as $key) {
        $assert(
            isset($translations[$key]) && trim((string)$translations[$key]) !== '',
            "The {$locale} translation file must define {$key}."
        );
    }
}

$notContains($panel, '<form', 'Visual placeholders must not introduce forms.');
$notContains($panel, '<table', 'Visual placeholders must not introduce a subscriber table before data services exist.');
$notContains($panel, 'wire:submit', 'Visual placeholders must not introduce persistence actions.');
$notContains($panel, 'wire:model', 'Visual controls must not bind persistent state.');
$notContains($panel, 'wire:click', 'Visual actions must not invoke behavior.');
$notContains($panel, 'type="submit"', 'The campaign foundation must not expose a functional submit action.');
$notContains($panel, '<x-evo::card label="sMailer::global.overview"', 'Overview must not repeat its tab title inside the dashboard.');
$assert(
    substr_count($panel, 'evo-ui-dashboard-card--span-2') === 6,
    'Overview must expose exactly six compact EvoUI dashboard metric cards.'
);
$contains($panel, 'sMailer::global.recent_activity', 'Overview must expose the recent activity placeholder section.');
$contains($panel, '<strong>&mdash;</strong>', 'Dashboard metrics must expose honest empty values.');
$contains($panel, "\$subscriberMetrics['total']", 'Overview must show the total number of subscribers.');
$contains($panel, "\$subscriberMetrics['active']", 'Overview must show the number of active subscribers.');
$notContains(
    $panel,
    'sMailer::global.overview_placeholder_description',
    'Overview must not fall back to the generic single-sentence placeholder.'
);

$contains($panel, 'data-smailer-mailings-workspace', 'Mailings must own the campaign-list workspace.');
$contains($panel, '<livewire:evo-ui.module-table', 'Mailings must use the native EvoUI module table.');
$contains($panel, 'preset="smailer.mailings"', 'Mailings must mount its package-owned EvoUI table preset.');
$contains($panel, "'tab' => 'mailings'", 'Mailings table must subscribe to the shared active-tab refresh event.');
$contains($panel, "'collectionUrl' => \$context['collectionUrl'] ?? ''", 'Mailings table must receive the collection route for editor navigation.');
$contains($panel, 'smailer:preview-mailing', 'Mailings must receive table preview requests through the module boundary.');
$contains($panel, 'previewStoredDocument', 'Mailings quick preview must render the stored campaign document.');
$contains($panel, 'smailer-mailing-preview-frame', 'Mailings quick preview must isolate final email HTML in an iframe.');
$notContains($panel, 'data-smailer-builder-shell', 'Mailings must remain a campaign collection without an inline Builder.');
$notContains($panel, 'builder_palette', 'The Builder must open only from Create or Edit campaign actions.');
$notContains($panel, 'builder_canvas', 'The Builder must open only from Create or Edit campaign actions.');
$notContains($panel, 'builder_inspector', 'The Builder must open only from Create or Edit campaign actions.');
$notContains($panel, 'class="evo-ui-view-toggle"', 'Mailings must not render Builder preview controls inline.');
$contains($panel, "@include('sMailer::components.campaign-editor'", 'Editor screen must be rendered separately from the Mailings collection.');
$notContains($panel, "@include('sMailer::components.campaign-meta'", 'Campaign metadata must use the native Mailings modal, not a separate screen.');
$contains($panel, 'preset="smailer.subscribers"', 'Subscribers must use the native EvoUI module table.');
$contains($panel, "'tab' => 'subscribers'", 'Subscribers table must subscribe to the shared active-tab refresh event.');

$editor = $read('views/components/campaign-editor.blade.php');
$contains($editor, 'data-smailer-builder-screen', 'Campaign editor must have an isolated Builder screen root.');
$contains($editor, 'builder_palette', 'Campaign editor must expose its block palette.');
$contains($editor, 'builder_canvas', 'Campaign editor must expose its email canvas.');
$notContains($editor, 'smailer-builder__inspector', 'Campaign editor must not render a permanent inspector panel.');
$contains($editor, 'class="evo-ui-view-toggle"', 'Campaign editor must use the EvoUI Desktop/Mobile preview toggle.');
$contains($editor, '$collectionUrl', 'Campaign editor must provide a return route to Mailings.');
$contains($editor, 'x-data=', 'Campaign editor must own an in-browser document state.');
$contains($editor, 'addBlock(', 'Campaign editor palette must add blocks to its local document.');
$contains($editor, 'selectedBlock', 'Campaign editor must select a local document block.');
$contains($editor, 'x-model="block.content"', 'Campaign editor must edit text blocks locally.');
$notContains($editor, 'smailer-builder__block-type', 'Canvas blocks must not display internal block names in the email content.');
$contains($editor, 'this.$refs.titleInput?.focus()', 'Selecting a Title block must immediately focus its text field.');
$contains($editor, 'titleLevel', 'Title blocks must store their selected semantic text level.');
$contains($editor, "block.align = 'justify'", 'Title blocks must support justified text alignment.');
$contains($editor, 'recordHistory(', 'Builder changes must be captured in document history.');
$contains($editor, 'undo()', 'Builder toolbar must support undo.');
$contains($editor, 'redo()', 'Builder toolbar must support redo.');
$contains($editor, 'removeSelected()', 'Campaign editor contextual menu must remove the selected local block.');
$contains($editor, 'duplicateSelected()', 'Campaign editor contextual menu must duplicate a local block.');
$contains($editor, 'moveSelected(', 'Campaign editor contextual menu must reorder a local block.');
$contains($editor, 'toggleSettings()', 'Campaign editor contextual menu must open local block settings.');
$contains($editor, 'x-model.number="block.padding"', 'Campaign editor contextual settings must edit local block padding.');
$contains($editor, "block.align = 'center'", 'Campaign editor contextual settings must update local block alignment.');
$contains($editor, "previewMode: 'desktop'", 'Campaign editor must keep one desktop-first preview state.');
$contains($editor, "previewMode = 'mobile'", 'Campaign editor must switch the same document to mobile preview.');
$contains($editor, 'width: min(600px, 100%)', 'Campaign editor desktop canvas must use the safe 600px email width.');
$contains($editor, 'width: min(375px, 100%)', 'Campaign editor mobile preview must use a narrow viewport.');
$contains($editor, 'window.EvoUI?.browseImageField', 'Image blocks must use the shared EvoUI Manager image library bridge.');
$contains($editor, 'data-evo-media-bridge', 'Image blocks must use the EvoUI media bridge marker.');
$contains($editor, 'assignImageFromLibrary', 'Image blocks must accept the selected Manager library URL locally.');
$contains($editor, 'block.imageWidth', 'Image blocks must expose local width controls.');
$contains($editor, 'block.imageWidthUnit', 'Image blocks must support percentage and pixel width units.');
$contains($editor, 'setImageWidthUnit', 'Image blocks must clamp the selected width unit safely.');
$contains($editor, 'value="px"', 'Image blocks must offer pixel sizing.');
$contains($editor, 'RichTextEditor::html', 'Text blocks must load the configured system rich-text editor through EvoUI.');
$contains($editor, 'EvoUI?.initRichEditorField', 'Text blocks must use the shared EvoUI editor lifecycle.');
$contains($editor, 'syncActiveTextEditor()', 'Text blocks must sync editor HTML into the Builder JSON before saving.');
$contains($editor, 'tinymce?.get', 'Text blocks must read the active system TinyMCE content when available.');
$contains($editor, "'Codemirror'", 'HTML blocks must load the system CodeMirror editor.');
$contains($editor, 'initHtmlEditor(', 'HTML blocks must initialize CodeMirror only for the selected block.');
$contains($editor, 'syncActiveHtmlEditor()', 'HTML blocks must sync CodeMirror content before saving.');
$contains($editor, 'saveTemplate()', 'Campaign editor must expose JSON document persistence.');
$contains($editor, '$wire.saveDocument(', 'Campaign editor must save through its native EvoUI module component.');
$contains($editor, '{ version: 1, blocks: this.blocks }', 'Campaign editor must save a versioned JSON document.');
$contains($editor, 'payload?.ok', 'Campaign editor must handle the native save result.');
$contains($editor, 'payload?.code', 'Campaign editor must show only a safe save diagnostic code.');
$notContains($editor, 'fetch(', 'Campaign editor must not use a custom HTTP persistence route.');
$notContains($editor, 'X-CSRF-TOKEN', 'Campaign editor must rely on the manager component transport rather than a custom CSRF request.');
$notContains($editor, 'csrfToken', 'Campaign editor must not carry a custom route CSRF token.');
$contains($editor, 'block.imageLink', 'Image blocks must expose local link controls.');
$contains($editor, 'block.imageAlt', 'Image blocks must expose local alternative text controls.');
$contains($editor, 'block.paddingTop', 'Image blocks must expose individual padding controls.');
$notContains($editor, 'wire:model', 'Builder JSON persistence must not use a Livewire document binding.');
$notContains($editor, 'wire:click', 'Builder controls must invoke the module boundary through Alpine rather than template wire bindings.');
$notContains($editor, 'Storage::', 'Builder JSON persistence must not write template files.');
$contains($panel, 'global_settings_placeholder', 'Settings must contain only the global package placeholder.');
$notContains($panel, 'footer_content_group', 'Global Settings must not own campaign footer content.');
$notContains($panel, 'social_links_group', 'Global Settings must not own campaign social content.');
$notContains($panel, 'legal_information_group', 'Global Settings must not own campaign legal content.');
$notContains($panel, 'unsubscribe_content_group', 'Global Settings must not own campaign unsubscribe content.');
$notContains($panel, 'data-smailer-periodic', 'The obsolete Periodic visual prototype must be retired.');
$notContains($panel, 'file_put_contents', 'The visual foundation must not write template files.');
$notContains($panel, 'Storage::', 'The visual foundation must not introduce storage behavior.');
$notContains($panel, 'Blade::render', 'The visual foundation must not execute arbitrary template source.');

$mailingsConfig = $read('config/mailings/table.php');
$contains($mailingsConfig, "'key' => 'smailer.mailings'", 'Mailings table must declare its EvoUI preset key.');
$contains($mailingsConfig, "'provider' => \\Seiger\\sMailer\\Tables\\MailingsTableData::class", 'Mailings table must use its package provider.');
$contains($mailingsConfig, "'views' => ['table', 'list']", 'Mailings must support native EvoUI table/list view state.');
$contains($mailingsConfig, "'type' => 'multi-select'", 'Mailings must use native EvoUI multi-select filters.');
$contains($mailingsConfig, "'type' => 'chips'", 'Mailings status and delivery cells must use native EvoUI chips.');
$contains($mailingsConfig, "'type' => 'placeholder'", 'Mailings CRUD affordances must stay non-persistent placeholders.');
$contains($mailingsConfig, "'type' => 'link'", 'Mailings Create and template actions must navigate to the separate editor screen.');
$contains($mailingsConfig, "'href_provider' => 'editorUrl'", 'Mailings toolbar actions must resolve the editor route through the provider.');
$contains($mailingsConfig, "'method' => 'openEditModal'", 'Mailings campaign edit actions must open the native EvoUI metadata modal.');
$contains($mailingsConfig, "'href' => 'editor_url'", 'Mailings row template action must resolve the Builder route.');
$contains($mailingsConfig, "'key' => 'preview'", 'Mailings row actions must expose a quick preview action.');
$contains($mailingsConfig, "'icon' => 'eye'", 'Mailings quick preview must use the shared eye icon.');
$contains($mailingsConfig, "'x-on:click.prevent' => 'preview_action'", 'Mailings quick preview must dispatch its stored-document request from the selected row.');
$contains($mailingsConfig, "'modal' => [", 'Mailings must declare its native EvoUI campaign metadata modal.');
$contains($mailingsConfig, "'row_dblclick' => false", 'Mailings must not open campaign metadata from an accidental row double click.');

$toolbarActions = [
    "'key' => 'create'",
    "'key' => 'edit'",
    "'key' => 'template'",
    "'key' => 'duplicate'",
    "'key' => 'delete'",
];
$lastToolbarPosition = -1;
foreach ($toolbarActions as $action) {
    $position = strpos($mailingsConfig, $action);
    $assert($position !== false, "Mailings toolbar must declare {$action}.");
    $assert($position > $lastToolbarPosition, "Mailings toolbar must keep {$action} in the approved order.");
    $lastToolbarPosition = $position;
}
$contains($mailingsConfig, "'tone' => 'success'", 'Mailings Add action must use the green EvoUI tone.');
$assert(
    substr_count($mailingsConfig, "'icon_only' => true") === 5,
    'Mailings collection toolbar must expose Create, campaign edit, template edit, duplicate, and delete actions.'
);
$assert(
    substr_count($mailingsConfig, "'selection' => 'single'") === 4,
    'Campaign edit, template edit, duplicate, and delete must require a selected mailing.'
);

$mailingsData = $read('src/Tables/MailingsTableData.php');
$contains($mailingsData, 'implements ModuleTableProvider', 'Mailings provider must use the EvoUI table provider contract.');
$contains($mailingsData, 'public function total(): int', 'Mailings provider must expose total().');
$contains($mailingsData, 'public function rows(int $page, int $perPage): array', 'Mailings provider must expose rows().');
$contains($mailingsData, 'public function filterGroups(): array', 'Mailings provider must expose native filter groups.');
$contains($mailingsData, 'Mailing::query()', 'Mailings provider must list persisted campaign documents.');
$contains($mailingsData, 'public function modalData', 'Mailings provider must provide data for the native metadata modal.');
$contains($mailingsData, 'public function saveModal', 'Mailings provider must save metadata through the native EvoUI modal contract.');
$contains($mailingsData, 'public function editorUrl', 'Mailings provider must expose the separate campaign editor route.');
$contains($mailingsData, 'preview_action', 'Mailings provider must provide a row-scoped quick preview dispatch action.');
$contains($mailingsData, "return \$this->screenUrl('editor'", 'Mailings template route must use the editor screen contract.');
$contains($mailingsData, 'use Seiger\\sMailer\\Models\\Mailing;', 'Mailings provider must use the package mailing model.');
$contains($component, 'public function previewStoredDocument', 'Manager panel must render saved documents for the Mailings quick preview.');
$notContains($provider, 'loadRoutesFrom', 'sMailer must not register a custom HTTP route layer for normal manager persistence.');
$assert(!is_file($root . '/src/Controllers/MailingController.php'), 'sMailer must not retain the removed custom mailing controller.');
$assert(!is_file($root . '/src/Http/routes.php'), 'sMailer must not retain the removed custom mailing routes.');
$notContains($mailingsData, 'Storage::', 'Mailings visual provider must not write files.');

$subscribersConfig = $read('config/subscribers/table.php');
$contains($subscribersConfig, "'key' => 'smailer.subscribers'", 'Subscribers table must declare its EvoUI preset key.');
$contains($subscribersConfig, "'provider' => \\Seiger\\sMailer\\Tables\\SubscribersTableData::class", 'Subscribers table must use its package provider.');
$contains($subscribersConfig, "'search_subscribers'", 'Subscribers table must expose native search.');
$contains($subscribersConfig, "'subscriber_status'", 'Subscribers table must expose status filtering.');

$subscribersData = $read('src/Tables/SubscribersTableData.php');
$contains($subscribersData, 'implements ModuleTableProvider', 'Subscribers provider must use the EvoUI table provider contract.');
$contains($subscribersData, 'public function saveModal', 'Subscribers provider must fulfil the native EvoUI table contract.');
$contains($subscribersData, 'Subscriber::query()', 'Subscribers provider must read the package subscriber model.');
$contains($subscribersData, "'active'", 'Subscribers provider must expose active recipients.');
$contains($subscribersData, "'unsubscribed'", 'Subscribers provider must expose unsubscribed recipients.');
$contains($subscribersData, "'blocked'", 'Subscribers provider must expose blocked recipients.');

$subscriberMigration = $read('database/migrations/2026_08_02_000000_create_mailings_table.php');
$contains($subscriberMigration, "Schema::create('s_subscribers'", 'Initial package migration must create the subscriber table.');

$mailingsConfig = $read('config/mailings/table.php');
$contains($mailingsConfig, "'key' => 'send_once'", 'Mailings table must expose a one-time delivery action.');
$contains($mailingsConfig, "'name' => 'scheduled_at'", 'Mailings table must expose the scheduled delivery time for one-time campaigns.');
$contains($mailingsConfig, "'name' => 'recurrence_frequency'", 'Mailings table must expose recurring delivery frequency.');
$contains($mailingsConfig, "'name' => 'recurrence_time'", 'Mailings table must expose recurring delivery time.');
$contains($mailingsConfig, "'value' => 'daily'", 'Recurring delivery must support daily frequency.');
$contains($mailingsConfig, "'value' => 'weekly'", 'Recurring delivery must support weekly frequency.');
$contains($mailingsConfig, "'value' => 'monthly'", 'Recurring delivery must support monthly frequency.');
$deliveryWorker = $read('src/Workers/MailingDeliveryWorker.php');
$contains($deliveryWorker, 'extends BaseWorker', 'Campaign delivery must use the native sTask worker contract.');
$contains($deliveryWorker, 'taskSendOnce', 'Campaign delivery worker must implement the one-time action.');
$contains($deliveryWorker, "where('status', 'active')", 'Campaign delivery worker must resolve active subscribers at execution time.');
$deliveryQueue = $read('src/Services/MailingDeliveryQueue.php');
$contains($deliveryQueue, "'start_at' => \$startAt", 'Scheduled delivery must use the native sTask start time.');
$contains($deliveryQueue, 'enqueueRecurring', 'Recurring delivery must create the next native sTask task.');
$contains($deliveryQueue, 'nextRecurringAt', 'Recurring delivery must calculate a campaign-specific next run time.');
$contains($deliveryWorker, 'scheduleNextRecurring', 'Recurring delivery must enqueue the following cycle after completion.');
$contains($subscriberMigration, "\$table->string('domain')->default('default')", 'Subscribers must default to the default domain.');
$contains($subscriberMigration, "\$table->string('lang')->default('base')", 'Subscribers must default to the base language.');
$contains($subscriberMigration, "\$table->unique(['domain', 'email'])", 'Subscriber email addresses must be unique within a domain.');
$contains($subscriberMigration, "\$table->string('status')->default('active')", 'Subscribers must default to the active lifecycle state.');

$ruTranslations = require $root . '/lang/ru/global.php';
$ukTranslations = require $root . '/lang/uk/global.php';
$assert($ruTranslations === $ukTranslations, 'Russian locale must fall back directly to Ukrainian translations.');

if ($failures !== []) {
    fwrite(STDERR, implode(PHP_EOL, $failures) . PHP_EOL);
    exit(1);
}

echo "sMailer foundation contract passed ({$tests} checks)." . PHP_EOL;
