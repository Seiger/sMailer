<style>
    [data-smailer-module-panel] .smailer-builder {
        display: grid;
        gap: var(--evo-ui-space-3, 0.75rem);
        padding: var(--evo-ui-space-4, 1rem);
    }

    [data-smailer-module-panel] .smailer-builder__toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: var(--evo-ui-space-3, 0.75rem);
        align-items: center;
        justify-content: space-between;
    }

    [data-smailer-module-panel] .smailer-builder__heading strong,
    [data-smailer-module-panel] .smailer-builder__heading span {
        display: block;
    }

    [data-smailer-module-panel] .smailer-builder__heading strong {
        color: var(--evo-ui-text);
        font-size: 0.875rem;
    }

    [data-smailer-module-panel] .smailer-builder__heading span {
        margin-top: 0.125rem;
        color: var(--evo-ui-muted);
        font-size: 0.75rem;
    }

    [data-smailer-module-panel] .smailer-builder__toolbar-actions {
        display: inline-flex;
        flex-wrap: wrap;
        gap: var(--evo-ui-space-1, 0.25rem);
        align-items: center;
    }

    [data-smailer-module-panel] .smailer-builder__toolbar-divider {
        width: 1px;
        height: 1.5rem;
        margin: 0 var(--evo-ui-space-1, 0.25rem);
        background: var(--evo-ui-border-soft);
    }

    [data-smailer-module-panel] .smailer-builder__save-state {
        color: var(--evo-ui-muted);
        font-size: 0.75rem;
    }

    [data-smailer-module-panel] .smailer-builder__save-state.is-error {
        color: var(--evo-ui-danger);
    }

    [data-smailer-module-panel] .smailer-builder__preview-backdrop {
        position: fixed;
        z-index: 1000;
        inset: 0;
        display: grid;
        place-items: center;
        padding: var(--evo-ui-space-4, 1rem);
        background: rgb(15 23 42 / 72%);
    }

    [data-smailer-module-panel] .smailer-builder__preview-dialog {
        display: grid;
        grid-template-rows: auto minmax(0, 1fr);
        width: min(52rem, 100%);
        height: min(90dvh, 58rem);
        overflow: hidden;
        border: 1px solid var(--evo-ui-border);
        border-radius: var(--evo-ui-radius);
        background: var(--evo-ui-surface);
        box-shadow: 0 18px 48px rgb(0 0 0 / 35%);
    }

    [data-smailer-module-panel] .smailer-builder__preview-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: var(--evo-ui-space-3, .75rem);
        padding: var(--evo-ui-space-3, .75rem) var(--evo-ui-space-4, 1rem);
        border-bottom: 1px solid var(--evo-ui-border);
    }

    [data-smailer-module-panel] .smailer-builder__preview-frame {
        width: 100%;
        height: 100%;
        border: 0;
        background: #f3f4f6;
    }

    [data-smailer-module-panel] .smailer-builder__test-dialog {
        display: block;
        width: min(28rem, 100%);
        height: auto;
    }

    [data-smailer-module-panel] .smailer-builder__test-form {
        display: grid;
        gap: var(--evo-ui-space-4, 1rem);
        padding: var(--evo-ui-space-4, 1rem);
    }

    [data-smailer-module-panel] .smailer-builder__test-form label {
        display: grid;
        gap: var(--evo-ui-space-2, .5rem);
        color: var(--evo-ui-text);
        font-weight: 600;
    }

    [data-smailer-module-panel] .smailer-builder__test-actions {
        display: flex;
        justify-content: flex-end;
        gap: var(--evo-ui-space-2, .5rem);
    }

    [data-smailer-module-panel] .smailer-builder__test-status {
        color: var(--evo-ui-success, #15803d);
        font-size: .875rem;
    }

    [data-smailer-module-panel] .smailer-builder__test-status.is-error {
        color: var(--evo-ui-danger);
    }

    [data-smailer-module-panel] .smailer-builder__workspace {
        display: grid;
        grid-template-columns: 4.5rem minmax(0, 1fr);
        gap: var(--evo-ui-space-3, 0.75rem);
    }

    [data-smailer-module-panel] .smailer-builder__sidebar {
        display: grid;
        align-content: start;
        gap: var(--evo-ui-space-2, 0.5rem);
        justify-items: center;
        padding: var(--evo-ui-space-2, 0.5rem);
        border: 1px solid var(--evo-ui-border);
        border-radius: var(--evo-ui-radius);
        background: var(--evo-ui-bg);
    }

    [data-smailer-module-panel] .smailer-builder__sidebar-section {
        display: grid;
        place-items: center;
        width: 3rem;
        height: 3rem;
        color: var(--evo-ui-primary);
        border: 1px solid transparent;
        border-radius: var(--evo-ui-radius);
        background: color-mix(in oklch, var(--evo-ui-primary) 8%, var(--evo-ui-surface));
    }

    [data-smailer-module-panel] .smailer-builder__sidebar-section svg {
        width: 1.25rem;
        height: 1.25rem;
    }

    [data-smailer-module-panel] .smailer-builder__palette {
        display: grid;
        grid-template-columns: 1fr;
        gap: var(--evo-ui-space-2, 0.5rem);
    }

    [data-smailer-module-panel] .smailer-builder__palette .evo-ui-btn {
        justify-content: center;
        width: 3rem;
        min-width: 3rem;
        height: 3rem;
        padding: 0;
    }

    [data-smailer-module-panel] .smailer-builder__canvas-document {
        display: grid;
        height: calc(100dvh - 6rem);
        min-height: 0;
        padding: var(--evo-ui-space-4, 1rem);
        background: color-mix(in oklch, var(--evo-ui-muted) 7%, var(--evo-ui-surface));
        overflow-y: auto;
        overscroll-behavior: contain;
    }

    [data-smailer-module-panel] .smailer-builder__email-page {
        width: min(600px, 100%);
        min-height: 100%;
        margin: 0 auto;
        padding: 0;
        color: #1f2937;
        background: #fff;
        box-shadow: 0 4px 18px rgb(0 0 0 / 18%);
        transition: width 160ms ease;
    }

    [data-smailer-module-panel] .smailer-builder__email-page.is-mobile {
        width: min(375px, 100%);
    }

    [data-smailer-module-panel] .smailer-builder__block {
        position: relative;
        width: 100%;
        padding: var(--evo-ui-space-3, 0.75rem);
        border: 1px solid transparent;
        border-radius: 0;
        color: #1f2937;
        background: #fff;
        text-align: left;
    }

    [data-smailer-module-panel] .smailer-builder__block.is-selected {
        border-color: var(--evo-ui-primary);
        box-shadow: 0 0 0 1px var(--evo-ui-primary);
    }

    [data-smailer-module-panel] .smailer-builder__block:hover:not(.is-selected) {
        border-color: var(--evo-ui-border);
    }

    [data-smailer-module-panel] .smailer-builder__block.is-title {
        border-radius: 0;
    }

    [data-smailer-module-panel] .smailer-builder__block-content {
        display: block;
    }

    [data-smailer-module-panel] .smailer-builder__block-actions {
        position: absolute;
        z-index: 1;
        top: calc(100% - 0.25rem);
        left: 50%;
        display: inline-flex;
        gap: var(--evo-ui-space-1, 0.25rem);
        padding: 0.25rem;
        border: 1px solid var(--evo-ui-border);
        border-radius: var(--evo-ui-radius);
        background: var(--evo-ui-surface);
        box-shadow: 0 4px 14px rgb(0 0 0 / 14%);
        transform: translateX(-50%);
    }

    [data-smailer-module-panel] .smailer-builder__block-settings {
        position: absolute;
        z-index: 2;
        top: calc(100% + 3.5rem);
        left: 50%;
        display: grid;
        gap: var(--evo-ui-space-3, 0.75rem);
        width: min(20rem, calc(100vw - 4rem));
        padding: var(--evo-ui-space-3, 0.75rem);
        border: 1px solid var(--evo-ui-border);
        border-radius: var(--evo-ui-radius);
        background: var(--evo-ui-surface);
        box-shadow: 0 8px 22px rgb(0 0 0 / 20%);
        transform: translateX(-50%);
    }

    [data-smailer-module-panel] .smailer-builder__block-setting-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: var(--evo-ui-space-2, 0.5rem);
    }

    [data-smailer-module-panel] .smailer-builder__block-setting-label {
        color: var(--evo-ui-text);
        font-size: 0.8125rem;
        font-weight: 650;
    }

    [data-smailer-module-panel] .smailer-builder__alignment-toggle {
        display: inline-flex;
        gap: 0.125rem;
    }

    [data-smailer-module-panel] .smailer-builder__image-size-control {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: var(--evo-ui-space-1, 0.25rem);
        width: 10rem;
    }

    [data-smailer-module-panel] .smailer-builder__color-control {
        display: grid;
        grid-template-columns: 2.5rem minmax(0, 1fr);
        gap: var(--evo-ui-space-1, 0.25rem);
        width: 10rem;
    }

    [data-smailer-module-panel] .smailer-builder__color-control input[type="color"] {
        min-width: 0;
        padding: 0.125rem;
    }

    [data-smailer-module-panel] .smailer-builder__image-size-control select {
        width: 4rem;
    }

    [data-smailer-module-panel] .smailer-builder__block.is-text .smailer-builder__block-content {
        font-size: 1rem;
        line-height: 1.45;
    }

    [data-smailer-module-panel] .smailer-builder__title-content {
        min-height: 1.2em;
        outline: 0;
    }

    [data-smailer-module-panel] .smailer-builder__title-content.is-editable {
        cursor: text;
    }

    [data-smailer-module-panel] .smailer-builder__text-editor-bootstrap {
        position: fixed;
        bottom: -10000px;
        left: -10000px;
        width: 1px;
        height: 1px;
        overflow: hidden;
        opacity: 0;
        pointer-events: none;
    }

    [data-smailer-module-panel] .smailer-builder__html-editor .CodeMirror {
        height: 18rem;
        border: 1px solid var(--evo-ui-border);
    }

    [data-smailer-module-panel] .smailer-builder__image-frame {
        display: block;
    }

    [data-smailer-module-panel] .smailer-builder__image-frame img {
        display: block;
        width: 100%;
        height: auto;
    }

    [data-smailer-module-panel] .smailer-builder__image-placeholder {
        display: grid;
        min-height: 8rem;
        place-content: center;
        gap: var(--evo-ui-space-2, 0.5rem);
        width: 100%;
        border: 1px dashed #9ca3af;
        color: #4b5563;
        background: #f9fafb;
    }

    [data-smailer-module-panel] .smailer-builder__image-placeholder svg {
        width: 1.5rem;
        height: 1.5rem;
        margin: 0 auto;
    }

    [data-smailer-module-panel] .smailer-builder__video-card {
        position: relative;
        display: grid;
        min-height: 10rem;
        place-content: center;
        gap: var(--evo-ui-space-2, 0.5rem);
        width: 100%;
        color: #fff;
        background: #111827;
        text-align: center;
        text-decoration: none;
    }

    [data-smailer-module-panel] .smailer-builder__video-card.is-youtube {
        aspect-ratio: 16 / 9;
        min-height: 0;
        overflow: hidden;
    }

    [data-smailer-module-panel] .smailer-builder__video-thumbnail {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    [data-smailer-module-panel] .smailer-builder__video-play {
        position: relative;
        z-index: 1;
        display: grid;
        place-items: center;
        width: 3.5rem;
        height: 3.5rem;
        margin: 0 auto;
        border-radius: 50%;
        color: #1f2937;
        background: rgb(255 255 255 / 35%);
        box-shadow: 0 2px 12px rgb(0 0 0 / 18%);
        font-size: 1.25rem;
        line-height: 1;
    }

    [data-smailer-module-panel] .smailer-builder__layout-grid {
        display: grid;
        gap: var(--evo-ui-space-3, 0.75rem);
        width: 100%;
    }

    [data-smailer-module-panel] .smailer-builder__layout-column {
        display: grid;
        align-content: start;
        min-width: 0;
        min-height: 7rem;
        border: 1px dashed transparent;
    }

    [data-smailer-module-panel] .smailer-builder__layout-column.is-active,
    [data-smailer-module-panel] .smailer-builder__layout-column:hover {
        border-color: var(--evo-ui-primary);
    }

    [data-smailer-module-panel] .smailer-builder__layout-column-empty {
        display: grid;
        min-height: 7rem;
        place-content: center;
        padding: var(--evo-ui-space-2, 0.5rem);
        color: #6b7280;
        background: #f9fafb;
        font-size: 0.75rem;
        text-align: center;
    }

    [data-smailer-module-panel] .smailer-builder__layout-child {
        min-width: 0;
    }

    [data-smailer-module-panel] .smailer-builder__email-page.is-mobile .smailer-builder__layout-grid {
        grid-template-columns: minmax(0, 1fr) !important;
    }

    [data-smailer-module-panel] .smailer-builder__navigation {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: var(--evo-ui-space-3, 0.75rem);
        margin: 0;
        padding: 0;
        list-style: none;
    }

    [data-smailer-module-panel] .smailer-builder__navigation-link {
        color: inherit;
        text-decoration: none;
        white-space: nowrap;
    }

    [data-smailer-module-panel] .smailer-builder__social {
        display: flex;
        flex-wrap: wrap;
        gap: 0.625rem;
    }

    [data-smailer-module-panel] .smailer-builder__social-link {
        display: inline-grid;
        place-items: center;
        width: 2.25rem;
        height: 2.25rem;
        color: inherit;
        text-decoration: none;
    }

    [data-smailer-module-panel] .smailer-builder__social-link svg {
        width: 100%;
        height: 100%;
    }

    [data-smailer-module-panel] .smailer-builder__unsubscribe {
        color: inherit;
        font-size: 0.8125rem;
        text-decoration: underline;
    }

    [data-smailer-module-panel] .smailer-builder__product-draft {
        display: grid;
        min-height: 8rem;
        place-items: center;
        gap: var(--evo-ui-space-2, .5rem);
        color: var(--evo-ui-text-muted, #6b7280);
        border: 1px dashed var(--evo-ui-border-color, #d1d5db);
        text-align: center;
    }

    [data-smailer-module-panel] .smailer-builder__product-preview-grid {
        display: grid;
        gap: var(--evo-ui-space-3, .75rem);
    }

    [data-smailer-module-panel] .smailer-builder__product-preview-card {
        display: grid;
        align-content: start;
        gap: var(--evo-ui-space-2, .5rem);
        min-height: 7rem;
        padding: var(--evo-ui-space-3, .75rem);
        color: inherit;
        text-align: left;
        text-decoration: none;
    }

    [data-smailer-module-panel] .smailer-builder__product-preview-image {
        display: block;
        width: 100%;
        aspect-ratio: 1;
        object-fit: cover;
    }

    [data-smailer-module-panel] .smailer-builder__product-preview-title {
        font-weight: 700;
    }

    [data-smailer-module-panel] .smailer-builder__product-preview-price {
        color: var(--evo-ui-text-muted, #6b7280);
    }

    [data-smailer-module-panel] .smailer-builder__video-card svg {
        width: 2rem;
        height: 2rem;
        margin: 0 auto;
    }

    [data-smailer-module-panel] .smailer-builder__block-settings-group {
        display: grid;
        gap: var(--evo-ui-space-2, 0.5rem);
        padding-top: var(--evo-ui-space-2, 0.5rem);
        border-top: 1px solid var(--evo-ui-border-soft);
    }

    [data-smailer-module-panel] .smailer-builder__block-settings-group:first-child {
        padding-top: 0;
        border-top: 0;
    }

    [data-smailer-module-panel] .smailer-builder__image-picker-row {
        display: flex;
        align-items: center;
        gap: var(--evo-ui-space-2, 0.5rem);
    }

    [data-smailer-module-panel] .smailer-builder__image-picker-name {
        overflow: hidden;
        color: var(--evo-ui-muted);
        font-size: 0.75rem;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    [data-smailer-module-panel] .smailer-builder__image-padding-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: var(--evo-ui-space-2, 0.5rem);
    }

    [data-smailer-module-panel] .smailer-builder__image-padding-grid label {
        display: grid;
        gap: 0.25rem;
        color: var(--evo-ui-muted);
        font-size: 0.6875rem;
    }

    [data-smailer-module-panel] .smailer-builder__image-toggle {
        display: flex;
        align-items: center;
        gap: var(--evo-ui-space-2, 0.5rem);
        color: var(--evo-ui-text);
        font-size: 0.8125rem;
    }

    @media (max-width: 1050px) {
        [data-smailer-module-panel] .smailer-builder__workspace {
            grid-template-columns: 4.5rem minmax(0, 1fr);
        }
    }

    @media (max-width: 700px) {
        [data-smailer-module-panel] .smailer-builder__workspace {
            grid-template-columns: 4.5rem minmax(0, 1fr);
        }
    }
</style>

<section
    class="smailer-builder"
    data-smailer-builder-screen
    wire:ignore
    x-init="init()"
    x-data="{
        blocks: @js(data_get($mailing, 'document.blocks', [])),
        mailingId: @js(data_get($mailing, 'id')),
        mailingName: @js(data_get($mailing, 'name', __('sMailer::global.mailing_untitled'))),
        isSaving: false,
        saveMessage: '',
        saveError: false,
        selectedId: null,
        editingId: null,
        settingsId: null,
        history: [],
        historyIndex: -1,
        historyTimer: null,
        imageLibraryTargetId: null,
        activeLayoutId: null,
        activeLayoutColumn: null,
        activeLayoutChildId: null,
        previewMode: 'desktop',
        textEditorSettings: null,
        textEditorBootPromise: null,
        productPreviews: {},
        productPreviewErrors: {},
        isPreviewing: false,
        previewOpen: false,
        previewHtml: '',
        previewError: '',
        testOpen: false,
        testEmail: '',
        isSendingTest: false,
        testMessage: '',
        testError: false,
        hasInitialized: false,
        isImageBlock(block) {
            return ['image', 'logo'].includes(block.type);
        },
        isTitleBlock(block) {
            return block.type === 'title';
        },
        isDividerBlock(block) {
            return block.type === 'divider';
        },
        isButtonBlock(block) {
            return block.type === 'button';
        },
        isVideoBlock(block) {
            return block.type === 'video';
        },
        isNavigationBlock(block) {
            return block.type === 'navigation';
        },
        isSocialBlock(block) {
            return block.type === 'social';
        },
        isUnsubscribeBlock(block) {
            return block.type === 'unsubscribe';
        },
        isHtmlBlock(block) {
            return block?.type === 'html';
        },
        isProductBlock(block) {
            return block.type === 'product';
        },
        isLayoutBlock(block) {
            return block.type === 'layout';
        },
        youtubeVideoId(url) {
            if (!url) return null;

            try {
                const parsed = new URL(url);
                const host = parsed.hostname.replace(/^www\./, '');
                if (host === 'youtu.be') return parsed.pathname.split('/').filter(Boolean)[0] || null;
                if (!['youtube.com', 'm.youtube.com'].includes(host)) return null;

                return parsed.searchParams.get('v')
                    || parsed.pathname.match(/^\/(?:embed|shorts|live)\/([^/?#]+)/)?.[1]
                    || null;
            } catch (error) {
                return null;
            }
        },
        youtubeThumbnailUrl(block) {
            const videoId = this.youtubeVideoId(block.videoUrl);
            return videoId ? `https://i.ytimg.com/vi/${videoId}/hqdefault.jpg` : null;
        },
        videoThumbnailUrl(block) {
            return block.videoThumbnailSrc || this.youtubeThumbnailUrl(block);
        },
        normalizeTextHtml(html) {
            if (!html || !window.DOMParser) return html || '';

            const documentNode = new DOMParser().parseFromString(html, 'text/html');
            documentNode.querySelectorAll('img[data-emoji], img[src*=&quot;fonts.gstatic.com/s/e/notoemoji/&quot;]').forEach((image) => {
                const emoji = image.dataset.emoji || image.getAttribute('aria-label') || image.getAttribute('alt');
                if (emoji) image.replaceWith(documentNode.createTextNode(emoji));
            });

            return documentNode.body.innerHTML;
        },
        textEditorId(block) {
            return `smailer-builder-text-editor-${block.id}`;
        },
        htmlEditorId(block) {
            return `smailer-builder-html-editor-${block.id}`;
        },
        fitCanvas(canvas) {
            const refresh = () => {
                const bottomGap = 8;
                const availableHeight = Math.max(18 * 16, Math.floor(window.innerHeight - canvas.getBoundingClientRect().top - bottomGap));
                canvas.style.height = `${availableHeight}px`;
            };

            canvas._smailerCanvasFit = refresh;
            refresh();
            window.addEventListener('resize', refresh);
        },
        bootstrapTextEditor(host) {
            const field = host?.querySelector('[data-evo-rich-editor]');
            if (!field || this.textEditorSettings || this.textEditorBootPromise) return;

            this.textEditorBootPromise = Promise.resolve(window.EvoUI?.initRichEditorField(host))
                .then(() => new Promise((resolve) => {
                    const captureSettings = (attempt = 0) => {
                        const editor = window.tinymce?.get(field.id);
                        if (editor) {
                            this.textEditorSettings = { ...editor.settings };
                            editor.remove();
                            resolve(this.textEditorSettings);
                            return;
                        }

                        if (attempt >= 20) {
                            resolve(null);
                            return;
                        }

                        window.setTimeout(() => captureSettings(attempt + 1), 50);
                    };

                    captureSettings();
                }))
                .finally(() => { this.textEditorBootPromise = null; });
        },
        init() {
            if (this.hasInitialized) return;
            this.hasInitialized = true;
            this.blocks = this.blocks.map((block) => ({
                ...block,
                columns: this.isLayoutBlock(block)
                    ? (block.columns || []).map((column) => ({
                        blocks: (column.blocks || []).map((child) => ({
                            ...child,
                            socialLinks: this.isSocialBlock(child) && child.socialLinks?.length
                                ? child.socialLinks.map((link) => ({ ...link, color: link.color || this.socialDefaultColor(link.platform) }))
                                : (this.isSocialBlock(child) ? this.defaultSocialLinks() : []),
                            socialSize: Math.min(Math.max(Number(child.socialSize) || 28, 16), 56),
                            unsubscribeText: this.isUnsubscribeBlock(child) ? (child.unsubscribeText || @js(__('sMailer::global.builder_unsubscribe_default_text'))) : child.unsubscribeText,
                        })),
                    }))
                    : block.columns,
                content: this.normalizeTextHtml(block.content === block.label ? '' : (block.content || '')),
                titleLevel: block.titleLevel || 'h2',
                fontFamily: block.fontFamily || 'Arial, sans-serif',
                fontSize: Number(block.fontSize) || 32,
                fontWeight: block.fontWeight || '700',
                fontStyle: block.fontStyle || 'normal',
                textDecoration: block.textDecoration || 'none',
                textColor: block.textColor || '#1f2937',
                backgroundColor: block.backgroundColor || '#ffffff',
                lineHeight: Number(block.lineHeight) || 1.2,
                dividerColor: block.dividerColor || '#e5e7eb',
                dividerThickness: Number(block.dividerThickness) || 1,
                buttonText: block.buttonText || @js(__('sMailer::global.builder_button_default_text')),
                buttonLink: block.buttonLink || '',
                buttonBackgroundColor: block.buttonBackgroundColor || '#2563eb',
                buttonTextColor: block.buttonTextColor || '#ffffff',
                buttonRadius: Number(block.buttonRadius) || 6,
                videoUrl: block.videoUrl || '',
                videoThumbnailSrc: block.videoThumbnailSrc || '',
                videoThumbnailFileName: block.videoThumbnailFileName || '',
                navigationLinks: this.isNavigationBlock(block) && block.navigationLinks?.length
                    ? block.navigationLinks
                    : (this.isNavigationBlock(block) ? this.defaultNavigationLinks() : []),
                socialLinks: this.isSocialBlock(block) && block.socialLinks?.length
                    ? block.socialLinks.map((link) => ({ ...link, color: link.color || this.socialDefaultColor(link.platform) }))
                    : (this.isSocialBlock(block) ? this.defaultSocialLinks() : []),
                socialSize: Math.min(Math.max(Number(block.socialSize) || 28, 16), 56),
                unsubscribeText: block.unsubscribeText || @js(__('sMailer::global.builder_unsubscribe_default_text')),
                productLimit: Math.min(Math.max(Number(block.productLimit) || 3, 1), 12),
                productColumns: [1, 2, 3].includes(Number(block.productColumns)) ? Number(block.productColumns) : 1,
                productSelectionMode: block.productSelectionMode === 'ids' ? 'ids' : 'filters',
                productIds: block.productIds || '',
                productFilter: block.productFilter || 'all',
                productSort: ['newest', 'oldest', 'title_asc', 'title_desc', 'price_asc', 'price_desc'].includes(block.productSort) ? block.productSort : 'newest',
                productCategoryId: block.productCategoryId || '',
                productAttributeAlias: block.productAttributeAlias || '',
                productAttributeValue: block.productAttributeValue || '',
                productAvailability: block.productAvailability || 'available',
            }));
            this.recordHistory(true);
            this.$nextTick(() => window.setTimeout(() => this.refreshSavedProductPreviews(), 250));
        },
        snapshot() {
            return JSON.stringify(this.blocks);
        },
        recordHistory(force = false) {
            const snapshot = this.snapshot();
            if (!force && this.history[this.historyIndex] === snapshot) return;
            this.history = this.history.slice(0, this.historyIndex + 1);
            this.history.push(snapshot);
            if (this.history.length > 50) this.history.shift();
            this.historyIndex = this.history.length - 1;
        },
        queueHistory() {
            window.clearTimeout(this.historyTimer);
            this.historyTimer = window.setTimeout(() => this.recordHistory(), 250);
        },
        restoreHistory(index) {
            if (index < 0 || index >= this.history.length) return;
            this.historyIndex = index;
            this.blocks = JSON.parse(this.history[index]);
            this.selectedId = null;
            this.editingId = null;
            this.settingsId = null;
        },
        undo() {
            this.restoreHistory(this.historyIndex - 1);
        },
        redo() {
            this.restoreHistory(this.historyIndex + 1);
        },
        blockStyle(block) {
            const padding = this.isImageBlock(block)
                ? `${block.paddingTop}px ${block.paddingRight}px ${block.paddingBottom}px ${block.paddingLeft}px`
                : `${block.padding}px`;

            const style = {
                padding,
                textAlign: block.align,
                backgroundColor: this.isTitleBlock(block) ? block.backgroundColor : '#fff',
            };

            return style;
        },
        imageFrameStyle(block) {
            const configuredWidth = `${block.imageWidth}${block.imageWidthUnit}`;
            const mobileWidth = this.previewMode === 'mobile' && block.imageResponsive && block.imageWidthUnit === '%'
                ? `${Math.round(Number(block.imageWidth) * 6)}px`
                : configuredWidth;

            return {
                width: mobileWidth,
                maxWidth: this.previewMode === 'mobile' && block.imageResponsive ? '100%' : 'none',
                marginLeft: block.align === 'left' ? '0' : 'auto',
                marginRight: block.align === 'right' ? '0' : 'auto',
                borderRadius: block.imageRounded ? '0.75rem' : '0',
                overflow: 'hidden',
            };
        },
        dividerStyle(block) {
            return {
                margin: '0',
                border: '0',
                borderTop: `${block.dividerThickness}px solid ${block.dividerColor}`,
            };
        },
        buttonStyle(block) {
            return {
                display: 'inline-block',
                padding: '0.75rem 1.25rem',
                borderRadius: `${block.buttonRadius}px`,
                color: block.buttonTextColor,
                backgroundColor: block.buttonBackgroundColor,
                fontWeight: '700',
                lineHeight: '1.2',
                textDecoration: 'none',
            };
        },
        titleStyle(block) {
            return {
                fontFamily: block.fontFamily,
                fontSize: `${block.fontSize}px`,
                fontWeight: block.fontWeight,
                fontStyle: block.fontStyle,
                textDecoration: block.textDecoration,
                color: block.textColor,
                lineHeight: block.lineHeight,
                margin: '0',
            };
        },
        setTitleLevel(block, level) {
            const sizes = { p: 16, h1: 40, h2: 32, h3: 28, h4: 24, h5: 20, h6: 18 };
            block.titleLevel = level;
            block.fontSize = sizes[level] || 16;
            this.recordHistory();
        },
        defaultNavigationLinks() {
            return [
                { label: @js(__('sMailer::global.builder_navigation_default_first')), url: '#' },
                { label: @js(__('sMailer::global.builder_navigation_default_second')), url: '#' },
                { label: @js(__('sMailer::global.builder_navigation_default_third')), url: '#' },
            ];
        },
        defaultSocialLinks() {
            return [
                { platform: 'facebook', url: '', color: '#1877f2' },
                { platform: 'instagram', url: '', color: '#e4405f' },
                { platform: 'youtube', url: '', color: '#ff0000' },
            ];
        },
        socialPlatforms() {
            return ['facebook', 'instagram', 'youtube', 'linkedin', 'tiktok', 'telegram', 'whatsapp', 'x'];
        },
        socialDefaultColor(platform) {
            return {
                facebook: '#1877f2', instagram: '#e4405f', youtube: '#ff0000', linkedin: '#0a66c2',
                tiktok: '#000000', telegram: '#229ed9', whatsapp: '#25d366', x: '#000000',
            }[platform] || '#1f2937';
        },
        addSocialLink(block) {
            const used = new Set(block.socialLinks.map((link) => link.platform));
            const platform = this.socialPlatforms().find((item) => !used.has(item)) || 'facebook';
            block.socialLinks.push({ platform, url: '', color: this.socialDefaultColor(platform) });
            this.recordHistory();
        },
        removeSocialLink(block, index) {
            block.socialLinks.splice(index, 1);
            this.recordHistory();
        },
        addNavigationLink(block) {
            block.navigationLinks.push({ label: @js(__('sMailer::global.builder_navigation_new_item')), url: '#' });
            this.recordHistory();
        },
        removeNavigationLink(block, index) {
            if (block.navigationLinks.length <= 1) return;
            block.navigationLinks.splice(index, 1);
            this.recordHistory();
        },
        async refreshProductPreview(block) {
            this.productPreviewErrors[block.id] = '';

            try {
                const result = await $wire.previewProducts({
                    limit: Math.min(Math.max(Number(block.productLimit) || 1, 1), 12),
                    selectionMode: block.productSelectionMode || 'filters',
                    ids: block.productIds || '',
                    filter: block.productFilter || 'all',
                    sort: block.productSort || 'newest',
                    categoryId: block.productCategoryId || null,
                    attributeAlias: block.productAttributeAlias || '',
                    attributeValue: block.productAttributeValue || '',
                    availability: block.productAvailability || 'available',
                });

                if (!result?.ok) {
                    this.productPreviews[block.id] = [];
                    this.productPreviewErrors[block.id] = result?.code || 'unavailable';
                    return;
                }

                this.productPreviews[block.id] = result.products || [];
            } catch (error) {
                this.productPreviews[block.id] = [];
                this.productPreviewErrors[block.id] = 'unavailable';
            }
        },
        refreshSavedProductPreviews() {
            const productBlocks = [];

            this.blocks.forEach((block) => {
                if (this.isProductBlock(block)) productBlocks.push(block);
                if (!this.isLayoutBlock(block)) return;

                (block.columns || []).forEach((column) => {
                    (column.blocks || []).forEach((child) => {
                        if (this.isProductBlock(child)) productBlocks.push(child);
                    });
                });
            });

            productBlocks.forEach((block) => this.refreshProductPreview(block));
        },
        makeBlock(type, label) {
            const id = `block-${Date.now()}-${this.blocks.length}`;
            const image = ['image', 'logo'].includes(type);
            return {
                id,
                type,
                label,
                content: '',
                padding: 16,
                align: 'left',
                imageSrc: null,
                imageFileName: '',
                imageAlt: '',
                imageLink: '',
                imageWidth: 100,
                imageWidthUnit: '%',
                imageRounded: false,
                imageResponsive: true,
                paddingTop: image ? 10 : 16,
                paddingRight: image ? 16 : 16,
                paddingBottom: image ? 10 : 16,
                paddingLeft: image ? 16 : 16,
                titleLevel: 'h2',
                fontFamily: 'Arial, sans-serif',
                fontSize: 32,
                fontWeight: '700',
                fontStyle: 'normal',
                textDecoration: 'none',
                textColor: '#1f2937',
                backgroundColor: '#ffffff',
                lineHeight: 1.2,
                dividerColor: '#e5e7eb',
                dividerThickness: 1,
                buttonText: type === 'button' ? @js(__('sMailer::global.builder_button_default_text')) : '',
                buttonLink: '',
                buttonBackgroundColor: '#2563eb',
                buttonTextColor: '#ffffff',
                buttonRadius: 6,
                videoUrl: '',
                videoThumbnailSrc: '',
                videoThumbnailFileName: '',
                navigationLinks: type === 'navigation' ? this.defaultNavigationLinks() : [],
                socialLinks: type === 'social' ? this.defaultSocialLinks() : [],
                socialSize: 28,
                unsubscribeText: type === 'unsubscribe' ? @js(__('sMailer::global.builder_unsubscribe_default_text')) : '',
                productLimit: 3,
                productColumns: 1,
                productSelectionMode: 'filters',
                productIds: '',
                productFilter: 'all',
                productSort: 'newest',
                productCategoryId: '',
                productAttributeAlias: '',
                productAttributeValue: '',
                productAvailability: 'available',
            };
        },
        addLayout(columns, label) {
            const layout = this.makeBlock('layout', label);
            layout.columns = Array.from({ length: columns }, () => ({ blocks: [] }));
            this.blocks.push(layout);
            this.selectedId = layout.id;
            this.activeLayoutId = layout.id;
            this.activeLayoutColumn = 0;
            this.activeLayoutChildId = null;
            this.settingsId = layout.id;
            this.recordHistory();
        },
        addBlock(type, label) {
            const block = this.makeBlock(type, label);
            const layout = this.blocks.find((item) => item.id === this.activeLayoutId && this.isLayoutBlock(item));
            if (layout && Number.isInteger(this.activeLayoutColumn) && layout.columns?.[this.activeLayoutColumn]) {
                layout.columns[this.activeLayoutColumn].blocks.push(block);
                this.selectedId = layout.id;
                this.activeLayoutChildId = block.id;
                this.settingsId = layout.id;
                this.editingId = ['text', 'html'].includes(block.type) ? block.id : null;
                this.recordHistory();
                if (this.isImageBlock(block)) this.$nextTick(() => this.openImageLibrary());
                return;
            }

            this.blocks.push(block);
            const image = this.isImageBlock(block);
            this.selectedId = block.id;
            this.editingId = image || type === 'title' || type === 'video' || type === 'navigation' || type === 'social' || type === 'unsubscribe' || type === 'product' ? null : block.id;
            this.settingsId = image || type === 'video' || type === 'navigation' || type === 'social' || type === 'unsubscribe' || type === 'product' ? block.id : null;
            this.recordHistory();
            if (type === 'title') this.$nextTick(() => this.$refs.titleEditor?.focus());
        },
        get selectedBlock() {
            return this.blocks.find((block) => block.id === this.selectedId) ?? null;
        },
        get selectedLayoutChild() {
            const layout = this.blocks.find((block) => block.id === this.activeLayoutId && this.isLayoutBlock(block));
            return layout?.columns?.[this.activeLayoutColumn]?.blocks?.find((block) => block.id === this.activeLayoutChildId) ?? null;
        },
        findBlockById(id) {
            for (const block of this.blocks) {
                if (block.id === id) return block;
                if (!this.isLayoutBlock(block)) continue;
                for (const column of block.columns || []) {
                    const child = column.blocks?.find((item) => item.id === id);
                    if (child) return child;
                }
            }

            return null;
        },
        selectLayoutColumn(layout, index, keepActiveEditor = false) {
            if (!keepActiveEditor) this.closeActiveTextEditor();
            this.selectedId = layout.id;
            this.activeLayoutId = layout.id;
            this.activeLayoutColumn = index;
            this.activeLayoutChildId = null;
            this.settingsId = layout.id;
        },
        selectLayoutChild(layout, index, child) {
            this.selectLayoutColumn(layout, index, this.editingId === child.id);
            this.activeLayoutChildId = child.id;
            this.editingId = ['text', 'html'].includes(child.type) ? child.id : null;
        },
        setLayoutColumns(layout, count) {
            const columns = layout.columns || [];
            layout.columns = Array.from({ length: count }, (_, index) => columns[index] || { blocks: [] });
            this.activeLayoutColumn = Math.min(this.activeLayoutColumn ?? 0, count - 1);
            this.recordHistory();
        },
        removeSelected() {
            this.closeActiveTextEditor();
            this.blocks = this.blocks.filter((block) => block.id !== this.selectedId);
            this.selectedId = this.blocks.at(-1)?.id ?? null;
            this.editingId = null;
            this.settingsId = null;
            this.recordHistory();
        },
        duplicateSelected() {
            const index = this.blocks.findIndex((block) => block.id === this.selectedId);
            if (index < 0) return;
            const source = this.blocks[index];
            const id = `block-${Date.now()}-${this.blocks.length}`;
            this.blocks.splice(index + 1, 0, { ...source, id });
            this.selectedId = id;
            this.recordHistory();
        },
        moveSelected(direction) {
            const index = this.blocks.findIndex((block) => block.id === this.selectedId);
            const target = index + direction;
            if (index < 0 || target < 0 || target >= this.blocks.length) return;
            [this.blocks[index], this.blocks[target]] = [this.blocks[target], this.blocks[index]];
            this.recordHistory();
        },
        selectBlock(block) {
            if (this.editingId !== null && this.editingId !== block.id) this.closeActiveTextEditor();
            this.selectedId = block.id;
            this.activeLayoutId = null;
            this.activeLayoutColumn = null;
            this.activeLayoutChildId = null;
            if (this.isTitleBlock(block)) {
                this.$nextTick(() => this.$refs.titleEditor?.focus());
                return;
            }
            if (['text', 'html'].includes(block.type)) this.editingId = block.id;
        },
        initTextEditor(block, host) {
            const field = host?.querySelector('[data-evo-rich-editor]');
            if (!host || !field) return;

            const content = this.normalizeTextHtml(block.content || '');
            block.content = content;
            field.value = content;
            window.tinymce?.get(field.id)?.remove();

            const bindEditor = (editor = window.tinymce?.get(field.id)) => {
                if (!editor) return;
                this.textEditorSettings ??= { ...editor.settings };
                editor.setContent(block.content);
                editor.on('input change undo redo', () => {
                    block.content = this.normalizeTextHtml(editor.getContent() || '');
                    field.value = block.content;
                    this.queueHistory();
                });
            };

            if (this.textEditorSettings && window.tinymce?.init) {
                const settings = { ...this.textEditorSettings, target: field };
                delete settings.selector;
                Promise.resolve(window.tinymce.init(settings)).then(bindEditor);
                return;
            }

            if (this.textEditorBootPromise) {
                this.textEditorBootPromise.then(() => {
                    if (field.isConnected && this.textEditorSettings) this.initTextEditor(block, host);
                });
                return;
            }
        },
        syncActiveTextEditor() {
            const block = this.findBlockById(this.editingId);
            if (block?.type !== 'text') return;
            const editor = window.tinymce?.get(this.textEditorId(block));
            if (editor && block) block.content = editor.getContent() || '';
        },
        initHtmlEditor(block, host) {
            const field = host?.querySelector('[data-smailer-html-editor]');
            if (!field || field._smailerCodeMirror || !window.CodeMirror) return;

            field.value = block.content || '';
            const options = {
                ...(window.config || {}),
                mode: 'htmlmixed',
                lineNumbers: true,
                lineWrapping: true,
            };
            const editor = window.CodeMirror.fromTextArea(field, options);
            field._smailerCodeMirror = editor;
            editor.on('change', () => {
                block.content = editor.getValue();
                field.value = block.content;
                this.queueHistory();
            });
            this.$nextTick(() => editor.refresh());
        },
        syncActiveHtmlEditor() {
            const block = this.findBlockById(this.editingId);
            if (!this.isHtmlBlock(block)) return;
            const field = document.getElementById(this.htmlEditorId(block));
            const editor = field?._smailerCodeMirror;
            if (editor) block.content = editor.getValue();
        },
        closeActiveTextEditor() {
            const block = this.findBlockById(this.editingId);
            if (block?.type === 'text') {
                this.syncActiveTextEditor();
                window.tinymce?.get(this.textEditorId(block))?.remove();
            } else if (this.isHtmlBlock(block)) {
                this.syncActiveHtmlEditor();
                document.getElementById(this.htmlEditorId(block))?._smailerCodeMirror?.toTextArea();
            } else {
                return;
            }
            this.editingId = null;
            this.recordHistory();
        },
        toggleSettings() {
            this.settingsId = this.settingsId === this.selectedId ? null : this.selectedId;
        },
        openImageLibrary() {
            const target = this.selectedLayoutChild || this.selectedBlock;
            if (!this.isImageBlock(target)) return;
            this.imageLibraryTargetId = target.id;
            this.$refs.imageSource.value = target.imageSrc || '';
            window.EvoUI?.browseImageField(this.$refs.imageSource.id);
        },
        openVideoThumbnailLibrary() {
            const target = this.selectedLayoutChild || this.selectedBlock;
            if (!this.isVideoBlock(target)) return;
            this.imageLibraryTargetId = target.id;
            this.$refs.imageSource.value = target.videoThumbnailSrc || '';
            window.EvoUI?.browseImageField(this.$refs.imageSource.id);
        },
        assignImageFromLibrary(value) {
            const block = this.findBlockById(this.imageLibraryTargetId);
            if (!value || !block) return;
            const siteUrl = window.EVO?.config?.EVO_SITE_URL || window.EVO_SITE_URL || window.location.origin + '/';
            const source = new URL(value, siteUrl).toString();
            const fileName = value.split('/').filter(Boolean).pop() || value;
            if (this.isVideoBlock(block)) {
                block.videoThumbnailSrc = source;
                block.videoThumbnailFileName = fileName;
            } else {
                block.imageSrc = source;
                block.imageFileName = fileName;
            }
            this.settingsId = this.activeLayoutId && this.selectedLayoutChild?.id === block.id
                ? this.activeLayoutId
                : block.id;
            this.recordHistory();
        },
        removeVideoThumbnail(block) {
            block.videoThumbnailSrc = '';
            block.videoThumbnailFileName = '';
            this.recordHistory();
        },
        removeImage(block) {
            block.imageSrc = null;
            block.imageFileName = '';
            this.recordHistory();
        },
        setImageWidthUnit(block, unit) {
            block.imageWidthUnit = unit;
            const max = unit === '%' ? 100 : 600;
            block.imageWidth = Math.min(Math.max(Number(block.imageWidth) || 1, 1), max);
            this.recordHistory();
        },
        async saveTemplate() {
            if (this.isSaving) return;
            this.isSaving = true;
            this.saveMessage = '';
            this.saveError = false;
            this.syncActiveTextEditor();
            this.syncActiveHtmlEditor();
            const documentSnapshot = {
                version: 1,
                blocks: JSON.parse(JSON.stringify(this.blocks)),
            };

            try {
                const payload = await $wire.saveDocument(
                    documentSnapshot,
                    this.mailingId,
                    this.mailingName,
                );

                if (!payload?.ok) {
                    this.saveError = true;
                    this.saveMessage = `${@js(__('sMailer::global.builder_save_failed'))} (${payload?.code || 'unexpected'})`;
                    return;
                }

                this.mailingId = payload.id;
                this.mailingName = payload.name;
                this.blocks = documentSnapshot.blocks;
                const url = new URL(window.location.href);
                url.searchParams.set('smailer_mailing', String(payload.id));
                window.history.replaceState({}, '', url);
                this.saveMessage = @js(__('sMailer::global.builder_save_success'));
            } catch (error) {
                this.saveError = true;
                this.saveMessage = @js(__('sMailer::global.builder_save_failed'));
            } finally {
                this.isSaving = false;
            }
        },
        async openPreview() {
            if (this.isPreviewing) return;
            this.isPreviewing = true;
            this.previewError = '';
            this.syncActiveTextEditor();
            this.syncActiveHtmlEditor();

            try {
                const payload = await $wire.renderPreview({
                    version: 1,
                    blocks: JSON.parse(JSON.stringify(this.blocks)),
                });

                if (!payload?.ok || !payload.html) {
                    this.previewError = `${@js(__('sMailer::global.builder_preview'))} (${payload?.code || 'unavailable'})`;
                    return;
                }

                this.previewHtml = payload.html;
                this.previewOpen = true;
            } catch (error) {
                this.previewError = `${@js(__('sMailer::global.builder_preview'))} (unavailable)`;
            } finally {
                this.isPreviewing = false;
            }
        },
        openTest() {
            this.testMessage = '';
            this.testError = false;
            this.testOpen = true;
            this.$nextTick(() => this.$refs.testEmail?.focus());
        },
        async sendTest() {
            if (this.isSendingTest) return;
            this.isSendingTest = true;
            this.testMessage = '';
            this.testError = false;
            this.syncActiveTextEditor();
            this.syncActiveHtmlEditor();

            try {
                const payload = await $wire.sendTest({
                    version: 1,
                    blocks: JSON.parse(JSON.stringify(this.blocks)),
                }, this.testEmail, this.mailingName);
                this.testError = !payload?.ok;
                this.testMessage = payload?.ok
                    ? @js(__('sMailer::global.builder_test_sent'))
                    : `${@js(__('sMailer::global.builder_test_failed'))} (${payload?.code || 'transport'})`;
            } catch (error) {
                this.testError = true;
                this.testMessage = `${@js(__('sMailer::global.builder_test_failed'))} (transport)`;
            } finally {
                this.isSendingTest = false;
            }
        }
    }"
>
    <input id="smailer-builder-image-source" x-ref="imageSource" class="evo-ui-sr-only" type="text" data-evo-media-bridge @input="assignImageFromLibrary($event.target.value)">
    <div class="smailer-builder__text-editor-bootstrap" aria-hidden="true" x-init="$nextTick(() => bootstrapTextEditor($el))">
        <textarea id="smailer-builder-text-editor-bootstrap" data-evo-rich-editor></textarea>
        {!! \EvoUI\Support\RichTextEditor::html('smailer-builder-text-editor-bootstrap', '220px', 'system') !!}
    </div>
    <div class="smailer-builder__text-editor-bootstrap" aria-hidden="true">
        <textarea id="smailer-builder-html-editor-bootstrap" name="smailer-builder-html-editor-bootstrap"></textarea>
        {!! app(\Seiger\sMailer\Controllers\sMailerController::class)->textEditor('smailer-builder-html-editor-bootstrap', '220px', 'Codemirror') !!}
    </div>
    <div class="evo-ui-table-toolbar smailer-builder__toolbar">
        <div class="smailer-builder__heading">
            <strong>{{ __('sMailer::global.campaign_editor_destination') }}</strong>
            <span>{{ __('sMailer::global.builder_local_only') }}</span>
        </div>

        <div class="evo-ui-table-actions smailer-builder__toolbar-actions" aria-label="{{ __('sMailer::global.builder_toolbar') }}">
            <a class="evo-ui-btn evo-ui-btn--icon" href="{{ $collectionUrl }}" title="{{ __('sMailer::global.mailings') }}" aria-label="{{ __('sMailer::global.mailings') }}">
                <x-evo::icon name="arrow-left" />
            </a>
            <button class="evo-ui-btn evo-ui-btn--icon" type="button" :disabled="historyIndex < 1" @click="undo()" title="{{ __('sMailer::global.builder_undo') }}" aria-label="{{ __('sMailer::global.builder_undo') }}"><x-evo::icon name="arrow-back-up" /></button>
            <button class="evo-ui-btn evo-ui-btn--icon" type="button" :disabled="historyIndex >= history.length - 1" @click="redo()" title="{{ __('sMailer::global.builder_redo') }}" aria-label="{{ __('sMailer::global.builder_redo') }}"><x-evo::icon name="arrow-forward-up" /></button>
            <span class="smailer-builder__toolbar-divider" aria-hidden="true"></span>
            <div class="evo-ui-view-toggle" aria-label="{{ __('sMailer::global.builder_preview_modes') }}">
                <button :class="{ 'is-active': previewMode === 'desktop' }" type="button" @click="previewMode = 'desktop'" title="{{ __('sMailer::global.builder_preview_desktop') }}" aria-label="{{ __('sMailer::global.builder_preview_desktop') }}"><x-evo::icon name="device-desktop" /></button>
                <button :class="{ 'is-active': previewMode === 'mobile' }" type="button" @click="previewMode = 'mobile'" title="{{ __('sMailer::global.builder_preview_mobile') }}" aria-label="{{ __('sMailer::global.builder_preview_mobile') }}"><x-evo::icon name="device-mobile" /></button>
            </div>
            <button class="evo-ui-btn evo-ui-btn--icon evo-ui-btn--info" type="button" @click="openPreview()" title="{{ __('sMailer::global.builder_preview') }}" aria-label="{{ __('sMailer::global.builder_preview') }}"><x-evo::icon name="eye" /></button>
            <button class="evo-ui-btn evo-ui-btn--icon evo-ui-btn--info" type="button" @click="openTest()" title="{{ __('sMailer::global.builder_test') }}" aria-label="{{ __('sMailer::global.builder_test') }}"><x-evo::icon name="flask" /></button>
            <button class="evo-ui-btn evo-ui-btn--icon evo-ui-btn--primary" type="button" :disabled="isSaving" @click="saveTemplate()" title="{{ __('sMailer::global.builder_save') }}" aria-label="{{ __('sMailer::global.builder_save') }}"><x-evo::icon name="device-floppy" /></button>
            <span class="smailer-builder__save-state" :class="{ 'is-error': saveError || previewError }" x-show="saveMessage || previewError" x-text="previewError || saveMessage" x-cloak></span>
        </div>
    </div>

    <div class="smailer-builder__preview-backdrop" x-show="previewOpen" x-cloak @keydown.escape.window="previewOpen = false" @click.self="previewOpen = false">
        <section class="smailer-builder__preview-dialog" role="dialog" aria-modal="true" aria-label="{{ __('sMailer::global.builder_preview') }}">
            <header class="smailer-builder__preview-header">
                <strong>{{ __('sMailer::global.builder_preview') }}</strong>
                <button class="evo-ui-btn evo-ui-btn--icon" type="button" @click="previewOpen = false" aria-label="{{ __('sMailer::global.builder_preview') }}">&times;</button>
            </header>
            <iframe class="smailer-builder__preview-frame" :srcdoc="previewHtml" title="{{ __('sMailer::global.builder_preview') }}"></iframe>
        </section>
    </div>

    <div class="smailer-builder__preview-backdrop" x-show="testOpen" x-cloak @keydown.escape.window="testOpen = false" @click.self="testOpen = false">
        <section class="smailer-builder__preview-dialog smailer-builder__test-dialog" role="dialog" aria-modal="true" aria-label="{{ __('sMailer::global.builder_test') }}">
            <header class="smailer-builder__preview-header">
                <strong>{{ __('sMailer::global.builder_test') }}</strong>
                <button class="evo-ui-btn evo-ui-btn--icon" type="button" @click="testOpen = false" aria-label="{{ __('sMailer::global.builder_test') }}">&times;</button>
            </header>
            <form class="smailer-builder__test-form" @submit.prevent="sendTest()">
                <label>
                    <span>{{ __('sMailer::global.builder_test_email') }}</span>
                    <input class="evo-ui-input" x-ref="testEmail" x-model.trim="testEmail" type="email" autocomplete="email" required>
                </label>
                <p class="smailer-builder__test-status" :class="{ 'is-error': testError }" x-show="testMessage" x-text="testMessage" x-cloak></p>
                <div class="smailer-builder__test-actions">
                    <button class="evo-ui-btn" type="button" @click="testOpen = false">{{ __('sMailer::global.builder_test_cancel') }}</button>
                    <button class="evo-ui-btn evo-ui-btn--primary" type="submit" :disabled="isSendingTest" x-text="isSendingTest ? @js(__('sMailer::global.builder_test_sending')) : @js(__('sMailer::global.builder_test_send'))"></button>
                </div>
            </form>
        </section>
    </div>

    <div class="smailer-builder__workspace">
        <aside class="smailer-builder__sidebar" aria-label="{{ __('sMailer::global.builder_palette') }}">
            <span class="smailer-builder__sidebar-section" title="{{ __('sMailer::global.builder_palette') }}"><x-evo::icon name="blocks" /><span class="evo-ui-sr-only">{{ __('sMailer::global.builder_palette') }}</span></span>
            <div class="smailer-builder__palette">
                @foreach([
                    ['key' => 'title', 'icon' => 'heading'], ['key' => 'text', 'icon' => 'typography'], ['key' => 'image', 'icon' => 'photo'],
                    ['key' => 'video', 'icon' => 'video'],
                ] as $block)
                    <button class="evo-ui-btn evo-ui-btn--icon" type="button" @click="addBlock(@js($block['key']), @js(__('sMailer::global.builder_block_' . $block['key'])))" title="{{ __('sMailer::global.builder_block_' . $block['key']) }}" aria-label="{{ __('sMailer::global.builder_block_' . $block['key']) }}" data-smailer-builder-block="{{ $block['key'] }}">
                        <x-evo::icon :name="$block['icon']" /><span class="evo-ui-sr-only">{{ __('sMailer::global.builder_block_' . $block['key']) }}</span>
                    </button>
                @endforeach
                @if(class_exists(\Seiger\sCommerce\Models\sProduct::class))
                    <button class="evo-ui-btn evo-ui-btn--icon" type="button" @click="addBlock('product', @js(__('sMailer::global.builder_block_product')))" title="{{ __('sMailer::global.builder_block_product') }}" aria-label="{{ __('sMailer::global.builder_block_product') }}" data-smailer-builder-block="product"><x-evo::icon name="shopping-bag" /><span class="evo-ui-sr-only">{{ __('sMailer::global.builder_block_product') }}</span></button>
                @endif
                @foreach([
                    ['key' => 'button', 'icon' => 'pointer'],
                    ['key' => 'social', 'icon' => 'share'],
                    ['key' => 'divider', 'icon' => 'separator-horizontal'], ['key' => 'navigation', 'icon' => 'menu-2'],
                    ['key' => 'spacer', 'icon' => 'spacing-vertical'],
                ] as $block)
                    <button class="evo-ui-btn evo-ui-btn--icon" type="button" @click="addBlock(@js($block['key']), @js(__('sMailer::global.builder_block_' . $block['key'])))" title="{{ __('sMailer::global.builder_block_' . $block['key']) }}" aria-label="{{ __('sMailer::global.builder_block_' . $block['key']) }}" data-smailer-builder-block="{{ $block['key'] }}">
                        <x-evo::icon :name="$block['icon']" /><span class="evo-ui-sr-only">{{ __('sMailer::global.builder_block_' . $block['key']) }}</span>
                    </button>
                @endforeach
                <button class="evo-ui-btn evo-ui-btn--icon" type="button" @click="addBlock('html', @js(__('sMailer::global.builder_block_html')))" title="{{ __('sMailer::global.builder_block_html') }}" aria-label="{{ __('sMailer::global.builder_block_html') }}" data-smailer-builder-block="html"><x-evo::icon name="code" /><span class="evo-ui-sr-only">{{ __('sMailer::global.builder_block_html') }}</span></button>
                <button class="evo-ui-btn evo-ui-btn--icon" type="button" @click="addBlock('unsubscribe', @js(__('sMailer::global.builder_block_unsubscribe')))" title="{{ __('sMailer::global.builder_block_unsubscribe') }}" aria-label="{{ __('sMailer::global.builder_block_unsubscribe') }}" data-smailer-builder-block="unsubscribe"><x-evo::icon name="mail-off" /><span class="evo-ui-sr-only">{{ __('sMailer::global.builder_block_unsubscribe') }}</span></button>
                <button class="evo-ui-btn evo-ui-btn--icon" type="button" @click="addLayout(2, @js(__('sMailer::global.builder_layout_two_columns')))" title="{{ __('sMailer::global.builder_layout_two_columns') }}" aria-label="{{ __('sMailer::global.builder_layout_two_columns') }}" data-smailer-builder-layout="2"><x-evo::icon name="columns-2" /><span class="evo-ui-sr-only">{{ __('sMailer::global.builder_layout_two_columns') }}</span></button>
                <button class="evo-ui-btn evo-ui-btn--icon" type="button" @click="addLayout(3, @js(__('sMailer::global.builder_layout_three_columns')))" title="{{ __('sMailer::global.builder_layout_three_columns') }}" aria-label="{{ __('sMailer::global.builder_layout_three_columns') }}" data-smailer-builder-layout="3"><x-evo::icon name="columns-3" /><span class="evo-ui-sr-only">{{ __('sMailer::global.builder_layout_three_columns') }}</span></button>
            </div>
        </aside>

        <div class="smailer-builder__canvas-document" aria-label="{{ __('sMailer::global.builder_canvas') }}" x-init="fitCanvas($el)" @click.self="selectedId = null; editingId = null; settingsId = null; activeLayoutId = null; activeLayoutColumn = null; activeLayoutChildId = null">
            <article class="smailer-builder__email-page" :class="{ 'is-mobile': previewMode === 'mobile' }" @click.self="selectedId = null; editingId = null; settingsId = null; activeLayoutId = null; activeLayoutColumn = null; activeLayoutChildId = null">
                <template x-for="block in blocks" :key="block.id">
                        <article class="smailer-builder__block" :class="{ 'is-selected': selectedId === block.id, 'is-title': isTitleBlock(block), 'is-text': block.type === 'text' }" :style="blockStyle(block)" @click.stop="selectBlock(block)">
                            <template x-if="isImageBlock(block)">
                                <div class="smailer-builder__image-frame" :style="imageFrameStyle(block)">
                                    <template x-if="block.imageSrc">
                                        <a :href="block.imageLink || null" :target="block.imageLink ? '_blank' : null"><img :src="block.imageSrc" :alt="block.imageAlt"></a>
                                    </template>
                                    <button class="smailer-builder__image-placeholder" x-show="!block.imageSrc" type="button" @click.stop="openImageLibrary()">
                                        <x-evo::icon name="photo-plus" />
                                        <span>{{ __('sMailer::global.builder_image_select') }}</span>
                                    </button>
                                </div>
                            </template>
                            <template x-if="isTitleBlock(block)">
                                <div
                                    x-ref="titleEditor"
                                    class="smailer-builder__title-content"
                                    :class="{ 'is-editable': selectedId === block.id }"
                                    :contenteditable="selectedId === block.id"
                                    :style="titleStyle(block)"
                                    x-init="$el.textContent = block.content"
                                    x-effect="if (selectedId !== block.id && $el.textContent !== block.content) $el.textContent = block.content"
                                    @click="if (selectedId !== block.id) selectBlock(block); $event.stopPropagation()"
                                    @input="block.content = $event.currentTarget.innerText"
                                    @blur="recordHistory()"
                                    @keydown.escape="$event.currentTarget.blur()"
                                ></div>
                            </template>
                            <template x-if="isDividerBlock(block)">
                                <hr class="smailer-builder__divider" :style="dividerStyle(block)">
                            </template>
                            <template x-if="isButtonBlock(block)">
                                <a
                                    class="smailer-builder__button"
                                    :href="block.buttonLink || '#'"
                                    :style="buttonStyle(block)"
                                    @click.prevent.stop="selectBlock(block)"
                                    x-text="block.buttonText"
                                ></a>
                            </template>
                            <template x-if="isNavigationBlock(block)">
                                <nav class="smailer-builder__navigation" :style="{ justifyContent: block.align === 'justify' ? 'space-between' : block.align }">
                                    <template x-for="(link, index) in block.navigationLinks" :key="`${block.id}-link-${index}`">
                                        <a class="smailer-builder__navigation-link" :href="link.url || '#'" :target="link.url && link.url !== '#' ? '_blank' : null" @click.prevent.stop="selectBlock(block)" x-text="link.label"></a>
                                    </template>
                                </nav>
                            </template>
                            <template x-if="isSocialBlock(block)">
                                <nav class="smailer-builder__social" :style="{ justifyContent: block.align === 'justify' ? 'space-between' : block.align }">
                                    <template x-for="(link, index) in block.socialLinks" :key="`${block.id}-social-${index}`">
                                        <a class="smailer-builder__social-link" :href="link.url || '#'" :target="link.url ? '_blank' : null" :style="{ width: `${block.socialSize}px`, height: `${block.socialSize}px`, color: link.color || '#1f2937' }" @click.prevent.stop="selectBlock(block)">
                                            <template x-if="link.platform === 'facebook'"><x-evo::icon name="brand-facebook" /></template>
                                            <template x-if="link.platform === 'instagram'"><x-evo::icon name="brand-instagram" /></template>
                                            <template x-if="link.platform === 'youtube'"><x-evo::icon name="brand-youtube" /></template>
                                            <template x-if="link.platform === 'linkedin'"><x-evo::icon name="brand-linkedin" /></template>
                                            <template x-if="link.platform === 'tiktok'"><x-evo::icon name="brand-tiktok" /></template>
                                            <template x-if="link.platform === 'telegram'"><x-evo::icon name="brand-telegram" /></template>
                                            <template x-if="link.platform === 'whatsapp'"><x-evo::icon name="brand-whatsapp" /></template>
                                            <template x-if="link.platform === 'x'"><x-evo::icon name="brand-x" /></template>
                                        </a>
                                    </template>
                                </nav>
                            </template>
                            <template x-if="isUnsubscribeBlock(block)">
                                <a class="smailer-builder__unsubscribe" href="#unsubscribe" @click.prevent.stop="selectBlock(block)" x-text="block.unsubscribeText"></a>
                            </template>
                            <template x-if="isProductBlock(block)">
                                <template x-if="productPreviews[block.id]?.length">
                                    <div class="smailer-builder__product-preview-grid" :style="{ gridTemplateColumns: `repeat(${block.productColumns}, minmax(0, 1fr))` }">
                                        <template x-for="item in productPreviews[block.id]" :key="item.id">
                                            <a class="smailer-builder__product-preview-card" :href="item.url || '#'" :target="item.url ? '_blank' : null" @click.stop>
                                                <img class="smailer-builder__product-preview-image" x-show="item.image" :src="item.image" :alt="item.title">
                                                <span class="smailer-builder__product-preview-title" x-text="item.title"></span>
                                                <span class="smailer-builder__product-preview-price" x-text="item.price"></span>
                                            </a>
                                        </template>
                                    </div>
                                </template>
                                <template x-if="!productPreviews[block.id]?.length">
                                    <div class="smailer-builder__product-draft">
                                        <x-evo::icon name="shopping-bag" />
                                        <span>{{ __('sMailer::global.builder_product_draft') }}</span>
                                        <span x-show="productPreviewErrors[block.id]" x-text="productPreviewErrors[block.id] === 'validation' ? @js(__('sMailer::global.builder_product_preview_validation')) : @js(__('sMailer::global.builder_product_preview_empty'))"></span>
                                    </div>
                                </template>
                            </template>
                            <template x-if="isLayoutBlock(block)">
                                <div class="smailer-builder__layout-grid" :style="{ gridTemplateColumns: `repeat(${block.columns.length}, minmax(0, 1fr))` }">
                                    <template x-for="(column, index) in block.columns" :key="`${block.id}-column-${index}`">
                                        <section class="smailer-builder__layout-column" :class="{ 'is-active': activeLayoutId === block.id && activeLayoutColumn === index }" @click.stop="selectLayoutColumn(block, index)">
                                            <template x-if="column.blocks.length === 0">
                                                <span class="smailer-builder__layout-column-empty">{{ __('sMailer::global.builder_layout_column_empty') }}</span>
                                            </template>
                                            <template x-for="child in column.blocks" :key="child.id">
                                                <div class="smailer-builder__layout-child" :style="blockStyle(child)" @click.stop="selectLayoutChild(block, index, child)">
                                                    <template x-if="isImageBlock(child)">
                                                        <div class="smailer-builder__image-frame" :style="imageFrameStyle(child)">
                                                            <template x-if="child.imageSrc">
                                                                <a :href="child.imageLink || null" :target="child.imageLink ? '_blank' : null"><img :src="child.imageSrc" :alt="child.imageAlt"></a>
                                                            </template>
                                                            <button class="smailer-builder__image-placeholder" x-show="!child.imageSrc" type="button" @click.stop="selectLayoutChild(block, index, child); openImageLibrary()">
                                                                <x-evo::icon name="photo-plus" />
                                                                <span>{{ __('sMailer::global.builder_image_select') }}</span>
                                                            </button>
                                                        </div>
                                                    </template>
                                                    <template x-if="isTitleBlock(child)">
                                                        <div
                                                            class="smailer-builder__title-content"
                                                            :class="{ 'is-editable': activeLayoutChildId === child.id }"
                                                            :contenteditable="activeLayoutChildId === child.id"
                                                            :style="titleStyle(child)"
                                                            x-init="$el.textContent = child.content"
                                                            x-effect="if (activeLayoutChildId !== child.id && $el.textContent !== child.content) $el.textContent = child.content"
                                                            @click.stop="selectLayoutChild(block, index, child)"
                                                            @input="child.content = $event.currentTarget.innerText"
                                                            @blur="recordHistory()"
                                                            @keydown.escape="$event.currentTarget.blur()"
                                                        ></div>
                                                    </template>
                                                    <template x-if="isDividerBlock(child)">
                                                        <hr class="smailer-builder__divider" :style="dividerStyle(child)">
                                                    </template>
                                                    <template x-if="isButtonBlock(child)">
                                                        <a
                                                            class="smailer-builder__button"
                                                            :href="child.buttonLink || '#'"
                                                            :style="buttonStyle(child)"
                                                            @click.prevent.stop="selectLayoutChild(block, index, child)"
                                                            x-text="child.buttonText"
                                                        ></a>
                                                    </template>
                                                    <template x-if="isNavigationBlock(child)">
                                                        <nav class="smailer-builder__navigation" :style="{ justifyContent: child.align === 'justify' ? 'space-between' : child.align }">
                                                            <template x-for="(link, linkIndex) in child.navigationLinks" :key="`${child.id}-link-${linkIndex}`">
                                                                <a class="smailer-builder__navigation-link" :href="link.url || '#'" :target="link.url && link.url !== '#' ? '_blank' : null" @click.prevent.stop="selectLayoutChild(block, index, child)" x-text="link.label"></a>
                                                            </template>
                                                        </nav>
                                                    </template>
                                                    <template x-if="isSocialBlock(child)">
                                                        <nav class="smailer-builder__social" :style="{ justifyContent: child.align === 'justify' ? 'space-between' : child.align }">
                                                            <template x-for="(link, linkIndex) in child.socialLinks" :key="`${child.id}-social-${linkIndex}`">
                                                                <a class="smailer-builder__social-link" :href="link.url || '#'" :target="link.url ? '_blank' : null" :style="{ width: `${child.socialSize}px`, height: `${child.socialSize}px`, color: link.color || '#1f2937' }" @click.prevent.stop="selectLayoutChild(block, index, child)">
                                                                    <template x-if="link.platform === 'facebook'"><x-evo::icon name="brand-facebook" /></template><template x-if="link.platform === 'instagram'"><x-evo::icon name="brand-instagram" /></template><template x-if="link.platform === 'youtube'"><x-evo::icon name="brand-youtube" /></template><template x-if="link.platform === 'linkedin'"><x-evo::icon name="brand-linkedin" /></template><template x-if="link.platform === 'tiktok'"><x-evo::icon name="brand-tiktok" /></template><template x-if="link.platform === 'telegram'"><x-evo::icon name="brand-telegram" /></template><template x-if="link.platform === 'whatsapp'"><x-evo::icon name="brand-whatsapp" /></template><template x-if="link.platform === 'x'"><x-evo::icon name="brand-x" /></template>
                                                                </a>
                                                            </template>
                                                        </nav>
                                                    </template>
                                                    <template x-if="isUnsubscribeBlock(child)">
                                                        <a class="smailer-builder__unsubscribe" href="#unsubscribe" @click.prevent.stop="selectLayoutChild(block, index, child)" x-text="child.unsubscribeText"></a>
                                                    </template>
                                                    <template x-if="isProductBlock(child)">
                                                        <template x-if="productPreviews[child.id]?.length">
                                                            <div class="smailer-builder__product-preview-grid" :style="{ gridTemplateColumns: `repeat(${child.productColumns}, minmax(0, 1fr))` }">
                                                                <template x-for="item in productPreviews[child.id]" :key="item.id">
                                                                    <a class="smailer-builder__product-preview-card" :href="item.url || '#'" :target="item.url ? '_blank' : null" @click.stop>
                                                                        <img class="smailer-builder__product-preview-image" x-show="item.image" :src="item.image" :alt="item.title">
                                                                        <span class="smailer-builder__product-preview-title" x-text="item.title"></span>
                                                                        <span class="smailer-builder__product-preview-price" x-text="item.price"></span>
                                                                    </a>
                                                                </template>
                                                            </div>
                                                        </template>
                                                        <template x-if="!productPreviews[child.id]?.length">
                                                            <div class="smailer-builder__product-draft">
                                                                <x-evo::icon name="shopping-bag" />
                                                                <span>{{ __('sMailer::global.builder_product_draft') }}</span>
                                                                <span x-show="productPreviewErrors[child.id]" x-text="productPreviewErrors[child.id] === 'validation' ? @js(__('sMailer::global.builder_product_preview_validation')) : @js(__('sMailer::global.builder_product_preview_empty'))"></span>
                                                            </div>
                                                        </template>
                                                    </template>
                                                    <template x-if="isVideoBlock(child)">
                                                        <a class="smailer-builder__video-card" :class="{ 'is-youtube': videoThumbnailUrl(child) }" :href="child.videoUrl || '#'" :target="child.videoUrl ? '_blank' : null" @click.stop="selectLayoutChild(block, index, child); if (!child.videoUrl) $event.preventDefault()">
                                                            <template x-if="videoThumbnailUrl(child)"><img class="smailer-builder__video-thumbnail" :src="videoThumbnailUrl(child)" alt=""></template>
                                                            <span class="smailer-builder__video-play" aria-hidden="true"><x-evo::icon name="player-play-filled" /></span>
                                                        </a>
                                                    </template>
                                                    <template x-if="!isImageBlock(child) && !isTitleBlock(child) && !isDividerBlock(child) && !isButtonBlock(child) && !isVideoBlock(child) && !isNavigationBlock(child) && !isSocialBlock(child) && !isUnsubscribeBlock(child) && !isProductBlock(child)">
                                                        <span class="smailer-builder__block-content" x-show="editingId !== child.id" x-html="child.content"></span>
                                                    </template>
                                                    <template x-if="child.type === 'text' && editingId === child.id">
                                                        <div class="smailer-builder__text-editor" x-init="$nextTick(() => initTextEditor(child, $el))" @click.stop>
                                                            <textarea
                                                                :id="textEditorId(child)"
                                                                class="evo-ui-textarea evo-ui-textarea--editor"
                                                                rows="8"
                                                                data-evo-rich-editor
                                                                @input="child.content = $event.target.value; queueHistory()"
                                                                @blur="queueHistory()"
                                                            ></textarea>
                                                        </div>
                                                    </template>
                                                    <template x-if="isHtmlBlock(child) && editingId === child.id">
                                                        <div class="smailer-builder__html-editor" x-init="$nextTick(() => initHtmlEditor(child, $el))" @click.stop>
                                                            <textarea :id="htmlEditorId(child)" data-smailer-html-editor></textarea>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                        </section>
                                    </template>
                                </div>
                            </template>
                            <template x-if="isVideoBlock(block)">
                                <a class="smailer-builder__video-card" :class="{ 'is-youtube': videoThumbnailUrl(block) }" :href="block.videoUrl || '#'" :target="block.videoUrl ? '_blank' : null" @click.stop="selectBlock(block); if (!block.videoUrl) $event.preventDefault()">
                                    <template x-if="videoThumbnailUrl(block)">
                                        <img class="smailer-builder__video-thumbnail" :src="videoThumbnailUrl(block)" alt="">
                                    </template>
                                    <span class="smailer-builder__video-play" aria-hidden="true"><x-evo::icon name="player-play-filled" /></span>
                                </a>
                            </template>
                            <template x-if="!isImageBlock(block) && !isTitleBlock(block) && !isDividerBlock(block) && !isButtonBlock(block) && !isVideoBlock(block) && !isNavigationBlock(block) && !isSocialBlock(block) && !isUnsubscribeBlock(block) && !isProductBlock(block) && !isLayoutBlock(block)">
                                <span
                                    class="smailer-builder__block-content"
                                    x-show="editingId !== block.id"
                                    x-html="block.content"
                                ></span>
                            </template>
                            <template x-if="block.type === 'text' && editingId === block.id">
                                <div class="smailer-builder__text-editor" x-init="$nextTick(() => initTextEditor(block, $el))" @click.stop>
                                      <textarea
                                        :id="textEditorId(block)"
                                        class="evo-ui-textarea evo-ui-textarea--editor"
                                        rows="8"
                                        data-evo-rich-editor
                                          @input="block.content = $event.target.value; queueHistory()"
                                          @blur="queueHistory()"
                                      ></textarea>
                                  </div>
                            </template>
                            <template x-if="isHtmlBlock(block) && editingId === block.id">
                                <div class="smailer-builder__html-editor" x-init="$nextTick(() => initHtmlEditor(block, $el))" @click.stop>
                                    <textarea :id="htmlEditorId(block)" data-smailer-html-editor></textarea>
                                </div>
                            </template>
                            <textarea class="evo-ui-input smailer-builder__block-editor" rows="4" x-show="!isImageBlock(block) && !isTitleBlock(block) && !isDividerBlock(block) && !isButtonBlock(block) && !isVideoBlock(block) && !isNavigationBlock(block) && !isSocialBlock(block) && !isUnsubscribeBlock(block) && !isProductBlock(block) && !isLayoutBlock(block) && !isHtmlBlock(block) && block.type !== 'text' && editingId === block.id" x-cloak x-model="block.content" @blur="editingId = null; recordHistory()"></textarea>
                            <div class="smailer-builder__block-actions" x-show="selectedId === block.id" x-cloak>
                                <button class="evo-ui-btn evo-ui-btn--icon" type="button" @click.stop="moveSelected(-1)" title="{{ __('sMailer::global.builder_move_block_up') }}" aria-label="{{ __('sMailer::global.builder_move_block_up') }}"><x-evo::icon name="arrow-up" /></button>
                                <button class="evo-ui-btn evo-ui-btn--icon" type="button" @click.stop="moveSelected(1)" title="{{ __('sMailer::global.builder_move_block_down') }}" aria-label="{{ __('sMailer::global.builder_move_block_down') }}"><x-evo::icon name="arrow-down" /></button>
                                <button class="evo-ui-btn evo-ui-btn--icon" type="button" @click.stop="toggleSettings()" title="{{ __('sMailer::global.builder_block_settings') }}" aria-label="{{ __('sMailer::global.builder_block_settings') }}"><x-evo::icon name="adjustments" /></button>
                                <button class="evo-ui-btn evo-ui-btn--icon" type="button" @click.stop="duplicateSelected()" title="{{ __('sMailer::global.builder_duplicate_block') }}" aria-label="{{ __('sMailer::global.builder_duplicate_block') }}"><x-evo::icon name="copy" /></button>
                                <button class="evo-ui-btn evo-ui-btn--icon evo-ui-btn--danger" type="button" @click.stop="removeSelected()" title="{{ __('sMailer::global.builder_remove_block') }}" aria-label="{{ __('sMailer::global.builder_remove_block') }}"><x-evo::icon name="trash" /></button>
                            </div>
                            <div class="smailer-builder__block-settings" x-show="settingsId === block.id" x-cloak @click.stop>
                                <template x-if="isImageBlock(block)">
                                    <div class="smailer-builder__block-settings-group">
                                        <div class="smailer-builder__image-picker-row">
                                            <button class="evo-ui-btn evo-ui-btn--icon evo-ui-btn--primary" type="button" @click="openImageLibrary()" title="{{ __('sMailer::global.builder_image_select') }}" aria-label="{{ __('sMailer::global.builder_image_select') }}"><x-evo::icon name="photo-plus" /></button>
                                            <span class="smailer-builder__image-picker-name" x-text="block.imageFileName || @js(__('sMailer::global.builder_image_no_file'))"></span>
                                            <button class="evo-ui-btn evo-ui-btn--icon evo-ui-btn--danger" type="button" x-show="block.imageSrc" @click="removeImage(block)" title="{{ __('sMailer::global.builder_image_remove') }}" aria-label="{{ __('sMailer::global.builder_image_remove') }}"><x-evo::icon name="trash" /></button>
                                        </div>
                                        <label class="smailer-builder__block-setting-row">
                                            <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_image_alt') }}</span>
                                            <input class="evo-ui-input" type="text" x-model="block.imageAlt">
                                        </label>
                                        <label class="smailer-builder__block-setting-row">
                                            <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_image_link') }}</span>
                                            <input class="evo-ui-input" type="url" placeholder="https://" x-model="block.imageLink">
                                        </label>
                                        <label class="smailer-builder__block-setting-row">
                                            <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_image_width') }}</span>
                                            <span class="smailer-builder__image-size-control">
                                                <input class="evo-ui-input" type="number" min="1" :max="block.imageWidthUnit === '%' ? 100 : 600" step="1" x-model.number="block.imageWidth">
                                                <select class="evo-ui-input" :value="block.imageWidthUnit" @change="setImageWidthUnit(block, $event.target.value)" aria-label="{{ __('sMailer::global.builder_image_width') }}">
                                                    <option value="%">%</option>
                                                    <option value="px">px</option>
                                                </select>
                                            </span>
                                        </label>
                                        <div class="smailer-builder__block-setting-row">
                                            <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_image_shape') }}</span>
                                            <div class="smailer-builder__alignment-toggle">
                                                <button class="evo-ui-btn evo-ui-btn--icon" type="button" :class="{ 'is-active': !block.imageRounded }" @click="block.imageRounded = false" title="{{ __('sMailer::global.builder_image_shape_square') }}" aria-label="{{ __('sMailer::global.builder_image_shape_square') }}"><x-evo::icon name="square" /></button>
                                                <button class="evo-ui-btn evo-ui-btn--icon" type="button" :class="{ 'is-active': block.imageRounded }" @click="block.imageRounded = true" title="{{ __('sMailer::global.builder_image_shape_rounded') }}" aria-label="{{ __('sMailer::global.builder_image_shape_rounded') }}"><x-evo::icon name="square-rounded" /></button>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="!isImageBlock(block) && !isLayoutBlock(block) && !isProductBlock(block)">
                                    <label class="smailer-builder__block-setting-row">
                                        <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_block_padding') }}</span>
                                        <input class="evo-ui-input" type="number" min="0" max="120" step="1" x-model.number="block.padding" @change="recordHistory()">
                                    </label>
                                </template>
                                <template x-if="isTitleBlock(block)">
                                    <div class="smailer-builder__block-settings-group">
                                        <label class="smailer-builder__block-setting-row">
                                            <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_title_level') }}</span>
                                            <select class="evo-ui-input" :value="block.titleLevel" @change="setTitleLevel(block, $event.target.value)">
                                                <option value="p">{{ __('sMailer::global.builder_title_level_paragraph') }}</option>
                                                @foreach(['h1', 'h2', 'h3', 'h4', 'h5', 'h6'] as $level)
                                                    <option value="{{ $level }}">{{ strtoupper($level) }}</option>
                                                @endforeach
                                            </select>
                                        </label>
                                        <label class="smailer-builder__block-setting-row">
                                            <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_title_size') }}</span>
                                            <input class="evo-ui-input" type="number" min="8" max="96" step="1" x-model.number="block.fontSize" @change="recordHistory()">
                                        </label>
                                        <label class="smailer-builder__block-setting-row">
                                            <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_title_text_color') }}</span>
                                            <span class="smailer-builder__color-control">
                                                <input class="evo-ui-input" type="color" x-model="block.textColor" @change="recordHistory()">
                                                <input class="evo-ui-input" type="text" x-model="block.textColor" maxlength="7" pattern="#[0-9a-fA-F]{6}" spellcheck="false" @change="recordHistory()" aria-label="{{ __('sMailer::global.builder_title_text_color') }}">
                                            </span>
                                        </label>
                                        <label class="smailer-builder__block-setting-row">
                                            <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_title_background_color') }}</span>
                                            <span class="smailer-builder__color-control">
                                                <input class="evo-ui-input" type="color" x-model="block.backgroundColor" @change="recordHistory()">
                                                <input class="evo-ui-input" type="text" x-model="block.backgroundColor" maxlength="7" pattern="#[0-9a-fA-F]{6}" spellcheck="false" @change="recordHistory()" aria-label="{{ __('sMailer::global.builder_title_background_color') }}">
                                            </span>
                                        </label>
                                    </div>
                                </template>
                                <template x-if="isDividerBlock(block)">
                                    <div class="smailer-builder__block-settings-group">
                                        <label class="smailer-builder__block-setting-row">
                                            <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_divider_color') }}</span>
                                            <span class="smailer-builder__color-control">
                                                <input class="evo-ui-input" type="color" x-model="block.dividerColor" @change="recordHistory()">
                                                <input class="evo-ui-input" type="text" x-model="block.dividerColor" maxlength="7" pattern="#[0-9a-fA-F]{6}" spellcheck="false" @change="recordHistory()" aria-label="{{ __('sMailer::global.builder_divider_color') }}">
                                            </span>
                                        </label>
                                        <label class="smailer-builder__block-setting-row">
                                            <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_divider_thickness') }}</span>
                                            <input class="evo-ui-input" type="number" min="1" max="12" step="1" x-model.number="block.dividerThickness" @change="recordHistory()">
                                        </label>
                                    </div>
                                </template>
                                <template x-if="isButtonBlock(block)">
                                    <div class="smailer-builder__block-settings-group">
                                        <label class="smailer-builder__block-setting-row">
                                            <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_button_text') }}</span>
                                            <input class="evo-ui-input" type="text" x-model="block.buttonText" @change="recordHistory()">
                                        </label>
                                        <label class="smailer-builder__block-setting-row">
                                            <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_button_link') }}</span>
                                            <input class="evo-ui-input" type="url" placeholder="https://" x-model="block.buttonLink" @change="recordHistory()">
                                        </label>
                                        <label class="smailer-builder__block-setting-row">
                                            <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_button_background_color') }}</span>
                                            <span class="smailer-builder__color-control">
                                                <input class="evo-ui-input" type="color" x-model="block.buttonBackgroundColor" @change="recordHistory()">
                                                <input class="evo-ui-input" type="text" x-model="block.buttonBackgroundColor" maxlength="7" pattern="#[0-9a-fA-F]{6}" spellcheck="false" @change="recordHistory()" aria-label="{{ __('sMailer::global.builder_button_background_color') }}">
                                            </span>
                                        </label>
                                        <label class="smailer-builder__block-setting-row">
                                            <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_button_text_color') }}</span>
                                            <span class="smailer-builder__color-control">
                                                <input class="evo-ui-input" type="color" x-model="block.buttonTextColor" @change="recordHistory()">
                                                <input class="evo-ui-input" type="text" x-model="block.buttonTextColor" maxlength="7" pattern="#[0-9a-fA-F]{6}" spellcheck="false" @change="recordHistory()" aria-label="{{ __('sMailer::global.builder_button_text_color') }}">
                                            </span>
                                        </label>
                                        <label class="smailer-builder__block-setting-row">
                                            <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_button_radius') }}</span>
                                            <input class="evo-ui-input" type="number" min="0" max="48" step="1" x-model.number="block.buttonRadius" @change="recordHistory()">
                                        </label>
                                    </div>
                                </template>
                                <template x-if="isNavigationBlock(block)">
                                    <div class="smailer-builder__block-settings-group">
                                        <template x-for="(link, index) in block.navigationLinks" :key="`${block.id}-setting-link-${index}`">
                                            <div class="smailer-builder__block-settings-group">
                                                <label class="smailer-builder__block-setting-row">
                                                    <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_navigation_label') }}</span>
                                                    <input class="evo-ui-input" type="text" x-model="link.label" @change="recordHistory()">
                                                </label>
                                                <label class="smailer-builder__block-setting-row">
                                                    <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_navigation_url') }}</span>
                                                    <input class="evo-ui-input" type="url" placeholder="https://" x-model="link.url" @change="recordHistory()">
                                                </label>
                                                <button class="evo-ui-btn evo-ui-btn--danger" type="button" @click="removeNavigationLink(block, index)" :disabled="block.navigationLinks.length <= 1">{{ __('sMailer::global.builder_navigation_remove_item') }}</button>
                                            </div>
                                        </template>
                                        <button class="evo-ui-btn evo-ui-btn--primary" type="button" @click="addNavigationLink(block)"><x-evo::icon name="plus" /> {{ __('sMailer::global.builder_navigation_add_item') }}</button>
                                    </div>
                                </template>
                                <template x-if="isSocialBlock(block)">
                                    <div class="smailer-builder__block-settings-group">
                                        <template x-for="(link, index) in block.socialLinks" :key="`${block.id}-social-setting-${index}`">
                                            <div class="smailer-builder__block-settings-group">
                                                <label class="smailer-builder__block-setting-row">
                                                    <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_social_network') }}</span>
                                                    <select class="evo-ui-input" x-model="link.platform" @change="link.color = socialDefaultColor(link.platform); recordHistory()">
                                                        <template x-for="platform in socialPlatforms()" :key="platform"><option :value="platform" x-text="platform"></option></template>
                                                    </select>
                                                </label>
                                                <label class="smailer-builder__block-setting-row">
                                                    <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_social_url') }}</span>
                                                    <input class="evo-ui-input" type="url" placeholder="https://" x-model="link.url" @change="recordHistory()">
                                                </label>
                                                <label class="smailer-builder__block-setting-row">
                                                    <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_social_color') }}</span>
                                                    <span class="smailer-builder__color-control"><input class="evo-ui-input" type="color" x-model="link.color" @change="recordHistory()"><input class="evo-ui-input" type="text" x-model="link.color" maxlength="7" pattern="#[0-9a-fA-F]{6}" spellcheck="false" @change="recordHistory()"></span>
                                                </label>
                                                <button class="evo-ui-btn evo-ui-btn--danger" type="button" @click="removeSocialLink(block, index)">{{ __('sMailer::global.builder_social_remove_item') }}</button>
                                            </div>
                                        </template>
                                        <button class="evo-ui-btn evo-ui-btn--primary" type="button" @click="addSocialLink(block)"><x-evo::icon name="plus" /> {{ __('sMailer::global.builder_social_add_item') }}</button>
                                        <label class="smailer-builder__block-setting-row">
                                            <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_social_size') }}</span>
                                            <input class="evo-ui-input" type="number" min="16" max="56" step="1" x-model.number="block.socialSize" @change="block.socialSize = Math.min(Math.max(Number(block.socialSize) || 28, 16), 56); recordHistory()">
                                        </label>
                                    </div>
                                </template>
                                <template x-if="isUnsubscribeBlock(block)">
                                    <div class="smailer-builder__block-settings-group">
                                        <label class="smailer-builder__block-setting-row"><span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_unsubscribe_text') }}</span><input class="evo-ui-input" type="text" x-model="block.unsubscribeText" @change="recordHistory()"></label>
                                    </div>
                                </template>
                                <template x-if="isProductBlock(block)">
                                    <div class="smailer-builder__block-settings-group">
                                        <label class="smailer-builder__block-setting-row">
                                            <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_product_limit') }}</span>
                                            <input class="evo-ui-input" type="number" min="1" max="12" step="1" x-model.number="block.productLimit" @change="block.productLimit = Math.min(Math.max(Number(block.productLimit) || 1, 1), 12); recordHistory()">
                                        </label>
                                        <label class="smailer-builder__block-setting-row">
                                            <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_product_selection_mode') }}</span>
                                            <select class="evo-ui-input" x-model="block.productSelectionMode" @change="recordHistory()">
                                                <option value="ids">{{ __('sMailer::global.builder_product_selection_ids') }}</option>
                                                <option value="filters">{{ __('sMailer::global.builder_product_selection_filters') }}</option>
                                            </select>
                                        </label>
                                        <template x-if="block.productSelectionMode === 'ids'">
                                            <label class="smailer-builder__block-setting-row">
                                                <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_product_ids') }}</span>
                                                <input class="evo-ui-input" type="text" placeholder="12, 15, 42" x-model="block.productIds" @change="recordHistory()">
                                            </label>
                                        </template>
                                        <template x-if="block.productSelectionMode === 'filters'">
                                            <div class="smailer-builder__block-settings-group">
                                                <label class="smailer-builder__block-setting-row">
                                                    <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_product_source') }}</span>
                                                    <select class="evo-ui-input" x-model="block.productFilter" @change="recordHistory()">
                                                        <option value="all">{{ __('sMailer::global.builder_product_source_all') }}</option>
                                                        <option value="category">{{ __('sMailer::global.builder_product_source_category') }}</option>
                                                        <option value="attribute">{{ __('sMailer::global.builder_product_source_attribute') }}</option>
                                                    </select>
                                                </label>
                                                <template x-if="block.productFilter === 'category'">
                                                    <label class="smailer-builder__block-setting-row">
                                                        <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_product_category') }}</span>
                                                        <input class="evo-ui-input" type="text" x-model="block.productCategoryId" @change="recordHistory()">
                                                    </label>
                                                </template>
                                                <template x-if="block.productFilter === 'attribute'">
                                                <label class="smailer-builder__block-setting-row">
                                                    <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_product_attribute_alias') }}</span>
                                                    <input class="evo-ui-input" type="text" x-model="block.productAttributeAlias" @change="recordHistory()">
                                                </label>
                                                <label class="smailer-builder__block-setting-row">
                                                    <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_product_attribute_value') }}</span>
                                                    <input class="evo-ui-input" type="text" x-model="block.productAttributeValue" @change="recordHistory()">
                                                </label>
                                                </template>
                                                <label class="smailer-builder__block-setting-row">
                                                    <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_product_sort') }}</span>
                                                    <select class="evo-ui-input" x-model="block.productSort" @change="recordHistory()">
                                                        <option value="newest">{{ __('sMailer::global.builder_product_sort_newest') }}</option>
                                                        <option value="oldest">{{ __('sMailer::global.builder_product_sort_oldest') }}</option>
                                                        <option value="title_asc">{{ __('sMailer::global.builder_product_sort_title_asc') }}</option>
                                                        <option value="title_desc">{{ __('sMailer::global.builder_product_sort_title_desc') }}</option>
                                                        <option value="price_asc">{{ __('sMailer::global.builder_product_sort_price_asc') }}</option>
                                                        <option value="price_desc">{{ __('sMailer::global.builder_product_sort_price_desc') }}</option>
                                                    </select>
                                                </label>
                                            </div>
                                        </template>
                                        <label class="smailer-builder__block-setting-row">
                                            <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_product_availability') }}</span>
                                            <select class="evo-ui-input" x-model="block.productAvailability" @change="recordHistory()">
                                                <option value="available">{{ __('sMailer::global.builder_product_availability_all') }}</option>
                                                <option value="in_stock">{{ __('sMailer::global.builder_product_availability_in_stock') }}</option>
                                                <option value="in_stock_or_order">{{ __('sMailer::global.builder_product_availability_in_stock_or_order') }}</option>
                                            </select>
                                        </label>
                                        <button class="evo-ui-btn evo-ui-btn--primary" type="button" @click="refreshProductPreview(block)"><x-evo::icon name="refresh" /> {{ __('sMailer::global.builder_product_refresh') }}</button>
                                        <label class="smailer-builder__block-setting-row">
                                            <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_block_padding') }}</span>
                                            <input class="evo-ui-input" type="number" min="0" max="120" step="1" x-model.number="block.padding" @change="recordHistory()">
                                        </label>
                                        <div class="smailer-builder__block-setting-row">
                                            <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_product_columns') }}</span>
                                            <div class="smailer-builder__alignment-toggle">
                                                <button class="evo-ui-btn evo-ui-btn--icon" type="button" :class="{ 'is-active': block.productColumns === 1 }" @click="block.productColumns = 1; recordHistory()" title="{{ __('sMailer::global.builder_product_one_column') }}" aria-label="{{ __('sMailer::global.builder_product_one_column') }}"><x-evo::icon name="columns-1" /></button>
                                                <button class="evo-ui-btn evo-ui-btn--icon" type="button" :class="{ 'is-active': block.productColumns === 2 }" @click="block.productColumns = 2; recordHistory()" title="{{ __('sMailer::global.builder_layout_two_columns') }}" aria-label="{{ __('sMailer::global.builder_layout_two_columns') }}"><x-evo::icon name="columns-2" /></button>
                                                <button class="evo-ui-btn evo-ui-btn--icon" type="button" :class="{ 'is-active': block.productColumns === 3 }" @click="block.productColumns = 3; recordHistory()" title="{{ __('sMailer::global.builder_layout_three_columns') }}" aria-label="{{ __('sMailer::global.builder_layout_three_columns') }}"><x-evo::icon name="columns-3" /></button>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="isVideoBlock(block)">
                                    <div class="smailer-builder__block-settings-group">
                                        <label class="smailer-builder__block-setting-row">
                                            <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_video_url') }}</span>
                                            <input class="evo-ui-input" type="url" placeholder="https://" x-model="block.videoUrl" @change="recordHistory()">
                                        </label>
                                        <div class="smailer-builder__image-picker-row">
                                            <button class="evo-ui-btn evo-ui-btn--icon evo-ui-btn--primary" type="button" @click="openVideoThumbnailLibrary()" title="{{ __('sMailer::global.builder_video_thumbnail') }}" aria-label="{{ __('sMailer::global.builder_video_thumbnail') }}"><x-evo::icon name="photo-plus" /></button>
                                            <span class="smailer-builder__image-picker-name" x-text="block.videoThumbnailFileName || @js(__('sMailer::global.builder_video_thumbnail_auto'))"></span>
                                            <button class="evo-ui-btn evo-ui-btn--icon evo-ui-btn--danger" type="button" x-show="block.videoThumbnailSrc" @click="removeVideoThumbnail(block)" title="{{ __('sMailer::global.builder_image_remove') }}" aria-label="{{ __('sMailer::global.builder_image_remove') }}"><x-evo::icon name="trash" /></button>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="isLayoutBlock(block)">
                                    <div class="smailer-builder__block-settings-group">
                                        <div class="smailer-builder__block-setting-row" x-show="!selectedLayoutChild">
                                            <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_layout_columns') }}</span>
                                            <div class="smailer-builder__alignment-toggle">
                                                <button class="evo-ui-btn evo-ui-btn--icon" type="button" :class="{ 'is-active': block.columns.length === 2 }" @click="setLayoutColumns(block, 2)" title="{{ __('sMailer::global.builder_layout_two_columns') }}" aria-label="{{ __('sMailer::global.builder_layout_two_columns') }}"><x-evo::icon name="columns-2" /></button>
                                                <button class="evo-ui-btn evo-ui-btn--icon" type="button" :class="{ 'is-active': block.columns.length === 3 }" @click="setLayoutColumns(block, 3)" title="{{ __('sMailer::global.builder_layout_three_columns') }}" aria-label="{{ __('sMailer::global.builder_layout_three_columns') }}"><x-evo::icon name="columns-3" /></button>
                                            </div>
                                        </div>
                                        <template x-if="selectedLayoutChild && isVideoBlock(selectedLayoutChild)">
                                            <div class="smailer-builder__block-settings-group">
                                                <label class="smailer-builder__block-setting-row">
                                                    <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_video_url') }}</span>
                                                    <input class="evo-ui-input" type="url" placeholder="https://" x-model="selectedLayoutChild.videoUrl" @change="recordHistory()">
                                                </label>
                                                <div class="smailer-builder__image-picker-row">
                                                    <button class="evo-ui-btn evo-ui-btn--icon evo-ui-btn--primary" type="button" @click="openVideoThumbnailLibrary()" title="{{ __('sMailer::global.builder_video_thumbnail') }}" aria-label="{{ __('sMailer::global.builder_video_thumbnail') }}"><x-evo::icon name="photo-plus" /></button>
                                                    <span class="smailer-builder__image-picker-name" x-text="selectedLayoutChild.videoThumbnailFileName || @js(__('sMailer::global.builder_video_thumbnail_auto'))"></span>
                                                    <button class="evo-ui-btn evo-ui-btn--icon evo-ui-btn--danger" type="button" x-show="selectedLayoutChild.videoThumbnailSrc" @click="removeVideoThumbnail(selectedLayoutChild)" title="{{ __('sMailer::global.builder_image_remove') }}" aria-label="{{ __('sMailer::global.builder_image_remove') }}"><x-evo::icon name="trash" /></button>
                                                </div>
                                            </div>
                                        </template>
                                        <template x-if="selectedLayoutChild && isImageBlock(selectedLayoutChild)">
                                            <div class="smailer-builder__block-settings-group">
                                                <div class="smailer-builder__image-picker-row">
                                                    <button class="evo-ui-btn evo-ui-btn--icon evo-ui-btn--primary" type="button" @click="openImageLibrary()" title="{{ __('sMailer::global.builder_image_select') }}" aria-label="{{ __('sMailer::global.builder_image_select') }}"><x-evo::icon name="photo-plus" /></button>
                                                    <span class="smailer-builder__image-picker-name" x-text="selectedLayoutChild.imageFileName || @js(__('sMailer::global.builder_image_no_file'))"></span>
                                                    <button class="evo-ui-btn evo-ui-btn--icon evo-ui-btn--danger" type="button" x-show="selectedLayoutChild.imageSrc" @click="removeImage(selectedLayoutChild)" title="{{ __('sMailer::global.builder_image_remove') }}" aria-label="{{ __('sMailer::global.builder_image_remove') }}"><x-evo::icon name="trash" /></button>
                                                </div>
                                                <label class="smailer-builder__block-setting-row">
                                                    <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_image_alt') }}</span>
                                                    <input class="evo-ui-input" type="text" x-model="selectedLayoutChild.imageAlt" @change="recordHistory()">
                                                </label>
                                                <label class="smailer-builder__block-setting-row">
                                                    <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_image_link') }}</span>
                                                    <input class="evo-ui-input" type="url" placeholder="https://" x-model="selectedLayoutChild.imageLink" @change="recordHistory()">
                                                </label>
                                                <label class="smailer-builder__block-setting-row">
                                                    <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_image_width') }}</span>
                                                    <span class="smailer-builder__image-size-control">
                                                        <input class="evo-ui-input" type="number" min="1" :max="selectedLayoutChild.imageWidthUnit === '%' ? 100 : 600" step="1" x-model.number="selectedLayoutChild.imageWidth" @change="recordHistory()">
                                                        <select class="evo-ui-input" :value="selectedLayoutChild.imageWidthUnit" @change="setImageWidthUnit(selectedLayoutChild, $event.target.value)" aria-label="{{ __('sMailer::global.builder_image_width') }}">
                                                            <option value="%">%</option>
                                                            <option value="px">px</option>
                                                        </select>
                                                    </span>
                                                </label>
                                            </div>
                                        </template>
                                        <template x-if="selectedLayoutChild && isTitleBlock(selectedLayoutChild)">
                                            <div class="smailer-builder__block-settings-group">
                                                <label class="smailer-builder__block-setting-row">
                                                    <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_title_level') }}</span>
                                                    <select class="evo-ui-input" :value="selectedLayoutChild.titleLevel" @change="setTitleLevel(selectedLayoutChild, $event.target.value)">
                                                        <option value="p">{{ __('sMailer::global.builder_title_level_paragraph') }}</option>
                                                        @foreach(['h1', 'h2', 'h3', 'h4', 'h5', 'h6'] as $level)
                                                            <option value="{{ $level }}">{{ strtoupper($level) }}</option>
                                                        @endforeach
                                                    </select>
                                                </label>
                                                <label class="smailer-builder__block-setting-row">
                                                    <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_title_size') }}</span>
                                                    <input class="evo-ui-input" type="number" min="8" max="96" step="1" x-model.number="selectedLayoutChild.fontSize" @change="recordHistory()">
                                                </label>
                                            </div>
                                        </template>
                                        <template x-if="selectedLayoutChild && isDividerBlock(selectedLayoutChild)">
                                            <div class="smailer-builder__block-settings-group">
                                                <label class="smailer-builder__block-setting-row">
                                                    <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_divider_color') }}</span>
                                                    <input class="evo-ui-input" type="color" x-model="selectedLayoutChild.dividerColor" @change="recordHistory()">
                                                </label>
                                                <label class="smailer-builder__block-setting-row">
                                                    <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_divider_thickness') }}</span>
                                                    <input class="evo-ui-input" type="number" min="1" max="12" step="1" x-model.number="selectedLayoutChild.dividerThickness" @change="recordHistory()">
                                                </label>
                                            </div>
                                        </template>
                                        <template x-if="selectedLayoutChild && isButtonBlock(selectedLayoutChild)">
                                            <div class="smailer-builder__block-settings-group">
                                                <label class="smailer-builder__block-setting-row">
                                                    <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_button_text') }}</span>
                                                    <input class="evo-ui-input" type="text" x-model="selectedLayoutChild.buttonText" @change="recordHistory()">
                                                </label>
                                                <label class="smailer-builder__block-setting-row">
                                                    <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_button_link') }}</span>
                                                    <input class="evo-ui-input" type="url" placeholder="https://" x-model="selectedLayoutChild.buttonLink" @change="recordHistory()">
                                                </label>
                                            </div>
                                        </template>
                                        <template x-if="selectedLayoutChild && isNavigationBlock(selectedLayoutChild)">
                                            <div class="smailer-builder__block-settings-group">
                                                <template x-for="(link, index) in selectedLayoutChild.navigationLinks" :key="`${selectedLayoutChild.id}-setting-link-${index}`">
                                                    <div class="smailer-builder__block-settings-group">
                                                        <label class="smailer-builder__block-setting-row">
                                                            <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_navigation_label') }}</span>
                                                            <input class="evo-ui-input" type="text" x-model="link.label" @change="recordHistory()">
                                                        </label>
                                                        <label class="smailer-builder__block-setting-row">
                                                            <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_navigation_url') }}</span>
                                                            <input class="evo-ui-input" type="url" placeholder="https://" x-model="link.url" @change="recordHistory()">
                                                        </label>
                                                        <button class="evo-ui-btn evo-ui-btn--danger" type="button" @click="removeNavigationLink(selectedLayoutChild, index)" :disabled="selectedLayoutChild.navigationLinks.length <= 1">{{ __('sMailer::global.builder_navigation_remove_item') }}</button>
                                                    </div>
                                                </template>
                                                <button class="evo-ui-btn evo-ui-btn--primary" type="button" @click="addNavigationLink(selectedLayoutChild)"><x-evo::icon name="plus" /> {{ __('sMailer::global.builder_navigation_add_item') }}</button>
                                            </div>
                                        </template>
                                        <template x-if="selectedLayoutChild && isSocialBlock(selectedLayoutChild)">
                                            <div class="smailer-builder__block-settings-group">
                                                <template x-for="(link, index) in selectedLayoutChild.socialLinks" :key="`${selectedLayoutChild.id}-social-setting-${index}`">
                                                    <div class="smailer-builder__block-settings-group">
                                                        <label class="smailer-builder__block-setting-row"><span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_social_network') }}</span><select class="evo-ui-input" x-model="link.platform" @change="link.color = socialDefaultColor(link.platform); recordHistory()"><template x-for="platform in socialPlatforms()" :key="platform"><option :value="platform" x-text="platform"></option></template></select></label>
                                                        <label class="smailer-builder__block-setting-row"><span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_social_url') }}</span><input class="evo-ui-input" type="url" placeholder="https://" x-model="link.url" @change="recordHistory()"></label>
                                                        <label class="smailer-builder__block-setting-row"><span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_social_color') }}</span><span class="smailer-builder__color-control"><input class="evo-ui-input" type="color" x-model="link.color" @change="recordHistory()"><input class="evo-ui-input" type="text" x-model="link.color" maxlength="7" pattern="#[0-9a-fA-F]{6}" spellcheck="false" @change="recordHistory()"></span></label>
                                                        <button class="evo-ui-btn evo-ui-btn--danger" type="button" @click="removeSocialLink(selectedLayoutChild, index)">{{ __('sMailer::global.builder_social_remove_item') }}</button>
                                                    </div>
                                                </template>
                                                <button class="evo-ui-btn evo-ui-btn--primary" type="button" @click="addSocialLink(selectedLayoutChild)"><x-evo::icon name="plus" /> {{ __('sMailer::global.builder_social_add_item') }}</button>
                                                <label class="smailer-builder__block-setting-row"><span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_social_size') }}</span><input class="evo-ui-input" type="number" min="16" max="56" step="1" x-model.number="selectedLayoutChild.socialSize" @change="selectedLayoutChild.socialSize = Math.min(Math.max(Number(selectedLayoutChild.socialSize) || 28, 16), 56); recordHistory()"></label>
                                            </div>
                                        </template>
                                        <template x-if="selectedLayoutChild && isUnsubscribeBlock(selectedLayoutChild)">
                                            <div class="smailer-builder__block-settings-group"><label class="smailer-builder__block-setting-row"><span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_unsubscribe_text') }}</span><input class="evo-ui-input" type="text" x-model="selectedLayoutChild.unsubscribeText" @change="recordHistory()"></label></div>
                                        </template>
                                        <template x-if="selectedLayoutChild && isProductBlock(selectedLayoutChild)">
                                            <div class="smailer-builder__block-settings-group">
                                                <label class="smailer-builder__block-setting-row">
                                                    <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_product_limit') }}</span>
                                                    <input class="evo-ui-input" type="number" min="1" max="12" step="1" x-model.number="selectedLayoutChild.productLimit" @change="selectedLayoutChild.productLimit = Math.min(Math.max(Number(selectedLayoutChild.productLimit) || 1, 1), 12); recordHistory()">
                                                </label>
                                                <label class="smailer-builder__block-setting-row">
                                                    <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_product_selection_mode') }}</span>
                                                    <select class="evo-ui-input" x-model="selectedLayoutChild.productSelectionMode" @change="recordHistory()">
                                                        <option value="ids">{{ __('sMailer::global.builder_product_selection_ids') }}</option>
                                                        <option value="filters">{{ __('sMailer::global.builder_product_selection_filters') }}</option>
                                                    </select>
                                                </label>
                                                <template x-if="selectedLayoutChild.productSelectionMode === 'ids'">
                                                    <label class="smailer-builder__block-setting-row">
                                                        <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_product_ids') }}</span>
                                                        <input class="evo-ui-input" type="text" placeholder="12, 15, 42" x-model="selectedLayoutChild.productIds" @change="recordHistory()">
                                                    </label>
                                                </template>
                                                <template x-if="selectedLayoutChild.productSelectionMode === 'filters'">
                                                    <div class="smailer-builder__block-settings-group">
                                                        <label class="smailer-builder__block-setting-row">
                                                            <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_product_source') }}</span>
                                                            <select class="evo-ui-input" x-model="selectedLayoutChild.productFilter" @change="recordHistory()">
                                                                <option value="all">{{ __('sMailer::global.builder_product_source_all') }}</option>
                                                                <option value="category">{{ __('sMailer::global.builder_product_source_category') }}</option>
                                                                <option value="attribute">{{ __('sMailer::global.builder_product_source_attribute') }}</option>
                                                            </select>
                                                        </label>
                                                        <template x-if="selectedLayoutChild.productFilter === 'category'">
                                                            <label class="smailer-builder__block-setting-row">
                                                                <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_product_category') }}</span>
                                                                <input class="evo-ui-input" type="text" x-model="selectedLayoutChild.productCategoryId" @change="recordHistory()">
                                                            </label>
                                                        </template>
                                                        <template x-if="selectedLayoutChild.productFilter === 'attribute'">
                                                        <label class="smailer-builder__block-setting-row">
                                                            <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_product_attribute_alias') }}</span>
                                                            <input class="evo-ui-input" type="text" x-model="selectedLayoutChild.productAttributeAlias" @change="recordHistory()">
                                                        </label>
                                                        <label class="smailer-builder__block-setting-row">
                                                            <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_product_attribute_value') }}</span>
                                                            <input class="evo-ui-input" type="text" x-model="selectedLayoutChild.productAttributeValue" @change="recordHistory()">
                                                        </label>
                                                        </template>
                                                        <label class="smailer-builder__block-setting-row">
                                                            <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_product_sort') }}</span>
                                                            <select class="evo-ui-input" x-model="selectedLayoutChild.productSort" @change="recordHistory()">
                                                                <option value="newest">{{ __('sMailer::global.builder_product_sort_newest') }}</option>
                                                                <option value="oldest">{{ __('sMailer::global.builder_product_sort_oldest') }}</option>
                                                                <option value="title_asc">{{ __('sMailer::global.builder_product_sort_title_asc') }}</option>
                                                                <option value="title_desc">{{ __('sMailer::global.builder_product_sort_title_desc') }}</option>
                                                                <option value="price_asc">{{ __('sMailer::global.builder_product_sort_price_asc') }}</option>
                                                                <option value="price_desc">{{ __('sMailer::global.builder_product_sort_price_desc') }}</option>
                                                            </select>
                                                        </label>
                                                    </div>
                                                </template>
                                                <label class="smailer-builder__block-setting-row">
                                                    <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_product_availability') }}</span>
                                                    <select class="evo-ui-input" x-model="selectedLayoutChild.productAvailability" @change="recordHistory()">
                                                        <option value="available">{{ __('sMailer::global.builder_product_availability_all') }}</option>
                                                        <option value="in_stock">{{ __('sMailer::global.builder_product_availability_in_stock') }}</option>
                                                        <option value="in_stock_or_order">{{ __('sMailer::global.builder_product_availability_in_stock_or_order') }}</option>
                                                    </select>
                                                </label>
                                                <button class="evo-ui-btn evo-ui-btn--primary" type="button" @click="refreshProductPreview(selectedLayoutChild)"><x-evo::icon name="refresh" /> {{ __('sMailer::global.builder_product_refresh') }}</button>
                                                <label class="smailer-builder__block-setting-row">
                                                    <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_block_padding') }}</span>
                                                    <input class="evo-ui-input" type="number" min="0" max="120" step="1" x-model.number="selectedLayoutChild.padding" @change="recordHistory()">
                                                </label>
                                                <div class="smailer-builder__block-setting-row">
                                                    <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_product_columns') }}</span>
                                                    <div class="smailer-builder__alignment-toggle">
                                                        <button class="evo-ui-btn evo-ui-btn--icon" type="button" :class="{ 'is-active': selectedLayoutChild.productColumns === 1 }" @click="selectedLayoutChild.productColumns = 1; recordHistory()" title="{{ __('sMailer::global.builder_product_one_column') }}" aria-label="{{ __('sMailer::global.builder_product_one_column') }}"><x-evo::icon name="columns-1" /></button>
                                                        <button class="evo-ui-btn evo-ui-btn--icon" type="button" :class="{ 'is-active': selectedLayoutChild.productColumns === 2 }" @click="selectedLayoutChild.productColumns = 2; recordHistory()" title="{{ __('sMailer::global.builder_layout_two_columns') }}" aria-label="{{ __('sMailer::global.builder_layout_two_columns') }}"><x-evo::icon name="columns-2" /></button>
                                                        <button class="evo-ui-btn evo-ui-btn--icon" type="button" :class="{ 'is-active': selectedLayoutChild.productColumns === 3 }" @click="selectedLayoutChild.productColumns = 3; recordHistory()" title="{{ __('sMailer::global.builder_layout_three_columns') }}" aria-label="{{ __('sMailer::global.builder_layout_three_columns') }}"><x-evo::icon name="columns-3" /></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                        <template x-if="selectedLayoutChild && !isImageBlock(selectedLayoutChild) && !isDividerBlock(selectedLayoutChild) && !isProductBlock(selectedLayoutChild)">
                                            <label class="smailer-builder__block-setting-row">
                                                <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_block_padding') }}</span>
                                                <input class="evo-ui-input" type="number" min="0" max="120" step="1" x-model.number="selectedLayoutChild.padding" @change="recordHistory()">
                                            </label>
                                        </template>
                                    </div>
                                </template>
                                <div class="smailer-builder__block-setting-row" x-show="!isDividerBlock(block) && !isLayoutBlock(block)">
                                    <span class="smailer-builder__block-setting-label">{{ __('sMailer::global.builder_block_alignment') }}</span>
                                    <div class="smailer-builder__alignment-toggle">
                                        <button class="evo-ui-btn evo-ui-btn--icon" type="button" :class="{ 'is-active': block.align === 'left' }" @click="block.align = 'left'; recordHistory()" title="{{ __('sMailer::global.builder_alignment_left') }}" aria-label="{{ __('sMailer::global.builder_alignment_left') }}"><x-evo::icon name="align-left" /></button>
                                        <button class="evo-ui-btn evo-ui-btn--icon" type="button" :class="{ 'is-active': block.align === 'center' }" @click="block.align = 'center'; recordHistory()" title="{{ __('sMailer::global.builder_alignment_center') }}" aria-label="{{ __('sMailer::global.builder_alignment_center') }}"><x-evo::icon name="align-center" /></button>
                                        <button class="evo-ui-btn evo-ui-btn--icon" type="button" :class="{ 'is-active': block.align === 'right' }" @click="block.align = 'right'; recordHistory()" title="{{ __('sMailer::global.builder_alignment_right') }}" aria-label="{{ __('sMailer::global.builder_alignment_right') }}"><x-evo::icon name="align-right" /></button>
                                        <button class="evo-ui-btn evo-ui-btn--icon" type="button" :class="{ 'is-active': block.align === 'justify' }" @click="block.align = 'justify'; recordHistory()" title="{{ __('sMailer::global.builder_alignment_justify') }}" aria-label="{{ __('sMailer::global.builder_alignment_justify') }}"><x-evo::icon name="align-justified" /></button>
                                    </div>
                                </div>
                                <template x-if="isImageBlock(block)">
                                    <div class="smailer-builder__block-settings-group">
                                        <div class="smailer-builder__image-padding-grid">
                                            <label>{{ __('sMailer::global.builder_image_padding_top') }}<input class="evo-ui-input" type="number" min="0" max="120" step="1" x-model.number="block.paddingTop"></label>
                                            <label>{{ __('sMailer::global.builder_image_padding_bottom') }}<input class="evo-ui-input" type="number" min="0" max="120" step="1" x-model.number="block.paddingBottom"></label>
                                            <label>{{ __('sMailer::global.builder_image_padding_left') }}<input class="evo-ui-input" type="number" min="0" max="120" step="1" x-model.number="block.paddingLeft"></label>
                                            <label>{{ __('sMailer::global.builder_image_padding_right') }}<input class="evo-ui-input" type="number" min="0" max="120" step="1" x-model.number="block.paddingRight"></label>
                                        </div>
                                        <label class="smailer-builder__image-toggle"><input type="checkbox" x-model="block.imageResponsive"> {{ __('sMailer::global.builder_image_responsive') }}</label>
                                    </div>
                                </template>
                            </div>
                        </article>
                </template>
            </article>
        </div>
    </div>
</section>
