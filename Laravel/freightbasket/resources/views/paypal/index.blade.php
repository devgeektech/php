@extends('layouts.usertemplate')
@section('content')
	@if ($message = Session::get('success'))
	    <div class="w3-panel w3-green w3-display-container">
	        <span onclick="this.parentElement.style.display='none'"
	                class="w3-button w3-green w3-large w3-display-topright">&times;</span>
	        <p>{!! $message !!}</p>
	    </div>
	    <?php Session::forget('success');?>
    @endif
	@if ($message = Session::get('error'))
	    <div class="w3-panel w3-red w3-display-container">
	        <span onclick="this.parentElement.style.display='none'"
	                class="w3-button w3-red w3-large w3-display-topright">&times;</span>
	        <p>{!! $message !!}</p>
	    </div>
	    <?php Session::forget('error');?>
    @endif
	<div class="row">
		<div class="col-md-12">
			<form class="w3-container w3-display-middle w3-card-4 " method="POST" id="payment-form"  action="{{ route('payment.add-funds.paypal') }}">
			  {{ csrf_field() }}
			  <h2 class="w3-text-blue">Payment Form</h2>
			  <p>Demo PayPal form - Integrating paypal in laravel</p>
			  <p>      
			  <label class="w3-text-blue"><b>Enter Amount</b></label>
			  <input class="w3-input w3-border" name="amount" type="text" required></p>      
			  <button class="w3-btn w3-blue">Pay with PayPal</button></p>
			</form>
		</div>
	</div>
@endsection