@if ($payment)
    <div class="my-3">
        <div
            class="alert alert-success"
            role="alert"
        >
            <p class="mb-2">{{ trans('plugins/zarinpal::zarinpal.ref_id') }}: <strong>{{ Arr::get($payment, 'ref_id') }}</strong></p>

            <p class="mb-2">
                {{ trans('plugins/payment::payment.amount') }}:
                <strong>{{ Arr::get($payment, 'amount') }} {{ Arr::get($payment, 'currency') }}</strong>
            </p>

            @if (Arr::get($payment, 'card_pan'))
                <p class="mb-2">{{ trans('plugins/zarinpal::zarinpal.card_pan') }}: {{ Arr::get($payment, 'card_pan') }}</p>
            @endif

            @if (Arr::get($payment, 'fee') !== null)
                <p class="mb-2">{{ trans('plugins/zarinpal::zarinpal.fee') }}: {{ Arr::get($payment, 'fee') }}</p>
            @endif

            <p class="mb-0">{{ trans('plugins/zarinpal::zarinpal.authority') }}: {{ Arr::get($payment, 'authority') }}</p>
        </div>

        @include('plugins/payment::partials.view-payment-source')
    </div>
@endif
