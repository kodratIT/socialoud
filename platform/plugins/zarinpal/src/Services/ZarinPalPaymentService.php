<?php

namespace Botble\ZarinPal\Services;

use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Services\Traits\PaymentErrorTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ZarinPalPaymentService
{
    use PaymentErrorTrait;

    protected const REQUEST_URL_LIVE = 'https://payment.zarinpal.com/pg/v4/payment/request.json';

    protected const REQUEST_URL_SANDBOX = 'https://sandbox.zarinpal.com/pg/v4/payment/request.json';

    protected const VERIFY_URL_LIVE = 'https://payment.zarinpal.com/pg/v4/payment/verify.json';

    protected const VERIFY_URL_SANDBOX = 'https://sandbox.zarinpal.com/pg/v4/payment/verify.json';

    protected const START_PAY_URL_LIVE = 'https://www.zarinpal.com/pg/StartPay/';

    protected const START_PAY_URL_SANDBOX = 'https://sandbox.zarinpal.com/pg/StartPay/';

    protected const CACHE_PREFIX = 'zarinpal_payment_detail_';

    public function execute(array $data): string|null|bool
    {
        try {
            return $this->makePayment($data);
        } catch (Exception $exception) {
            $this->setErrorMessageAndLogging($exception, 1);

            return false;
        }
    }

    /**
     * The currency unit ZarinPal will actually be told about, taken from the plugin's
     * own "Currency unit" setting (Toman/Rial) — NOT from the store's global currency
     * title, since that title rarely equals the literal string "IRT" or "IRR".
     * @see https://www.zarinpal.com/docs/paymentGateway/moreFeatures/currency
     */
    public function configuredCurrency(): string
    {
        $currency = strtoupper((string) setting('payment_zarinpal_currency', 'IRT'));

        return in_array($currency, ['IRT', 'IRR'], true) ? $currency : 'IRT';
    }

    /**
     * Kept for compatibility with the payment method UI component, which expects a list.
     * ZarinPal only ever uses the single currency configured in the plugin settings.
     */
    public function supportedCurrencyCodes(): array
    {
        return [$this->configuredCurrency()];
    }

    public function isSandboxMode(): bool
    {
        return ! (bool) setting('payment_zarinpal_mode');
    }

    protected function requestUrl(): string
    {
        return $this->isSandboxMode() ? self::REQUEST_URL_SANDBOX : self::REQUEST_URL_LIVE;
    }

    protected function verifyUrl(): string
    {
        return $this->isSandboxMode() ? self::VERIFY_URL_SANDBOX : self::VERIFY_URL_LIVE;
    }

    protected function startPayUrl(): string
    {
        return $this->isSandboxMode() ? self::START_PAY_URL_SANDBOX : self::START_PAY_URL_LIVE;
    }

    protected function merchantId(): ?string
    {
        return setting('payment_zarinpal_merchant_id');
    }

    /**
     * Create a payment request at ZarinPal and return the checkout (StartPay) URL.
     */
    public function makePayment(array $data): string|null|bool
    {
        $amount = (int) round((float) $data['amount']);
        // Currency label kept on the order/payment record, for bookkeeping only.
        $storeCurrency = strtoupper((string) $data['currency']);
        // Currency actually sent to ZarinPal - from the plugin's own setting.
        $gatewayCurrency = $this->configuredCurrency();

        $queryParams = [
            'type' => ZARINPAL_PAYMENT_METHOD_NAME,
            'amount' => $amount,
            'currency' => $storeCurrency,
            'order_id' => $data['order_id'],
            'customer_id' => Arr::get($data, 'customer_id'),
            'customer_type' => Arr::get($data, 'customer_type'),
        ];

        $callbackUrl = $data['callback_url'] . '?' . http_build_query($queryParams);

        $description = Str::limit((string) Arr::get($data, 'description', ''), 250, '');

        $payload = array_filter([
            'merchant_id' => $this->merchantId(),
            'amount' => $amount,
            'currency' => $gatewayCurrency,
            'callback_url' => $callbackUrl,
            'description' => $description ?: 'Order payment',
        ]);

        $metadata = array_filter([
            'mobile' => Arr::get($data, 'address.phone'),
            'email' => Arr::get($data, 'address.email'),
        ]);

        if ($metadata) {
            $payload['metadata'] = $metadata;
        }

        do_action('payment_before_making_api_request', ZARINPAL_PAYMENT_METHOD_NAME, $payload);

        $response = Http::asJson()->acceptJson()->timeout(30)->post($this->requestUrl(), $payload);

        $result = (array) $response->json();

        do_action('payment_after_api_response', ZARINPAL_PAYMENT_METHOD_NAME, $payload, $result);

        $code = (int) Arr::get($result, 'data.code', 0);

        if (! $response->successful() || $code !== 100) {
            $message = Arr::get($result, 'errors.message')
                ?: Arr::get($result, 'data.message')
                ?: 'ZarinPal request failed (code: ' . $code . ').';

            throw new Exception($message);
        }

        $authority = Arr::get($result, 'data.authority');

        if (! $authority) {
            throw new Exception('ZarinPal did not return a valid authority code.');
        }

        session([
            'zarinpal_authority' => $authority,
            'zarinpal_amount' => $amount,
            'zarinpal_currency' => $gatewayCurrency,
        ]);

        return $this->startPayUrl() . $authority;
    }

    /**
     * Verify the transaction once the customer is redirected back from ZarinPal.
     * Returns the ZarinPal ref_id (reference id) on success, false on failure.
     */
    public function getPaymentStatus(Request $request): string|bool
    {
        $status = (string) $request->input('Status');
        $authority = (string) $request->input('Authority');

        if (strtolower($status) !== 'ok' || ! $authority) {
            session()->forget(['zarinpal_authority', 'zarinpal_amount', 'zarinpal_currency']);

            return false;
        }

        $sessionAuthority = session('zarinpal_authority');

        if ($sessionAuthority && $sessionAuthority !== $authority) {
            return false;
        }

        $amount = (int) (session('zarinpal_amount') ?: round((float) $request->input('amount')));
        $currency = strtoupper((string) (session('zarinpal_currency') ?: $this->configuredCurrency()));

        try {
            $payload = [
                'merchant_id' => $this->merchantId(),
                'amount' => $amount,
                'currency' => $currency,
                'authority' => $authority,
            ];

            do_action('payment_before_making_api_request', ZARINPAL_PAYMENT_METHOD_NAME, $payload);

            $response = Http::asJson()->acceptJson()->timeout(30)->post($this->verifyUrl(), $payload);

            $result = (array) $response->json();

            do_action('payment_after_api_response', ZARINPAL_PAYMENT_METHOD_NAME, $payload, $result);

            $code = (int) Arr::get($result, 'data.code', 0);

            // 100 = paid & verified now, 101 = was already verified before (still a success state)
            if (! $response->successful() || ! in_array($code, [100, 101], true)) {
                $message = Arr::get($result, 'errors.message')
                    ?: Arr::get($result, 'data.message')
                    ?: 'ZarinPal verification failed (code: ' . $code . ').';

                throw new Exception($message);
            }

            $refId = (string) Arr::get($result, 'data.ref_id');

            $detail = Arr::get($result, 'data', []);
            $detail['amount'] = $amount;
            $detail['currency'] = $currency;
            $detail['authority'] = $authority;

            Cache::put(self::CACHE_PREFIX . $refId, $detail, now()->addDays(90));

            session(['zarinpal_ref_id' => $refId]);

            return $refId;
        } catch (Exception $exception) {
            $this->setErrorMessageAndLogging($exception, 1);

            return false;
        }
    }

    public function afterMakePayment(array $data): ?string
    {
        $chargeId = session('zarinpal_ref_id');

        $orderIds = (array) Arr::get($data, 'order_id', []);

        do_action(PAYMENT_ACTION_PAYMENT_PROCESSED, [
            'amount' => Arr::get($data, 'amount'),
            'currency' => Arr::get($data, 'currency'),
            'charge_id' => $chargeId,
            'order_id' => $orderIds,
            'customer_id' => Arr::get($data, 'customer_id'),
            'customer_type' => Arr::get($data, 'customer_type'),
            'payment_channel' => ZARINPAL_PAYMENT_METHOD_NAME,
            'status' => PaymentStatusEnum::COMPLETED,
        ]);

        session()->forget(['zarinpal_authority', 'zarinpal_amount', 'zarinpal_currency', 'zarinpal_ref_id']);

        return $chargeId;
    }

    /**
     * Fetch the cached verification detail for a given ZarinPal ref_id, used in the
     * admin "payment detail" view. Returns null if nothing was cached for this id
     * (e.g. very old payments made before this plugin was installed).
     */
    public function getPaymentDetails(?string $paymentId): ?array
    {
        if (! $paymentId) {
            return null;
        }

        return Cache::get(self::CACHE_PREFIX . $paymentId);
    }
}
