@extends('layouts.usertemplate')
@section('content')
<!-- Top Bar End -->
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Add Offer</h4>
            </div>
            @if (Session::has('success'))
            <p class="alert alert-success">{!! Session::get('success') !!}<span class="close" style="cursor:pointer;" data-dismiss="alert">&times;</span></p>
            @endif
            @if (Session::has('error'))
            <p class="alert alert-danger">{!! Session::get('error') !!}<span class="close" style="cursor:pointer;" data-dismiss="alert">&times;</span></p>
            @endif


            @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>        
                @endforeach
            </div>
            @endif
        </div>
    </div>
    @if(Auth::User()->role_id == '4')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                   
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Filter Freight Here</h3>
                        </div>
                        <form id="saerchofferForm" action="#" method="post" class="col-md-12">
                            @csrf
                            <div class="row">    
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Select Freight Type</label>
                                        <select class="form-control freight_type_name" name="freight_type_name" required>
                                            <option value="air">Air</option>
                                            <option value="land">Land</option>
                                            <option value="sea">Sea</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Departure Country</label>
                                        <input type="text" name="dep_country_name" id="dep_country_name" class="typeahead form-control" placeholder="Departure Country"/>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Arrival Country</label>
                                        <input type="text" name="arv_country_name" id="arv_country_name" class="typeahead form-control " placeholder="Arrival Country"/>
                                    </div> 
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Arrival Port</label>
                                        <input type="text" name="arv_ports_name" id="arv_ports_name" class="typeahead form-control" placeholder="Arrival Port"/>
                                    </div> 
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label></label>
                                        <input type="submit" id="filter_submit_saerchofferForm" class="btn btn-primary" value="Filter"/>
                                    </div> 
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Fill Offer Data</h3>
                        </div>
                        <form id="offerForm" action="{{ action('ShipmentController@offeradd') }}" method="post" enctype="mutipart/form-data" class="col-md-12">
                        @csrf
                        <div class="row">    
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Select Freight</label>
                                    <select class="form-control @if ($errors->has('freight_id')) has-error @endif" id="freight_id" name="freight_id" required>
                                        <option value="">Select freight</option>
                                        <?php foreach ($freights as $freight): ?>
                                            <option value="<?php echo $freight->id; ?>" data-val="{{ $freight->service_category }}" data-type="{{ $freight->service_type }}">
                                                {{ $freight->service_category }} - {{ $freight->departure_country }} - {{ $freight->arriaval_country }} - {{ $freight->arriaval_port }}
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div> 
                            </div>
                            
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label>Select Client's Email</label>
                                    <div class="form-group">
                                    @if(count($cusomerslist) <= 0)
                                        <a href="/customer">You need to add customer here</a>
                                    @else
                                        <select class="form-control multiselect-icon  @if ($errors->has('client_email')) has-error @endif" id="client_email" name="client_email[]"  role="multiselect" multiple="multiple" required>
                                                @foreach ($cusomerslist as $cusomerslis)
                                                    @php
                        				                $multi_user_uns = unserialize($cusomerslis->multi_user);
                        				            @endphp
                        				            @foreach($multi_user_uns as $cusomerslis_emil)
                        				                <option value="{{ $cusomerslis_emil['email'] }}">{{ $cusomerslis_emil['email'] }}</option>
                        				            @endforeach
                                                @endforeach
                                        </select>
                                    @endif
                                    </div> 
                                </div> 
                            </div>
                            
                            <div class="col-md-12">
                                    <h4>Offer Service Cost</h4>
                            </div>
                            
                            <div class="col-md-12 service_cost_clone row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Cost Type</label>
                                        <select class="form-control cost_type_select" name="cost_type[]" required>
                                            <option value="">Select</option>
                                        </select>
                                    </div> 
                                </div>
                                <div class="col-md-2">
    			                    <div class="form-group">
    			                        <label>Calculation</label>
    			                        <select name="calculaion[]" class="form-control cal_land cal_selc" style="display:none">
    			                            <option value="">SELECT TRAILER</option>
    			                            <option value="FLAT BED TRAILER">FLAT BED TRAILER</option>
                                            <option value="DRY VAN AND ENCLOSED TRAILERS">DRY VAN AND ENCLOSED TRAILERS</option>
                                            <option value="REFRIGERATED TRAILERS AND REEFERS">REFRIGERATED TRAILERS AND REEFERS</option>
                                            <option value="LOWBOY TRAILER">LOWBOY TRAILER</option>
                                            <option value="STEP DECK TRAILERS – SINGLE DROP TRAILERS">STEP DECK TRAILERS – SINGLE DROP TRAILERS</option>
                                            <option value="EXTENDABLE FLATBED STRETCH TRAILERS">EXTENDABLE FLATBED STRETCH TRAILERS</option>
                                            <option value="STRETCH SINGLE DROP DECK TRAILER">STRETCH SINGLE DROP DECK TRAILER</option>
                                            <option value="STRETCH DOUBLE DROP TRAILERS">STRETCH DOUBLE DROP TRAILERS</option>
                                            <option value="EXTENDABLE DOUBLE DROP TRAILERS">EXTENDABLE DOUBLE DROP TRAILERS</option>
                                            <option value="RGN OR REMOVABLE GOOSENECK TRAILERS">RGN OR REMOVABLE GOOSENECK TRAILERS</option>
                                            <option value="STRETCH RGN OR REMOVABLE GOOSENECK TRAILERS">STRETCH RGN OR REMOVABLE GOOSENECK TRAILERS</option>
                                            <option value="CONESTOGA TRAILERS">CONESTOGA TRAILERS</option>
                                            <option value="SIDE KIT TRAILERS">SIDE KIT TRAILERS</option>
                                            <option value="POWER ONLY">POWER ONLY</option>
                                            <option value="SPECIALIZED TRAILERS">SPECIALIZED TRAILERS</option>
                                            <option value="SEMI TRAILER">SEMI TRAILER</option>
                                            <option value="JUMBO- BOX TRAILER">JUMBO- BOX TRAILER</option>
                                            <option value="MEGA TRAILER">MEGA TRAILER</option>
                                            <option value="REEFER TRAILER">REEFER TRAILER</option>
                                            <option value="CURTAIN TRAILER">CURTAIN TRAILER</option>
                                            <option value="TARPAULIN TRAILER">TARPAULIN TRAILER</option>
                                            <option value="SET">SET</option>
    			                        </select>
    			                        <select name="calculaion[]" class="form-control cal_sea_lcl cal_selc" style="display:none">
    			                            <option value="">Select Calculation Type</option>
    			                            <option value="Cubic Meter">Cubic Meter</option>
    			                            <option value="SET">SET</option>
    			                        </select>
    			                        <select name="calculaion[]" class="form-control cal_sea_fcl cal_selc" style="display:none">
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
                                            <option value="SET">SET</option>
    			                        </select>
    			                        <select name="calculaion[]" class="form-control select_for_air cal_selc">
    			                            <option value="">Select Calculation Type</option>
                             				<option value="KG">KG</option>
                             				<option value="SET">SET</option>
    			                        </select>
    			                    </div>
    			                </div>
                                
                                <div class="col-md-2 select_for_air cal_selc" style="display:none">
                             		<div class="form-group">
                             			<label for="">Quantity (Above )</label>
                             			<select name="quantity[]" class="form-control qunty_for_air">
                             			    <option value="">Select Quantity</option>
                             			    <option value="45">45</option>
                                            <option value="100">100</option>
                                            <option value="300">300</option>
                                            <option value="500">500</option>
                                            <option value="1000">1000</option>
                                            <option value="SET">SET</option>
                             			</select>
                             		</div>
                             	</div>
                             	
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Currency</label>
    			                        <select name="currency[]" class="form-control currency" required>
    			                            <option value="">Select Currency</option>
    			                            <option value="American Dollar">American Dollar</option>
    			                            <option value="Euro">Euro</option>
    			                            <option value="Great British Pound">Great British Pound</option>
    			                            <option value="Turish Lira">Turish Lira</option>
    			                            <option value="Australian Dollar">Australian Dollar</option>
    			                            <option value="Canadian Dollar">Canadian Dollar</option>
    			                        </select>
                                    </div>                                           
                                </div>
                                
                                <div class="col-md-2">
    			                    <div class="form-group">
    			                        <label>Price( in numbers only )</label>
    			                        <input type="text" name="price[]"   class="form-control prc_transf" placeholder="Enter Price" required>
    			                    </div>
    			                </div>
    			                
    			                <div class="col-md-2">
    			                    <div class="form-group append_btn_dlt">
    			                        <span class="btn btn-success mt-4" id="AddMoreForOffer" >Add More</span>
    			                    </div>
    			                </div>
                            </div>
                            <div class="appendhere"></div>
                                   
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Custom Notes</label>
                                    <textarea name="custom_note" class="form-control" placeholder="Enter Notes"></textarea>
                                </div>                                           
                            </div>       
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary" value="submit">  
                                </div>                                           
                            </div>
                           </div>
                        </div>
                    </form>
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
                <h3>Under maintenance</h3>
            </div>
        </div>
    @endif
@endsection