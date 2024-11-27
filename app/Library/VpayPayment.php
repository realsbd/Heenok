<?php

namespace App\Library;

use Hen8y\Vpay\Vpay;
use Illuminate\Support\Facades\Http;

class VpayPayment
{

    public static function sendPayment(array $data)
    {
        if (!$data) {
            return redirect()->back()->with('error', 'Payment data not found');
        }
        return (new Vpay)->handleCheckout($data);
    }
}