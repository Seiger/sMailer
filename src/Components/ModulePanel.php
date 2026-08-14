<?php namespace Seiger\sMailer\Components;

use EvoUI\Components\Component;
use Illuminate\Support\Facades\Validator;
use Seiger\sMailer\Models\Mailing;
use Seiger\sMailer\Models\Subscriber;
use Seiger\sMailer\Services\MailingDeliveryQueue;
use Seiger\sMailer\Services\DomainContext;
use Seiger\sMailer\Services\LanguageContext;
use Seiger\sMailer\Services\MailingDocumentRenderer;

/**
 * Own the interactive sMailer manager foundation.
 *
 * Business tabs will be added here only when their application services and
 * persistence contracts are ready, keeping the initial 2.x surface truthful.
 *
 * @since 2.0.0
 */
class ModulePanel extends Component
{
    /** @var list<array<string, mixed>> */
    public array $rawTabs = [];

    /** @var array<string, mixed> */
    public array $context = [];

    public string $activeTab = 'overview';

    public string $screen = 'collection';

    /** @var array{total: int, active: int, blocked: int, unsubscribed: int} */
    public array $subscriberMetrics = [
        'total' => 0,
        'active' => 0,
        'blocked' => 0,
        'unsubscribed' => 0,
    ];

    /**
     * Initialize the manager panel with server-defined navigation.
     *
     * @param list<array<string, mixed>> $tabs
     * @param string $activeTab
     * @param array<string, mixed> $context
     * @return void
     * @since 2.0.0
     */
    public function mount(array $tabs = [], string $activeTab = 'overview', array $context = []): void
    {
        $this->rawTabs = $tabs;
        $this->context = $context;
        $this->screen = ($context['screen'] ?? null) === 'editor' ? 'editor' : 'collection';
        $this->activeTab = $this->normalizeTab($activeTab);
        $this->loadSubscriberMetrics();
    }

    /**
     * Switch to an allowed manager tab.
     *
     * @param string $tab
     * @return void
     * @since 2.0.0
     */
    public function switchTab(string $tab): void
    {
        $this->activeTab = $this->normalizeTab($tab);

        if ($this->activeTab === 'overview') {
            $this->loadSubscriberMetrics();
        }
    }

    protected function loadSubscriberMetrics(): void
    {
        $counts = Subscriber::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $this->subscriberMetrics = [
            'total' => (int) $counts->sum(),
            'active' => (int) ($counts['active'] ?? 0),
            'blocked' => (int) ($counts['blocked'] ?? 0),
            'unsubscribed' => (int) ($counts['unsubscribed'] ?? 0),
        ];
    }

    /**
     * Persist the Builder document through the native EvoUI/Livewire module boundary.
     *
     * @param array<string, mixed> $document
     * @return array{id: int|null, name: string|null, ok: bool, code?: string}
     */
    public function saveDocument(array $document, ?int $mailingId = null, string $name = ''): array
    {
        try {
            Validator::make([
                'name' => $name,
                'document' => $document,
            ], [
                'name' => ['nullable', 'string', 'max:255'],
                'document' => ['required', 'array'],
                'document.version' => ['required', 'integer', 'in:1'],
                'document.blocks' => ['present', 'array', 'max:200'],
                'document.blocks.*.id' => ['required', 'string', 'max:100'],
                'document.blocks.*.type' => ['required', 'string', 'max:50'],
            ])->validate();

            $mailing = $mailingId ? Mailing::query()->findOrFail($mailingId) : new Mailing();
            $mailing->fill([
                'name' => trim($name) ?: __('sMailer::global.mailing_untitled'),
                'domain' => $mailing->exists ? $mailing->domain : app(DomainContext::class)->current(),
                'lang' => $mailing->exists ? $mailing->lang : app(LanguageContext::class)->current(),
                'status' => 'draft',
                'document' => $document,
            ])->save();

            $this->context['mailing'] = [
                'id' => $mailing->id,
                'name' => $mailing->name,
                'document' => $mailing->document,
            ];

            return ['id' => $mailing->id, 'name' => $mailing->name, 'ok' => true];
        } catch (\Illuminate\Validation\ValidationException) {
            return ['id' => null, 'name' => null, 'ok' => false, 'code' => 'validation'];
        } catch (\Illuminate\Database\QueryException) {
            return ['id' => null, 'name' => null, 'ok' => false, 'code' => 'database'];
        } catch (\Throwable) {
            return ['id' => null, 'name' => null, 'ok' => false, 'code' => 'unexpected'];
        }
    }

    /**
     * Render the Builder JSON into standalone email HTML for manager preview.
     *
     * Product results remain transient: selection settings stay in the document,
     * while current catalogue data is fetched only for this preview response.
     *
     * @param array<string, mixed> $document
     * @return array{ok: bool, html?: string, code?: string}
     */
    public function renderPreview(array $document): array
    {
        try {
            Validator::make(['document' => $document], [
                'document' => ['required', 'array'],
                'document.version' => ['required', 'integer', 'in:1'],
                'document.blocks' => ['present', 'array', 'max:200'],
                'document.blocks.*.id' => ['required', 'string', 'max:100'],
                'document.blocks.*.type' => ['required', 'string', 'max:50'],
            ])->validate();

            $products = [];
            $this->collectPreviewProducts($document['blocks'], $products);

            return [
                'ok' => true,
                'html' => app(MailingDocumentRenderer::class)->render($document, ['products' => $products]),
            ];
        } catch (\Illuminate\Validation\ValidationException) {
            return ['ok' => false, 'code' => 'validation'];
        } catch (\Throwable) {
            return ['ok' => false, 'code' => 'unavailable'];
        }
    }

    /**
     * Render the persisted Builder document for a quick campaign-list preview.
     *
     * The table never receives or reconstructs the document itself: it only
     * passes the mailing ID, while this method reads the saved JSON source of
     * truth and delegates to the same final email renderer as the Builder.
     *
     * @return array{ok: bool, html?: string, code?: string}
     */
    public function previewStoredDocument(int $mailingId): array
    {
        try {
            $document = Mailing::query()->findOrFail($mailingId)->document;

            return $this->renderPreview(is_array($document) ? $document : []);
        } catch (\Throwable) {
            return ['ok' => false, 'code' => 'unavailable'];
        }
    }

    /** @return array{ok: bool, mailingId?: int, name?: string, recipients?: int, code?: string} */
    public function prepareOnceDelivery(int $mailingId): array
    {
        $mailing = Mailing::query()->find($mailingId);
        if (!$mailing || !is_array($mailing->document)) {
            return ['ok' => false, 'code' => 'unavailable'];
        }

        return [
            'ok' => true,
            'mailingId' => (int) $mailing->id,
            'name' => (string) $mailing->name,
            'recipients' => Subscriber::query()
                ->where('domain', $mailing->domain ?: 'default')
                ->where('lang', $mailing->lang ?: 'base')
                ->where('status', 'active')
                ->count(),
        ];
    }

    /** @return array{ok: bool, taskId?: int, recipients?: int, code?: string} */
    public function queueOnceDelivery(int $mailingId): array
    {
        try {
            $prepared = $this->prepareOnceDelivery($mailingId);
            if (!($prepared['ok'] ?? false)) {
                return $prepared;
            }

            $task = app(MailingDeliveryQueue::class)->enqueue($mailingId);

            return [
                'ok' => true,
                'taskId' => (int) $task->id,
                'recipients' => (int) $prepared['recipients'],
            ];
        } catch (\Throwable) {
            return ['ok' => false, 'code' => 'unavailable'];
        }
    }

    /** @return array{ok: bool, created?: int, updated?: int, skipped?: int, code?: string} */
    public function importSubscribers(string $csv): array
    {
        if (mb_strlen($csv) > 5_000_000) {
            return ['ok' => false, 'code' => 'file_too_large'];
        }

        $handle = fopen('php://temp', 'r+');
        fwrite($handle, preg_replace('/^\xEF\xBB\xBF/', '', $csv) ?? $csv);
        rewind($handle);
        $firstLine = (string) strtok($csv, "\r\n");
        $delimiter = substr_count($firstLine, ';') > substr_count($firstLine, ',') ? ';' : ',';
        $header = fgetcsv($handle, 0, $delimiter);
        $header = is_array($header) ? array_map(fn ($value) => strtolower(trim((string) $value)), $header) : [];
        $emailIndex = array_search('email', $header, true);

        if ($emailIndex === false) {
            return ['ok' => false, 'code' => 'invalid_header'];
        }

        $nameIndex = array_search('name', $header, true);
        $statusIndex = array_search('status', $header, true);
        $domainIndex = array_search('domain', $header, true);
        $langIndex = array_search('lang', $header, true);
        $created = $updated = $skipped = 0;

        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            $email = strtolower(trim((string) ($row[$emailIndex] ?? '')));
            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $skipped++;
                continue;
            }

            $domain = $domainIndex === false
                ? app(DomainContext::class)->current()
                : trim((string) ($row[$domainIndex] ?? ''));
            $domain = $domain ?: 'default';
            $subscriber = Subscriber::query()->firstOrNew(['domain' => $domain, 'email' => $email]);
            $lang = $langIndex === false
                ? app(LanguageContext::class)->current()
                : trim((string) ($row[$langIndex] ?? ''));
            $subscriber->lang = $lang ?: 'base';
            $isNew = !$subscriber->exists;
            $status = $statusIndex === false ? null : strtolower(trim((string) ($row[$statusIndex] ?? '')));
            if ($status !== null && $status !== '' && !in_array($status, ['active', 'unsubscribed', 'blocked'], true)) {
                $skipped++;
                continue;
            }

            if ($nameIndex !== false) {
                $subscriber->name = trim((string) ($row[$nameIndex] ?? '')) ?: null;
            }
            if ($status !== null && $status !== '') {
                $subscriber->status = $status;
            }
            if ($isNew) {
                $subscriber->status ??= 'active';
                $subscriber->subscribed_at ??= now();
            }
            if ($subscriber->status === 'unsubscribed') {
                $subscriber->unsubscribed_at ??= now();
            }
            if ($subscriber->status === 'blocked') {
                $subscriber->blocked_at ??= now();
            }
            if ($subscriber->isDirty() || $isNew) {
                $subscriber->save();
                $isNew ? $created++ : $updated++;
            }
        }

        return ['ok' => true, 'created' => $created, 'updated' => $updated, 'skipped' => $skipped];
    }

    /** @return array{ok: bool, csv: string} */
    public function exportSubscribers(): array
    {
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, ['domain', 'lang', 'email', 'name', 'status', 'subscribed_at']);
        Subscriber::query()->orderBy('email')->each(function (Subscriber $subscriber) use ($handle): void {
            fputcsv($handle, [$subscriber->domain, $subscriber->lang, $subscriber->email, $subscriber->name, $subscriber->status, $subscriber->subscribed_at?->toAtomString()]);
        });
        rewind($handle);

        return ['ok' => true, 'csv' => (string) stream_get_contents($handle)];
    }

    /**
     * Render and send one explicitly requested test message.
     *
     * This never adds a recipient to a campaign queue: it is solely the
     * manager's immediate confirmation that the current Builder JSON renders
     * and that the configured Evolution mail transport can deliver it.
     *
     * @param array<string, mixed> $document
     * @return array{ok: bool, code?: string}
     */
    public function sendTest(array $document, string $email, string $name = ''): array
    {
        try {
            Validator::make([
                'email' => $email,
                'document' => $document,
            ], [
                'email' => ['required', 'email:rfc', 'max:255'],
                'document' => ['required', 'array'],
                'document.version' => ['required', 'integer', 'in:1'],
                'document.blocks' => ['present', 'array', 'max:200'],
                'document.blocks.*.id' => ['required', 'string', 'max:100'],
                'document.blocks.*.type' => ['required', 'string', 'max:50'],
            ])->validate();

            $products = [];
            $this->collectPreviewProducts($document['blocks'], $products);
            $subject = trim($name) ?: __('sMailer::global.mailing_untitled');
            $sent = evo()->sendmail([
                'type' => 'html',
                'from' => evo()->getConfig('site_name') . '<' . evo()->getConfig('emailsender') . '>',
                'to' => trim($email),
                'subject' => 'Test: ' . $subject,
                'body' => app(MailingDocumentRenderer::class)->render($document, ['products' => $products]),
            ]);

            return $sent ? ['ok' => true] : ['ok' => false, 'code' => 'transport'];
        } catch (\Illuminate\Validation\ValidationException) {
            return ['ok' => false, 'code' => 'validation'];
        } catch (\Throwable) {
            return ['ok' => false, 'code' => 'transport'];
        }
    }

    /**
     * Return a transient sCommerce product preview for the Builder.
     *
     * The mailing document owns only the selection configuration. The resulting
     * products are returned to the current browser session and are never saved.
     *
     * @param array<string, mixed> $selection
     * @return array{ok: bool, products: list<array{id: int, title: string, url: string, image: string, price: string}>, code?: string}
     */
    public function previewProducts(array $selection): array
    {
        $productClass = 'Seiger\\sCommerce\\Models\\sProduct';

        if (!class_exists($productClass)) {
            return ['ok' => false, 'products' => [], 'code' => 'commerce_unavailable'];
        }

        try {
            $validated = Validator::make($selection, [
                'limit' => ['required', 'integer', 'min:1', 'max:12'],
                'selectionMode' => ['required', 'in:ids,filters'],
                'ids' => ['nullable', 'required_if:selectionMode,ids', 'string', 'max:500'],
                'filter' => ['required', 'in:all,category,attribute'],
                'categoryId' => ['nullable', 'integer', 'min:1'],
                'attributeAlias' => ['nullable', 'string', 'max:100'],
                'attributeValue' => ['nullable', 'string', 'max:255'],
                'availability' => ['required', 'in:available,in_stock,in_stock_or_order'],
                'sort' => ['required', 'in:newest,oldest,title_asc,title_desc,price_asc,price_desc'],
            ])->validate();

            $query = $productClass::query()->active();

            $ids = collect(explode(',', (string)($validated['ids'] ?? '')))
                ->map(fn ($id) => (int) trim($id))
                ->filter()
                ->unique()
                ->values();

            if ($validated['selectionMode'] === 'ids') {
                if ($ids->isEmpty()) {
                    return ['ok' => false, 'products' => [], 'code' => 'validation'];
                }

                $query->whereIn('s_products.id', $ids->all());
            } elseif ($validated['filter'] === 'category') {
                if (empty($validated['categoryId'])) {
                    return ['ok' => false, 'products' => [], 'code' => 'validation'];
                }

                $commerceClass = 'Seiger\\sCommerce\\Facades\\sCommerce';

                if (!class_exists($commerceClass)) {
                    return ['ok' => false, 'products' => [], 'code' => 'commerce_unavailable'];
                }

                $categoryProducts = $commerceClass::getCategoryProducts(
                    10000,
                    (int) $validated['categoryId'],
                    null,
                    50,
                )->getCollection();

                $products = $categoryProducts
                    ->filter(function ($product) use ($validated, $productClass): bool {
                        return match ($validated['availability']) {
                            'in_stock' => (int) $product->availability === $productClass::AVAILABILITY_IN_STOCK,
                            'in_stock_or_order' => in_array((int) $product->availability, [
                                $productClass::AVAILABILITY_IN_STOCK,
                                $productClass::AVAILABILITY_ON_ORDER,
                            ], true),
                            default => true,
                        };
                    })
                    ->map(fn ($product) => [
                        'id' => (int) $product->id,
                        'title' => (string) $product->title,
                        'url' => (string) $product->link,
                        'image' => (string) $product->coverSrc,
                        'price' => (string) $product->price,
                        'sortPrice' => (float) $product->priceAsFloat,
                    ]);

                $products = match ($validated['sort']) {
                    'oldest' => $products->sortBy('id'),
                    'title_asc' => $products->sortBy(fn (array $product) => mb_strtolower($product['title'])),
                    'title_desc' => $products->sortByDesc(fn (array $product) => mb_strtolower($product['title'])),
                    'price_asc' => $products->sortBy('sortPrice'),
                    'price_desc' => $products->sortByDesc('sortPrice'),
                    default => $products->sortByDesc('id'),
                };

                return ['ok' => true, 'products' => $products
                    ->take($validated['limit'])
                    ->values()
                    ->map(fn (array $product) => collect($product)->except('sortPrice')->all())
                    ->all()];
            }

            if ($validated['selectionMode'] === 'filters' && $validated['filter'] === 'attribute') {
                if (empty($validated['attributeAlias'])) {
                    return ['ok' => false, 'products' => [], 'code' => 'validation'];
                }

                $query->whereHas('attrValues', function ($attributes) use ($validated): void {
                    $attributes->where('s_attributes.alias', $validated['attributeAlias']);

                    if (($validated['attributeValue'] ?? '') !== '') {
                        $attributes->where('s_product_attribute_values.value', $validated['attributeValue']);
                    }
                });
            }

            if ($validated['availability'] === 'in_stock') {
                $query->where('s_products.availability', $productClass::AVAILABILITY_IN_STOCK);
            }

            if ($validated['availability'] === 'in_stock_or_order') {
                $query->whereIn('s_products.availability', [
                    $productClass::AVAILABILITY_IN_STOCK,
                    $productClass::AVAILABILITY_ON_ORDER,
                ]);
            }

            $isPriceSort = $validated['selectionMode'] === 'filters'
                && in_array($validated['sort'], ['price_asc', 'price_desc'], true);

            if ($validated['selectionMode'] === 'filters' && !$isPriceSort) {
                match ($validated['sort']) {
                    'oldest' => $query->orderBy('s_products.id'),
                    'title_asc' => $query->orderBy('spt.pagetitle'),
                    'title_desc' => $query->orderByDesc('spt.pagetitle'),
                    default => $query->orderByDesc('s_products.id'),
                };
            }

            $products = $query
                ->when(!$isPriceSort && $validated['selectionMode'] !== 'ids', fn ($products) => $products->limit($validated['limit']))
                ->get()
                ->map(fn ($product) => [
                    'id' => (int) $product->id,
                    'title' => (string) $product->title,
                    'url' => (string) $product->link,
                    'image' => (string) $product->coverSrc,
                    'price' => (string) $product->price,
                    'sortPrice' => (float) $product->priceAsFloat,
                ])
                ->values();

            if ($validated['selectionMode'] === 'ids') {
                $positions = array_flip($ids->all());
                $products = $products
                    ->sortBy(fn (array $product) => $positions[$product['id']])
                    ->take($validated['limit'])
                    ->values();
            } elseif ($isPriceSort) {
                $products = $validated['sort'] === 'price_asc'
                    ? $products->sortBy('sortPrice')->values()
                    : $products->sortByDesc('sortPrice')->values();

                $products = $products->take($validated['limit'])->values();
            }

            return ['ok' => true, 'products' => $products
                ->map(fn (array $product) => collect($product)->except('sortPrice')->all())
                ->all()];
        } catch (\Illuminate\Validation\ValidationException) {
            return ['ok' => false, 'products' => [], 'code' => 'validation'];
        } catch (\Throwable) {
            return ['ok' => false, 'products' => [], 'code' => 'unavailable'];
        }
    }

    /**
     * @param list<array<string, mixed>> $blocks
     * @param array<string, list<array<string, mixed>>> $products
     */
    protected function collectPreviewProducts(array $blocks, array &$products): void
    {
        foreach ($blocks as $block) {
            if (!is_array($block)) {
                continue;
            }

            if (($block['type'] ?? null) === 'product' && !empty($block['id'])) {
                $result = $this->previewProducts([
                    'limit' => min(max((int) ($block['productLimit'] ?? 3), 1), 12),
                    'selectionMode' => $block['productSelectionMode'] ?? 'filters',
                    'ids' => $block['productIds'] ?? '',
                    'filter' => $block['productFilter'] ?? 'all',
                    'sort' => $block['productSort'] ?? 'newest',
                    'categoryId' => $block['productCategoryId'] ?: null,
                    'attributeAlias' => $block['productAttributeAlias'] ?? '',
                    'attributeValue' => $block['productAttributeValue'] ?? '',
                    'availability' => $block['productAvailability'] ?? 'available',
                ]);
                $products[(string) $block['id']] = $result['ok'] ? $result['products'] : [];
            }

            if (($block['type'] ?? null) !== 'layout') {
                continue;
            }

            foreach ((array) ($block['columns'] ?? []) as $column) {
                $this->collectPreviewProducts((array) ($column['blocks'] ?? []), $products);
            }
        }
    }

    /**
     * Render the package-owned EvoUI panel.
     *
     * @return mixed
     * @since 2.0.0
     */
    public function render(): mixed
    {
        return view('sMailer::components.module-panel', [
            'tabs' => $this->navigationTabs(),
            'activeTab' => $this->activeTab,
            'screen' => $this->screen,
            'context' => $this->context,
        ]);
    }

    /**
     * Keep client-provided tab state inside the declared navigation contract.
     *
     * @param string $tab
     * @return string
     * @since 2.0.0
     */
    protected function normalizeTab(string $tab): string
    {
        $allowed = collect($this->rawTabs)
            ->pluck('key')
            ->filter()
            ->values()
            ->all();

        return in_array($tab, $allowed, true) ? $tab : ($allowed[0] ?? 'overview');
    }

    /**
     * Convert package tab metadata into EvoUI wire navigation actions.
     *
     * @return list<array<string, mixed>>
     * @since 2.0.0
     */
    protected function navigationTabs(): array
    {
        return collect($this->rawTabs)
            ->map(function (array $tab): array {
                $key = (string)($tab['key'] ?? '');
                $tab['active'] = $key === $this->activeTab;
                $tab['type'] = 'wire';
                $tab['method'] = 'switchTab';
                $tab['argument'] = $key;

                return $tab;
            })
            ->values()
            ->all();
    }
}
