<?php namespace Seiger\sMailer\Controllers;

use Illuminate\Contracts\View\View;

/**
 * Build the sMailer manager module shell.
 *
 * The controller keeps Evolution's module entry thin and passes only navigation
 * context into the package-owned EvoUI component.
 *
 * @since 2.0.0
 */
class ModuleController
{
    /**
     * Render the installable 2.x manager foundation.
     *
     * @return View
     * @since 2.0.0
     */
    public function index(): View
    {
        $tabs = [
            [
                'key' => 'overview',
                'label' => __('sMailer::global.overview'),
                'icon' => 'layout-dashboard',
            ],
        ];

        return view('sMailer::module.shell', [
            'moduleTitle' => __('sMailer::global.module_title'),
            'tabs' => $tabs,
            'activeTab' => 'overview',
            'context' => [
                'moduleUrl' => (string)request()->fullUrl(),
            ],
        ]);
    }
}
