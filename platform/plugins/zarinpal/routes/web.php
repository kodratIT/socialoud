<?php

use Botble\Theme\Facades\Theme;
use Botble\ZarinPal\Http\Controllers\ZarinPalController;
use Illuminate\Support\Facades\Route;

Theme::registerRoutes(function (): void {
    Route::get('payment/zarinpal/status', [ZarinPalController::class, 'getCallback'])
        ->name('payments.zarinpal.status');
});
