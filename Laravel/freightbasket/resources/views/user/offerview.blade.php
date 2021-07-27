@extends('layouts.usertemplate')
@section('content')
<!-- Top Bar End -->
<!-- Page-Title -->
<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
			</div>
			<h4 class="page-title">Offer</h4>
		</div>
		@if (Session::has('success'))
		<p class="alert alert-success">{!! Session::get('success') !!}<span class="close" style="cursor:pointer;" data-dismiss="alert">&times;</span></p>
		@endif
		@if (Session::has('error'))
		<p class="alert alert-danger">{!! Session::get('error') !!}<span class="close" style="cursor:pointer;" data-dismiss="alert">&times;</span></p>
		@endif
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
			 
		        <div class="row">
		            <div class="col-md-12 p-5">
	                 	<table class="table table-striped  table-bordered text-uppercase">
				            <tr>
					            <th>Freight</th>
				            	@php
				            	$freight = $offer->freight;
				            	@endphp
				                <td>{{ $freight->service_category.' - '.$freight->service_type.' - '.$freight->departure_country.' to '.$freight->arriaval_country }}</td>
			                </tr>
			                <tr>
			                    <th>Customer</th>
					            
				                @php
				                $customer = json_decode($offer->customer)
				                @endphp
				                
				                <td>
				                    @foreach($customer as $customerr)
				                    {{ $customerr }}</br>
				                    @endforeach
				                </td>
			                </tr>
				            <tr>
			                    <th>Offer Pricing</th>
				            
				                @php
				                $offer_price = json_decode($offer->offer_price);
				                $coutn_price = count($offer_price->cost_type);
				                @endphp
				                <td>
				                    @for ($i = 0; $i < $coutn_price; $i++)
				                    	<table class="table table-striped  table-bordered text-uppercase">
				                        <tr>
				                            <th>Cost Type</th>
				                            <th>Calculaion</th>
				                            @if(count($offer_price->quantity) > 0 && $offer_price->quantity[$i] != null)
                                                <th>Quantity</th>
                                            @endif
				                            <th>Currency</th>
				                            <th>Price</th>
				                        </tr>
				                        <tr>
				                            <td>{{ $offer_price->cost_type[$i] }}</td>
				                            <td>{{ $offer_price->calculaion[$i] }}</td>
				                            @if(count($offer_price->quantity) > 0 && $offer_price->quantity[$i] != null)
                                                <td>{{ $offer_price->quantity[$i] }}</td> 
                                            <br>
                                            @endif
                                            <td>{{ $offer_price->currency[$i] }}</td>
                                            <td>
                                                @if($offer_price->currency[$i] == "Great British Pound")
                                                   @php setlocale(LC_MONETARY,"en_GB"); @endphp
                                                @elseif($offer_price->currency[$i] == "Euro")
                                                    @php setlocale(LC_MONETARY,"en_IE"); @endphp
                                                @elseif($offer_price->currency[$i] == "Australian Dollar")
                                                    @php setlocale(LC_MONETARY,"en_AU"); @endphp
                                                @elseif($offer_price->currency[$i] == "Canadian Dollar")
                                                    @php setlocale(LC_MONETARY,"en_CA"); @endphp
                                                @elseif($offer_price->currency[$i] == "Turish Lira")
                                                    @php setlocale(LC_MONETARY,"tr_TR"); @endphp
                                                @else
                                                    @php setlocale(LC_MONETARY,"en_US"); @endphp
                                                @endif
                                                
                                                {{ money_format('%i', $offer_price->price[$i]) }}
                                            </td>
				                        </tr>
				                        </table>
                                    @endfor
				                </td>
				            </tr>
				            <tr>
				                <th>Notes</th>
				                <td>{{$offer->custom_note}}</td>
				            </tr>
			            </table>		   
				    </div>
		        </div>
			   
			</div>
		</div>
	</div>
</div>
@endsection