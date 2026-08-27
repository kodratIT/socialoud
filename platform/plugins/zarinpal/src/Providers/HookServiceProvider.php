<?php

namespace Botble\ZarinPal\Providers;

use Botble\Base\Facades\Html;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Payment\Facades\PaymentMethods;
use Botble\ZarinPal\Forms\ZarinPalPaymentMethodForm;
use Botble\ZarinPal\Services\ZarinPalPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        add_filter(PAYMENT_FILTER_ADDITIONAL_PAYMENT_METHODS, [$this, 'registerZarinPalMethod'], 2, 2);

        $this->app->booted(function (): void {
            add_filter(PAYMENT_FILTER_AFTER_POST_CHECKOUT, [$this, 'checkoutWithZarinPal'], 2, 2);
        });

        add_filter(PAYMENT_METHODS_SETTINGS_PAGE, [$this, 'addPaymentSettings'], 2);

        add_filter(BASE_FILTER_ENUM_ARRAY, function ($values, $class) {
            if ($class == PaymentMethodEnum::class) {
                $values['ZARINPAL'] = ZARINPAL_PAYMENT_METHOD_NAME;
            }

            return $values;
        }, 2, 2);

        add_filter(BASE_FILTER_ENUM_LABEL, function ($value, $class) {
            if ($class == PaymentMethodEnum::class && $value == ZARINPAL_PAYMENT_METHOD_NAME) {
                $value = 'ZarinPal';
            }

            return $value;
        }, 2, 2);

        add_filter(BASE_FILTER_ENUM_HTML, function ($value, $class) {
            if ($class == PaymentMethodEnum::class && $value == ZARINPAL_PAYMENT_METHOD_NAME) {
                $value = Html::tag(
                    'span',
                    PaymentMethodEnum::getLabel($value),
                    ['class' => 'label-success status-label']
                )
                    ->toHtml();
            }

            return $value;
        }, 2, 2);

        add_filter(PAYMENT_FILTER_GET_SERVICE_CLASS, function ($data, $value) {
            if ($value == ZARINPAL_PAYMENT_METHOD_NAME) {
                $data = ZarinPalPaymentService::class;
            }

            return $data;
        }, 2, 2);

        add_filter(PAYMENT_FILTER_PAYMENT_INFO_DETAIL, function ($data, $payment) {
            if ($payment->payment_channel == ZARINPAL_PAYMENT_METHOD_NAME) {
                $paymentDetail = (new ZarinPalPaymentService())->getPaymentDetails($payment->charge_id);
                $data .= view('plugins/zarinpal::detail', ['payment' => $paymentDetail])->render();
            }

            return $data;
        }, 2, 2);
    }

    public function addPaymentSettings(?string $settings): string
    {
        return $settings . ZarinPalPaymentMethodForm::create()->renderForm();
    }

    public function registerZarinPalMethod(?string $html, array $data): string
    {
        PaymentMethods::method(ZARINPAL_PAYMENT_METHOD_NAME, [
            'html' => view('plugins/zarinpal::methods', $data)->render(),
        ]);

        return $html;
    }

    public function checkoutWithZarinPal(array $data, Request $request): array
    {
        if ($data['type'] !== ZARINPAL_PAYMENT_METHOD_NAME) {
            return $data;
        }

        $zarinPalService = $this->app->make(ZarinPalPaymentService::class);

        // Note: ZarinPal only ever receives the currency unit configured in the
        // plugin's own settings (Toman/Rial) - see ZarinPalPaymentService::configuredCurrency().
        // We don't block checkout based on the store's global currency title here,
        // since that title rarely matches the literal string "IRT"/"IRR".

        $paymentData = apply_filters(PAYMENT_FILTER_PAYMENT_DATA, [], $request);

        if (! $request->input('callback_url')) {
            $paymentData['callback_url'] = route('payments.zarinpal.status');
        }

        $checkoutUrl = $zarinPalService->execute($paymentData);

        if ($checkoutUrl) {
            $data['checkoutUrl'] = $checkoutUrl;
        } else {
            $data['error'] = true;
            $data['message'] = $zarinPalService->getErrorMessage();
        }

        return $data;
    }
}
