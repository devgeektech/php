@extends('layouts.usertemplate')
@section('content')
<!-- Top Bar End -->
<!-- Page-Title -->
<div class="row page-title-box">
	<div class="col-sm-6 ">
		<div class="">
			<div class="float-right">
			</div>
			<h4 class="page-title">Add Customer</h4>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="">
			<div class="float-right">
			
			<h4 class="page-title"> <a href="{{ route('addshipmentpart2') }}" class="btn btn-info">Skip This Step </a></h4></div>
		</div>
	</div>
	<div class="col-sm-12">
		
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
			     <form action="{{ route('addcustomer') }}" method="post" id="customer_data">
			         @csrf
                	<fieldset id="">
                		<div class="row">
                			<div class="col-md-12">
                				<div class="row">
                					<div class="col-md-12">
                						<h4>Customer Data</h4>
                					</div>
                					<div class="col-md-3">
                						<div class="form-group">
                							<label for="">Company Name</label>
                							<input type="text" name="fullname" class="form-control" required>
                						</div>
                					</div>
                					<div class="col-md-3">
                						<div class="form-group">
                							<label for="">Company Address.</label>
                							<input type="text" name="company_address" class="form-control" required>
                						</div>
                					</div>
                					<div class="col-md-3">
                						<div class="form-group">
                							<label for="">Country</label>
                							<select class="form-control @if ($errors->has('country')) has-error @endif" id="country" name="country" required>
                                                <option value="">Select Country</option>
                                                <?php foreach ($countryname as $countryname1): ?>
                                                    <option value="<?php echo $countryname1->name; ?>">
                                                        {{ $countryname1->name }}
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                						</div>
                					</div>
                					<div class="col-md-3">
                						<div class="form-group">
                							<label for="">City.</label>
                							<input type="text" name="city" class="form-control" required>
                						</div>
                					</div>
                					</div>
                					<div id="multiple_name">
                					    <div class="row">
                					        <div class="col">
                						    <div class="form-group">
                							    <label for="">Name</label>
                							     <input type="text" name="name[]" class="form-control" required>
                						    </div>
                					      </div>
                					      <div class="col">
                						    <div class="form-group">
                							    <label for="">Email</label>
                							     <input type="email" name="email[]" class="form-control" required>
                						    </div>
                					      </div>
                					      <div class="col">
                						    <div class="form-group">
                							    <label for="">Occuption</label>
                							     <input type="text" name="occuption[]" class="form-control" required>
                						    </div>
                					      </div>
                					      <div class="col">
                        	                 <span class="btn btn-success mt-4" id="AddFieldforcustomer" >Add More</span>
                                        	</div>
                					    </div>
                					</div>
                					<div class="row">
                				
                					<div class="col-md-4">
                						<div class="form-group">
                							<label for=""> TEL </label>
                							<input type="tel" name="phone" class="form-control" required>
                						</div>
                					</div>
                					<div class="col-md-4">
                						<div class="form-group">
                							<label for="">FAX </label>
                							<input type="text" name="fax" class="form-control" required>
                						</div>
                					</div>
                					<div class="col-md-4">
                						<div class="form-group">
                							<label for="">VAT OFFICE</label>
                							<input type="text" name="vat" class="form-control" required>
                						</div>
                					</div>
                					<div class="col-md-4">
                						<div class="form-group">
                							<label for="">TAX NO </label>
                							<input type="text" name="tax_no" class="form-control" required>
                						</div>
                					</div>
                					<div class="col-md-4">
                						<div class="form-group">
                							<label for="">MERSIS NO  </label>
                							<input type="text" name="mesis_no" class="form-control">
                						</div>
                					</div>
                					<div class="col-md-4">
                						<div class="form-group">
                							<label for="">PERSON INCHARGE  </label>
                							<input type="text" name="person_incharge" class="form-control" required>
                						</div>
                					</div>
                					<div class="col-md-4">
                						<div class="form-group">
                							<label for="">Group Name </label>
                							<input type="text" name="group_name" class="form-control" required>
                						</div>
                					</div>
                					
                					</div>
                					
                			</div>
                		</div>
                		
                		<div class="row">
                		    
                		       
                		            
                		            <div class="col-md-12">
                		                <button type="submit" id="submit_customer_data" class="btn btn-secondary float-right ml-3">Submit</button>
                		                <input type="reset" class="btn btn-primary float-right">
                		              </div> 
                		   
                		</div>
</fieldset>
                
                </form>
			            <!--shipment form-->
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