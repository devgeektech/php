<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Billable;
use Redirect;
use Config;

class StripeController extends Controller
{
    public function index()
    {
		return view("stripe.index");
	}

	public function payWithpaypal(Request $request)
    {
    	dd($request);
		
		\Session::put('error', 'Unknown error occurred');
	        return Redirect::route('paypal');
	}

	public function getPaymentStatus()
    {
        /** Get the payment ID before session clear **/
        $payment_id = Session::get('paypal_payment_id');
		/** clear the session payment ID **/
		        Session::forget('paypal_payment_id');
		        if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {
		\Session::put('error', 'Payment failed');
		            return Redirect::route('/');
		}
		$payment = Payment::get($payment_id, $this->_api_context);
		        $execution = new PaymentExecution();
		        $execution->setPayerId(Input::get('PayerID'));
		/**Execute the payment **/
		        $result = $payment->execute($execution, $this->_api_context);
		if ($result->getState() == 'approved') {
		\Session::put('success', 'Payment success');
		            return Redirect::route('/');
		}
		\Session::put('error', 'Payment failed');
		        return Redirect::route('/');
	}
}
