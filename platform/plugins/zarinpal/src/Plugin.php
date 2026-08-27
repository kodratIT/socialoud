<?php

namespace Botble\ZarinPal;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Botble\Setting\Facades\Setting;

class Plugin extends PluginOperationAbstract
{
    public static function remove(): void
    {
        Setting::delete([
            'payment_zarinpal_name',
            'payment_zarinpal_description',
            'payment_zarinpal_merchant_id',
            'payment_zarinpal_mode',
            'payment_zarinpal_currency',
            'payment_zarinpal_status',
        ]);
    }
}
