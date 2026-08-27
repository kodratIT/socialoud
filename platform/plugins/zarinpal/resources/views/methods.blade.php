@if (setting('payment_zarinpal_status') == 1)
    <x-plugins-payment::payment-method
        :name="ZARINPAL_PAYMENT_METHOD_NAME"
        paymentName="ZarinPal"
        :supportedCurrencies="[strtoupper(get_application_currency()->title)]"
    >
        <x-slot name="currencyNotSupportedMessage">
            <p class="mt-1 mb-0">
                {{ trans('plugins/zarinpal::zarinpal.learn_more') }}:
                {{ Html::link('https://www.zarinpal.com/docs/paymentGateway/moreFeatures/currency', attributes: ['target' => '_blank', 'rel' => 'nofollow']) }}.
            </p>
        </x-slot>
    </x-plugins-payment::payment-method>
@endif
