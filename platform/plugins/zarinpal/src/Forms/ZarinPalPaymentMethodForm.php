<?php

namespace Botble\ZarinPal\Forms;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Payment\Concerns\Forms\HasAvailableCountriesField;
use Botble\Payment\Forms\PaymentMethodForm;

class ZarinPalPaymentMethodForm extends PaymentMethodForm
{
    use HasAvailableCountriesField;

    public function setup(): void
    {
        parent::setup();

        $this
            ->paymentId(ZARINPAL_PAYMENT_METHOD_NAME)
            ->paymentName('ZarinPal')
            ->paymentDescription(trans('plugins/zarinpal::zarinpal.description'))
            ->paymentLogo(url('vendor/core/plugins/zarinpal/images/zarinpal.svg'))
            ->paymentFeeField(ZARINPAL_PAYMENT_METHOD_NAME)
            ->paymentUrl('https://www.zarinpal.com')
            ->defaultDescriptionValue(trans('plugins/zarinpal::zarinpal.redirect_message', ['name' => 'ZarinPal']))
            ->paymentInstructions(view('plugins/zarinpal::instructions')->render())
            ->add(
                sprintf('payment_%s_merchant_id', ZARINPAL_PAYMENT_METHOD_NAME),
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/zarinpal::zarinpal.merchant_id'))
                    ->placeholder('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx')
                    ->helperText(trans('plugins/zarinpal::zarinpal.merchant_id_helper'))
                    ->value(BaseHelper::hasDemoModeEnabled() ? '*******************************' : get_payment_setting('merchant_id', ZARINPAL_PAYMENT_METHOD_NAME))
            )
            ->add(
                sprintf('payment_%s_mode', ZARINPAL_PAYMENT_METHOD_NAME),
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(trans('plugins/payment::payment.live_mode'))
                    ->helperText(trans('plugins/zarinpal::zarinpal.mode_helper'))
                    ->value(get_payment_setting('mode', ZARINPAL_PAYMENT_METHOD_NAME, true))
            )
            ->add(
                sprintf('payment_%s_currency', ZARINPAL_PAYMENT_METHOD_NAME),
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/zarinpal::zarinpal.currency_unit'))
                    ->helperText(trans('plugins/zarinpal::zarinpal.currency_unit_helper'))
                    ->choices([
                        'IRT' => trans('plugins/zarinpal::zarinpal.toman'),
                        'IRR' => trans('plugins/zarinpal::zarinpal.rial'),
                    ])
                    ->selected(get_payment_setting('currency', ZARINPAL_PAYMENT_METHOD_NAME, 'IRT'))
            )
            ->addAvailableCountriesField(ZARINPAL_PAYMENT_METHOD_NAME);
    }
}
