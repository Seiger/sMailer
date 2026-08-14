<?php

use Illuminate\Support\Facades\Route;
use Seiger\sMailer\Controllers\SubscriptionController;

Route::middleware('web')->prefix('smailer')->name('smailer.')->group(function (): void {
    Route::post('subscribe', [SubscriptionController::class, 'store'])->name('subscribe');
});
