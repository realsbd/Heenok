<?php

namespace App\Http\Controllers;

use App\Models\AdminSettings;
use App\Models\PaymentGateways;
use Hen8y\Vpay\Vpay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class VpayController extends Controller
{
    public function __construct(Request $request, AdminSettings $settings)
    {
        $this->request = $request;
        $this->settings = $settings::first();
    }
    /**
     * Redirect the User to Checkout Page
     */
    public function redirectToGateway()
    {

        // Validate Payment Gateway
        Validator::extend('check_payment_gateway', function ($attribute, $value, $parameters) {
            return PaymentGateways::whereName($value)->first();
        });

        // Currency Position
        if ($this->settings->currency_position == 'right') {
            $currencyPosition =  2;
        } else {
            $currencyPosition =  null;
        }

        $messages = [
            'amount.min' => __('general.amount_minimum' . $currencyPosition, ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),
            'amount.max' => __('general.amount_maximum' . $currencyPosition, ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),
            'payment_gateway.check_payment_gateway' => __('general.payments_error'),
            'image.required_if' => __('general.please_select_image'),
        ];

        //<---- Validation
        $validator = Validator::make($this->request->all(), [
            'amount' => 'required|integer|min:' . $this->settings->min_deposits_amount . '|max:' . $this->settings->max_deposits_amount,
            'payment_gateway' => 'required|check_payment_gateway',
            'image' => 'required_if:payment_gateway,==,Bank|mimes:jpg,gif,png,jpe,jpeg|max:' . $this->settings->file_size_allowed_verify_account . '',
            'agree_terms' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray(),
            ]);
        }

        $payment = PaymentGateways::whereName('Vpay')->firstOrFail();

        $fee = $payment->fee;

        $taxes = $this->settings->tax_on_wallet ? ($this->request->amount * auth()->user()->isTaxable()->sum('percentage') / 100) : 0;

        $amountFixed = number_format($this->request->amount + ($this->request->amount * $fee / 100) + $taxes, 2, '.', '');


        // Sends the transaction data to the checkout handler
        $data = array(
            'amount' => $amountFixed,
            "email" => auth()->user()->email,
            "transactionref" => \Str::random(25),
            "domain" => env('APP_URL'),
            "key" => $payment->key,
            "customer_service_channel" => "Tel: +2348030070000",
        );

        $secret = config('vpay.public_id');

        if (!$data) {
            return redirect()->back()->with('error', 'Payment data not found');
        }
        $payment = (new Vpay)->handleCheckout($data);
    //   $payment = Http::withToken($secret)->post(
    //       env('APP_URL') . 'payment/webhook/vpay',
    //       $data
    //   )->json();

      // Add null check
    //   if (!$payment) {
    //       return response()->json([
    //           'success' => false,
    //           'errors' => ['error' => 'Payment gateway not responding'],
    //       ]);
    //   }

    //   if ($payment['status'] !== 'success') {
    //       return response()->json([
    //           'success' => false,
    //           'errors' => ['error' => __('general.error')],
    //       ]);
    //   }
    
    //     return response()->json([
    //       'success' => true,
    //       'url' => $payment['data']['link']
    //     ]);

    return $payment;
    }


    /**
     * Callback
     *
     * @param Request $request
     */
    public function callback(Request $request)
    {
        // This will give you all the data sent in the POST request
        // Now you can access individual data elements like $data['status'], $data['amount'], etc.

        // if successfull $request->input('status') will return success

        // if failed $request->input('status') will return failed

        $status = $request->input('status');
        $amount = $request->input('amount');
        $transactionref = $request->input('transactionref');
        $email = $request->input('email');

        // Use the retrieved data as needed
    }
}
