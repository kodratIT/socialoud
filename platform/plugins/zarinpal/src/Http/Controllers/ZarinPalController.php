<?php

namespace Botble\ZarinPal\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Payment\Supports\PaymentHelper;
use Botble\ZarinPal\Http\Requests\ZarinPalPaymentCallbackRequest;
use Botble\ZarinPal\Services\ZarinPalPaymentService;

class ZarinPalController extends BaseController
{
    public function getCallback(
        ZarinPalPaymentCallbackRequest $request,
        ZarinPalPaymentService $zarinPalPaymentService,
        BaseHttpResponse $response
    ) {
        $status = $zarinPalPaymentService->getPaymentStatus($request);

        if (! $status) {
            return $response
                ->setError()
                ->setNextUrl(PaymentHelper::getCancelURL())
                ->withInput()
                ->setMessage(trans('plugins/zarinpal::zarinpal.payment_failed'));
        }

        $zarinPalPaymentService->afterMakePayment($request->input());

        return $response
            ->setNextUrl(PaymentHelper::getRedirectURL())
            ->setMessage(trans('plugins/payment::payment.checkout_success'));
    }
}
