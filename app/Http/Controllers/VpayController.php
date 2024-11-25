<?php

namespace App\Http\Controllers;

use Hen8y\Vpay\Vpay;
use Illuminate\Http\Request;

class VpayController extends Controller
{
    /**
     * Redirect the User to Checkout Page
     */
    public function redirectToGateway()
    {

        // Get the data from the session
        $paymentData = session('payment_data');

        if (!$paymentData) {
            return redirect()->back()->with('error', 'Payment data not found');
        }
        return (new Vpay)->handleCheckout($paymentData);
    }


    /**
     * Callback
     *
     * @param Request $request
     */
    public function callback(Request $request){
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
