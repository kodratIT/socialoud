<?php

namespace Botble\ZarinPal\Http\Requests;

use Botble\Support\Http\Requests\Request;

class ZarinPalPaymentCallbackRequest extends Request
{
    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric'],
            'currency' => ['required'],
        ];
    }
}
