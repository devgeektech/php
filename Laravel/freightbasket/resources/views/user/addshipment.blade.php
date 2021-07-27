@extends('layouts.usertemplate')
@section('content')
<!-- Top Bar End -->
<!-- Page-Title -->
<div class="row page-title-box">
	<div class="col-sm-6 ">
		<div class="">
			<div class="float-right">
			</div>
			<h4 class="page-title">Add New Shipment </h4>
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
			     <form action="{{ route('add_good_description') }}" method="post" id="goods_desc">
			         @csrf
                	<fieldset id="shipment_goods_desc">
                		<div class="row">
                			<div class="col-md-12">
                				<div class="row">
                					<div class="col-md-12">
                						<h4>Goods Description</h4>
                					</div>
                					<div class="col-md-4">
                						<div class="form-group">
                							<label for="">Goods Description Title</label>
                							<input type="text" name="goods_name_title" class="form-control">
                						</div>
                					</div>
                					<div class="col-md-4">
                						<div class="form-group">
                							<label for="">Commercial Invoice No.</label>
                							<input type="text" name="commercial_invoice_no" class="form-control">
                						</div>
                					</div>
                					<div class="col-md-4">
                						<div class="form-group">
                							<label for="">Commercial Invoice Date</label>
                							<input type="date" name="commercial_invoice_date" class="form-control">
                						</div>
                					</div>
                					</div>
                					
                			</div>
                		</div>
                		<div id="more_goods_desc">
                		    <div class="row">
                					<div class="col-md-4">
                						<div class="form-group">
                							<label for="">Goods Name</label>
                							<input type="text" name="goods_name[]" class="form-control">
                						</div>
                					</div>
                					<div class="col-md-4">
                						<div class="form-group">
                							<label for="">Packing Types</label>
                							<input type="text" name="packing_types[]" class="form-control">
                						</div>
                					</div>
                					<div class="col-md-4">
                						<div class="form-group">
                							<label for="">Number Of Package </label>
                							<input type="number" name="packege_quantity[]" class="form-control packege_quantity">
                						</div>
                					</div>
                					<div class="col-md-4">
                						<div class="form-group">
                							<label for="">Gross Weight </label>
                							<input type="text" name="gross_weight[]" class="form-control">
                						</div>
                					</div>
                					<div class="col-md-4">
                						<div class="form-group">
                							<label for="">Width (In CM )</label>
                							<input type="number" name="width[]"  class="form-control goods_width">
                						</div>
                					</div>
                					<div class="col-md-4">
                						<div class="form-group">
                							<label for="">Length (In CM )</label>
                							<input type="number" name="length[]"  class="form-control goods_length">
                						</div>
                					</div>
                					<div class="col-md-4">
                						<div class="form-group">
                							<label for="">Height (In CM )</label>
                							<input type="number" name="height[]"  class="form-control goods_height">
                						</div>
                					</div>
                					<div class="col-md-4">
                						<div class="form-group">
                							<label for="">Hs Code</label>
                							<input type="text" name="hs_code[]" class="form-control">
                						</div>
                					</div>
                					
                				<div class="form-group col-md-4">
                					<label for="">Container Number</label>
                					<input type="text" name="container_number[]" class="form-control">
                				</div>
                				<div class="form-group col-md-4">
                					<label for="">Seal Number</label>
                					<input type="text" name="seal_no[]" class="form-control">
                				</div>
                				<div class="form-group col-md-4">
                					<label for="">Types Of Container</label>
                					<select name="type_opf_container[]" class="custom-select text-uppercase">
                						<option value="">SELECT CONTAINER TYPE</option>
                						<option value="20 DV STANDART CNTR">20 DV STANDART CNTR</option>
                						<option value="40’DV STANDART CNTR">40’DV STANDART CNTR</option>
                						<option value="40’HC CNTR">40’HC CNTR</option>
                						<option value="45’HC CNTR">45’HC CNTR</option>
                						<option value="45’PW PALLET WIDE CNTR">45’PW PALLET WIDE CNTR</option>
                						<option value="20’RF REEFER CNTR">20’RF REEFER CNTR</option>
                						<option value="40’RF REEFER CNTR">40’RF REEFER CNTR</option>
                						<option value="20’OT OPEN TOP CNTR">20’OT OPEN TOP CNTR</option>
                						<option value="40’OT OPEN TOP CNTR">40’OT OPEN TOP CNTR</option>
                					</select>
                				</div>
                				
                				</div>
                		</div>
                		<div class="row">
                		    
                		        <div class="col-md-6">
                		            <h4>Cubic Meter Of Cargo: <span id="cubic_calculation">0</span> <span id="cubic_calculator" class="btn btn-success ml-3">Calculate</span></h4>
                		            </div>
                		            <div class="col-md-6">
                		                <button type="submit" id="submit_goods_desc" class="btn btn-secondary float-right ml-3">Submit</button>
                		                <buttton class="btn btn-primary  float-right" id="addfieldgoodsdesc">Add More</button>
                		              </div> 
                		   
                		</div>
</fieldset>
                	<!--<fieldset>-->
                	<!--	<div id="shipment_container">-->
                	<!--		<div class="row">-->
                	<!--			<div class="col-md-12">-->
                	<!--				<h4>Container Description</h4>-->
                	<!--			</div>-->
                	<!--		</div>-->
                	<!--			<div  id="shipment_cntr_desc">-->
                	<!--			    <div class="row">-->
                	<!--			<div class="form-group col-md-4">-->
                	<!--				<label for="">Container Number</label>-->
                	<!--				<input type="number" name="container_number[]" class="form-control">-->
                	<!--			</div>-->
                	<!--			<div class="form-group col-md-4">-->
                	<!--				<label for="">Types Of Container</label>-->
                	<!--				<select name="type_opf_container[]" class="custom-select text-uppercase">-->
                	<!--					<option value="">SELECT CONTAINER TYPE</option>-->
                	<!--					<option value="20 DV STANDART CNTR">20 DV STANDART CNTR</option>-->
                	<!--					<option value="40’DV STANDART CNTR">40’DV STANDART CNTR</option>-->
                	<!--					<option value="40’HC CNTR">40’HC CNTR</option>-->
                	<!--					<option value="45’HC CNTR">45’HC CNTR</option>-->
                	<!--					<option value="45’PW PALLET WIDE CNTR">45’PW PALLET WIDE CNTR</option>-->
                	<!--					<option value="20’RF REEFER CNTR">20’RF REEFER CNTR</option>-->
                	<!--					<option value="40’RF REEFER CNTR">40’RF REEFER CNTR</option>-->
                	<!--					<option value="20’OT OPEN TOP CNTR">20’OT OPEN TOP CNTR</option>-->
                	<!--					<option value="40’OT OPEN TOP CNTR">40’OT OPEN TOP CNTR</option>-->
                	<!--				</select>-->
                	<!--			</div>-->
                	<!--			<div class="col-md-2"><span class="btn btn-primary mt-4" id="addfieldcntrlist">Add More</span></div>-->
                	<!--			</div>-->
                	<!--			</div>-->
                	<!--			</div>-->
                	<!--			<hr>-->
                	<!--			<div class="row">-->
                	<!--		    	<div class="col-md-12 text-center">-->
                	<!--			    	<button type="submit" class="btn btn-primary ">Submit</button>-->
                	<!--			    </div>-->
                	<!--			</div>-->
                			
                		
                	<!--</fieldset>-->
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