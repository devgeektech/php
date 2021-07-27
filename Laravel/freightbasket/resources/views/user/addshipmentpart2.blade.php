@extends('layouts.usertemplate')
@section('content')
<!-- Top Bar End -->
<!-- Page-Title -->
<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
			</div>
			<h4 class="page-title">Add New Shipment </h4>
		</div>
		@if (Session::has('success'))
		<p class="alert alert-success">{!! Session::get('success') !!}<span class="close" style="cursor:pointer;" data-dismiss="alert">&times;</span></p>
		@endif
		@if (Session::has('error'))
		<p class="alert alert-danger">{!! Session::get('error') !!}<span class="close" style="cursor:pointer;" data-dismiss="alert">&times;</span></p>
		@endif
	</div>
</div>
@if(Auth::User()->role_id == '4')
<div class="row">
	
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
			    <div class="row">
			        <div class="col-md-10 offset-md-1 py-3">
			               <form action="{{ route('add_billing_form') }}" method="post">
			                   @csrf
                    	<!-- Row one -->
                    	<div class="row">
                    	    <div class="col-md-12"><h4 class="mb-3">Billing Form</h4></div>
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Shipping Refrence</label>
                    				<input type="text" name="shipping_Refrence" id="shipping_Refrence" class="form-control" readonly="true" >
                    			</div>
                    		</div>
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">FBL NO.</label>
                    				<input type="text" name="fbl_no" class="form-control" readonly="true" value="<?= rand(49858754,99999999); ?>">
                    			</div>
                    		</div>
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">OCEAN BL / MBL</label>
                    				<input type="text" name="obl_no" class="form-control" >
                    			</div>
                    		</div>
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Way Bill Type</label>
                    				<select name="way_bill_type" id="" class="custom-select">
                    					<option value="">Select Way Bill Type</option>
                    					<option value="ORIGINAL">ORIGINAL</option>
                    					<option value="SEA WAY BILL">SEA WAY BILL</option>
                    					<option value="TELEX RELEASE">TELEX RELEASE</option>
                    				</select>
                    			</div>
                    		</div>
                    	</div>
                    	<!-- end of row one -->
                    	<!-- row two -->
                    	<div class="row">
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Booking No</label>
                    				<input type="text" name="booking_no" class="form-control" readonly="true" value="<?php $date = date('Y'); echo substr($date,2) ?>-<?= rand(8954542,9999999); ?>">
                    			</div>
                    		</div>
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Refrence Date</label>
                    				<input type="text" name="refrence_date" class="form-control" readonly="true" value="<?= date('d-m-Y')?>">
                    			</div>
                    		</div>
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Fright Payment Type ( H-BL )</label>
                    				<select name="fright_payment_type[]" id="" class="custom-select">
                    					<option value="">Select Freight Payment Type</option>
                    					<option value="PRE-PAID">PRE-PAID</option>
                    					<option value="COLLECT">COLLECT</option>
                    				</select>
                    			</div>
                    		</div>
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Fright Payment Type ( M-BL )</label>
                    				<select name="fright_payment_type[]" id="" class="custom-select">
                    					<option value="">Select Freight Payment Type</option>
                    					<option value="PRE-PAID">PRE-PAID</option>
                    					<option value="COLLECT">COLLECT</option>
                    				</select>
                    			</div>
                    		</div>
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Customer Refrence</label>
                    				<input type="text" name="customer_refrence" class="form-control" >
                    			</div>
                    		</div>
                    	
                    
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Goods Description</label>
                    				<select name="goods_description" id="" class="custom-select">
                    					<option value="">Select Goods Description</option>
                    					@if($goods_description)
                    					@foreach($goods_description as $row)
                    					<option value="{{ $row->id }}">{{ $row->description_name }}</option>
                    					@endforeach
                    					@endif
                    				</select>
                    			</div>
                    		</div>
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Feeder Vessel</label>
                    				<select name="feeder_vessel" id="feeder_vessel" class="custom-select">
                    					<option value="">Select Feeder Vessel</option>
                    					@if($vessels)
                    					@foreach($vessels as $row)
                    					<option value="{{ $row->id }}">{{ $row->vessel_name }}</option>
                    					@endforeach
                    					@endif
                    					
                    				</select>
                    			</div>
                    		</div>
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Feeder Voyage</label>
                    				<select name="feeder_voyage" id="feeder_voyage" class="custom-select">
                    					<option value="">Select Feeder Voyage</option>
                    					
                    				</select>
                    			</div>
                    		</div>
                    			<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Main Vessel</label>
                    				<select name="main_vessel" id="main_vessel" class="custom-select">
                    					<option value="">Select Main Vessel</option>
                    					@if($vessels)
                    					@foreach($vessels as $row)
                    					<option value="{{ $row->id }}">{{ $row->vessel_name }}</option>
                    					@endforeach
                    					@endif
                    				</select>
                    			</div>
                    		</div>
                    
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Main Voyage</label>
                    				<select name="main_voyage" id="main_voyage" class="custom-select">
                    					<option value="">Select Maine Voyage</option>
                    				</select>
                    			</div>
                    		</div>
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">B/L Note</label>
                    				<input type="text" name="b_l_note" class="form-control" value="SHPR S.T.C.">
                    			</div>
                    		</div>
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Cargo Type</label>
                    				<select name="cargo_type" id="" class="custom-select">
                    					<option value="">Select Cargo Type </option>
                    					<option value="LCL/LCL">LCL/LCL</option>
                    					<option value="LCL/FCL">LCL/FCL</option>
                    					<option value="FCL/FCL">FCL/FCL</option>
                    					<option value="LCL/FCl">LCL/FCL</option>
                    				</select>
                    			</div>
                    		</div>
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Service Type</label>
                    				<select name="service_type" id="" class="custom-select">
                    					<option value="">Select Service Type </option>
                    					<option value="PORT TO PORT">PORT TO PORT</option>
                    					<option value="PORT TO DOOR">PORT TO DOOR</option>
                    					<option value="DOOR TO PORT">DOOR TO PORT</option>
                    					<option value="DOOR TO DOOR">DOOR TO DOOR</option>
                    				</select>
                    			</div>
                    		</div>
                    	
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Shipper</label>
                    					<select name="shipper" id="" class="custom-select">
                    					<option value="">Select</option>
                    					@if($customer_lists)
                    					@foreach($customer_lists as $row)
                    					    <option value="{{ $row->id }}">{{ $row->fullname }}</option>
                    					@endforeach
                    					@endif
                    				</select>
                    			
                    			</div>
                    		</div>
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Consignee</label>
                    			
                    				<select name="consignee" id="" class="custom-select">
                    					<option value="">Select</option>
                    					@if($customer_lists)
                    					@foreach($customer_lists as $row)
                    					    <option value="{{ $row->id }}">{{ $row->fullname }}</option>
                    					@endforeach
                    					@endif
                    				</select>
                    			</div>
                    		</div>
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Notify Company</label>
                    					<select name="notify_company" id="" class="custom-select">
                    					<option value="">Select</option>
                    					@if($customer_lists)
                    					@foreach($customer_lists as $row)
                    					    <option value="{{ $row->id }}">{{ $row->fullname }}</option>
                    					@endforeach
                    					@endif
                    				</select>
                    				
                    			</div>
                    		</div>
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Contract Company</label>
                    				<select name="contract_company" id="" class="custom-select">
                    					<option value="">Select</option>
                    					@if($customer_lists)
                    					@foreach($customer_lists as $row)
                    					    <option value="{{ $row->id }}">{{ $row->fullname }}</option>
                    					@endforeach
                    					@endif
                    				</select>
                    			
                    			</div>
                    		</div>
                    	
                    		
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">SHIPPING LINE</label>
                    				<input type="text" name="shipping_line" class="form-control">
                    			</div>
                    		</div>
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Carrier Agent</label>
                    				<select name="carrier_agent" id="" class="custom-select">
                    					<option value="">Select</option>
                    					@if($customer_lists)
                    					@foreach($customer_lists as $row)
                    					    <option value="{{ $row->id }}">{{ $row->fullname }}</option>
                    					@endforeach
                    					@endif
                    				</select>
                    			
                    			</div>
                    		</div>
                    		
                    	
                    
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">BILL FROM</label>
                    				
                    				<input type="text" name="invoice_company" class="form-control">
                    			</div>
                    		</div>
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Booking Company</label>
                    				<input type="text" name="booking_company" class="form-control">
                    			</div>
                    		</div>
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Supplier</label>
                    				<input type="text" name="supplier" class="form-control">
                    			</div>
                    		</div>
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">WAY BILL DATE</label>
                    				<input type="date" name="way_bill_date" class="form-control">
                    			</div>
                    		</div>
                    	
                    
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">FREIGHTS BILL TO</label>
                    				<input type="text" name="invoice_customer" class="form-control">
                    			</div>
                    		</div>
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">FBL ORIGINAL AND COPY</label>
                    				<select name="fbl_orignal_company" id="" class="custom-select">
                    					<option value="">Select FBL Original Company</option>
                    					<option value="0/0">0/0</option>
                    					<option value="3/3">3/3</option>
                    				</select>
                    			</div>
                    		</div>
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Place Of Receipt</label>
                    				<input type="text" name="place_of_receipt" class="form-control">
                    			</div>
                    		</div>
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Place Of Loading</label>
                    				<select name="place_of_loading" id="" class="custom-select">
                    					<option value="">Select FBL Original Company</option>
                    					<option value="FACTORY">FACTORY</option>
                    					<option value="PORT">PORT</option>
                    					<option value="FREE DEPOT">FREE DEPOT</option>
                    					<option value="WAREHOUSE">WAREHOUSE</option>
                    				</select>
                    			</div>
                    		</div>
                    
                    	
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Loading Date</label>
                    				<input type="date" name="loading_date" class="form-control">
                    			</div>
                    		</div>
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Origin Port</label>
                    				<select name="origin_port" id="origin_port" class="custom-select">
                    					<option value="">Select Orgin Port</option>
                    					@if($seaports)
                    					@foreach($seaports as $row)
                    					<option value="{{ $row->code}}">{{ $row->port}}</option>
                    					@endforeach
                    					@endif
                    				</select>
                    			</div>
                    		</div>
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Transhipment Port 1</label>
                    				<input type="text" name="transhipment_port_1" class="form-control">
                    			</div>
                    		</div>
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Transhipment Port 2</label>
                    				<input type="text" name="transhipment_port_2" class="form-control">
                    			
                    			</div>
                    		</div>
            
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Port Of Discharge</label>
                    				<select name="port_of_discharge" id="port_of_discharge" class="custom-select">
                    					<option value="">Select Discharge Port</option>
                    					@if($seaports)
                    					@foreach($seaports as $row)
                    				<option value="{{ $row->code}}">{{ $row->port}}</option>
                    					@endforeach
                    					@endif
                    				</select>
                    			</div>
                    		</div>
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Discharged Date</label>
                    				<input type="date" name="discharged_date" class="form-control">
                    			</div>
                    		</div>
                    		
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Payment Terms</label>
                    				<select name="payment_terms" id="" class="custom-select">
                    					<option value="">Select Payment Of Terms</option>
                    					<option value="CASH">CASH</option>
                    					<option value="CREDIT">CREDIT</option>
                    					<option value="BANK CHEQUE">BANK CHEQUE</option>
                    				</select>
                    			</div>
                    		</div>
                
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Estimated Departure Date</label>
                    				<input type="date" name="estimated_departure_date" class="form-control">
                    			</div>
                    		</div>
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Sailing / Flight Date</label>
                    				<input type="date" name="sailing_flight_date" class="form-control">
                    			</div>
                    		</div>
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Estimated Arrival Date</label>
                    				<input type="date" name="estimated_arrival_date" class="form-control">
                    			</div>
                    		</div>
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Arrival Date</label>
                    				<input type="date" name="arrival_date" class="form-control">
                    			</div>
                    		</div>
                    	
                    		<div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Sales REP</label>
                    				<select name="sales_rep" id="" class="custom-select">
                    					<option value="">Select Sales REP</option>
                    					<option value="BOOKING FROM THE SYSTEMS">BOOKING FROM THE SYSTEMS</option>
                    					<option value="SALES PERSON">SALES PERSON</option>
                    				</select>
                    			</div>
                    		</div>
                            <div class="col-md-3">
                    			<div class="form-group">
                    				<label for="">Domestic Custom Place</label>
                    				<input type="text" name="domestic_custom_place" class="form-control">
                    			</div>
                    		</div>
                    		<div class="col-md-3">
                    		    <div class="form-group">
                    		        <label for="">DOMESTIC INLAND CARRIER </label>
                    		        <select name="trucking_company" id="" class="custom-select">
                    					<option value="">Select</option>
                    					@if($customer_lists)
                    					@foreach($customer_lists as $row)
                    					    <option value="{{ $row->id }}">{{ $row->fullname }}</option>
                    					@endforeach
                    					@endif
                    				</select>
                    		        
                    		    </div>
                    		</div>
                    		<div class="col-md-3">
                    		    <div class="form-group">
                    		        <label for="">DESTINATION AGENT</label>
                    		        <select name="destination_agent" id="" class="custom-select">
                    					<option value="">Select</option>
                    					@if($customer_lists)
                    					@foreach($customer_lists as $row)
                    					    <option value="{{ $row->id }}">{{ $row->fullname }}</option>
                    					@endforeach
                    					@endif
                    				</select>
                    		        
                    		    </div>
                    		</div>
                    	</div>
                    	<!-- end row six -->
                    	
                    	<!-- row fifteen -->
                    	<div class="row">
                    		<div class="col-md-12">
                    			<div class="form-group">
                    				<h4>Place Of Delivery </h4>
                    			</div>
                    		</div>
                    		<div class="col-md-4">
                    		    <div class="form-group">
                    		        <label for="">ZIP CODE</label>
                    		        <input type="text" name="delivery_zip_code" class="form-control">
                    		    </div>
                    		</div>
                    		<div class="col-md-4">
                    		    <div class="form-group">
                    		        <label for="">AREA NAME </label>
                    		        <input type="text" name="delivery_area_name" class="form-control">
                    		    </div>
                    		</div>
                    		<div class="col-md-4">
                    		    <div class="form-group">
                    		        <label for="">CITY NAME </label>
                    		        <input type="text" name="delivery_city_name" class="form-control">
                    		    </div>
                    		</div>
                    		</div>
                    		<!-- row fiftenn -->
                    	<!-- row sixteen -->
                    	<div class="row">
                    		<div class="col-md-12">
                    			 <button type="submit" class="btn btn-secondary float-right">Submit</button>
                    		</div>
                    	</div>
                    	<!-- end of row sixteen -->
                    </form>
			        </div>
			    </div>
		    </div>
        </div>
	</div>
</div>
<!-- testing modal -->
<!-- Button trigger modal -->
<!-- Modal -->
@else
<div class="card">
    <div class="card-body">
        <h3>Provider Dashboard is under maintenance</h3>
    </div>
</div>
@endif

@endsection