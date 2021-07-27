@extends('layouts.usertemplate')
@section('content')
<!-- Top Bar End -->
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-right">
                </div>
                <h4 class="page-title">Add New Vessel Schedule</h4>
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
                        <form id="addvesselschedule" action="{{ route('vessel.schedule.add') }}" method="post" enctype="mutipart/form-data" class="col-md-12">
                        @csrf
                        <div class="row">    
                            <div class="col-md-3">
                                <div class="form-group">
                                	<label>vessel name</label>
                                	<select class="form-control @if ($errors->has('vessel_name')) has-error @endif" id="vessel_name" name="vessel_name" required>
                                        <option value="">vessel name</option>
                                        <?php foreach ($vessels as $vessel): ?>
                                            <option value="<?php echo $vessel->id; ?>">
                                                {{ $vessel->vessel_name }}
                                            </option>
                                        <?php endforeach; ?>
                                    </select>                                    
                                </div> 
                            </div>
                                   
                            <div class="col-md-3">
                                <div class="form-group">
                                	<label>Imo Number</label>
                                    <input type="text" class="form-control @if ($errors->has('imo_no')) has-error @endif" id="imo_no" name="imo_no" placeholder="Imo No" disabled>
                                </div> 
                            </div>
                                   
                            <div class="col-md-3">
                            	<label>vessel flag</label>
                                <div class="form-group">
                                    <input type="text" class="form-control @if ($errors->has('flag')) has-error @endif" id="flag" name="flag" placeholder="Flag" value="{{ Request::old('flag') }}" disabled>
                                </div> 
                            </div>
                                   
                            <div class="col-md-3">
                            	<label>built date</label>
                                <div class="form-group">
                                    <input type="text"  class="form-control @if ($errors->has('built_date')) has-error @endif" id="built_date" name="built_date" placeholder="Built Date Only year" value="{{ Request::old('built_date') }}" disabled>
                                </div> 
                            </div>
                                   
                            <div class="col-md-4">
                            	<label>voyage no</label>
                                <div class="form-group">
                                    <input type="text" class="form-control @if ($errors->has('voyage_no')) has-error @endif" name="voyage_no" placeholder="voyage no" value="{{ Request::old('voyage_no') }}" required>
                                </div> 
                            </div>                         
                            
                            <div class="col-md-4">
                            	<label>liner agent</label>                            	
                                <div class="form-group">
                                    <input type="text" class="form-control @if ($errors->has('liner_agent')) has-error @endif" id="liner_agent" name="liner_agent" placeholder="liner agent" value="{{ Request::old('liner_agent') }}" required>
                                </div>                                           
                            </div>

                            <div class="col-md-4">
                            	<label>Ship role</label>
                                <div class="form-group">
                                    <select class="form-control @if ($errors->has('ship_role')) has-error @endif" id="ship_role" name="ship_role" required>
                                        <option value="main_vessel">main vessel</option>
                                        <option value="feeder_vessel">feeder vessel</option>
                                        <option value="bulk_vessel">bulk vessel</option>
                                        <option value="tanker">tanker</option>
                                    </select>
                                </div> 
                            </div>
                               
                            <div class="col-md-4">
                            	<label>departure country</label>
                                <div class="form-group">
                                    <select class="form-control @if ($errors->has('departure_country')) has-error @endif" id="departure_country" name="departure_country" required>
                                        <option value="">Departure Country</option>
                                        <?php foreach ($countryname_seaports as $countryname_seaport): ?>
                                            <option value="<?php echo $countryname_seaport->Countries; ?>">
                                                {{ $countryname_seaport->Countries }}
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div> 
                            </div>
                               
                            <div class="col-md-4">
                            	<label>departure port</label>
                                <div class="form-group">
                                    <select class="form-control @if ($errors->has('departure_port')) has-error @endif" id="departure_port" name="departure_port" required>
                                        <option>Departure port</option>                                        
                                    </select>
                                </div> 
                            </div>
                               
                            <div class="col-md-4">
                            	<label>est departure date</label>
                                <div class="form-group">
                                    <input type="date" class="form-control @if ($errors->has('est_departure_date')) has-error @endif" name="est_departure_date" value="{{ Request::old('est_departure_date') }}" required>
                                </div> 
                            </div>
                               
                            <div class="col-md-4">
                            	<label>arrival country</label>
                                <div class="form-group">
                                    <select class="form-control @if ($errors->has('arrival_country')) has-error @endif" id="arrival_country" name="arrival_country" required>
                                        <option value="">Arrival Country</option>
                                        <?php foreach ($countryname_seaports as $countryname_seaport): ?>
                                            <option value="<?php echo $countryname_seaport->Countries; ?>">
                                                {{ $countryname_seaport->Countries }}
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div> 
                            </div>
                               
                            <div class="col-md-4">
                            	<label>arrival port</label>
                                <div class="form-group">
                                    <select class="form-control @if ($errors->has('arrival_port')) has-error @endif" id="arrival_port" name="arrival_port" required>
                                        <option>arrival port</option>
                                    </select>
                                </div> 
                            </div>
                               
                            <div class="col-md-4">
                            	<label>est arrival date</label>
                                <div class="form-group">
                                    <input type="date" class="form-control @if ($errors->has('est_arrival_date')) has-error @endif" name="est_arrival_date" value="{{ Request::old('est_arrival_date') }}" required>
                                </div> 
                            </div>
                               
                            <div class="col-md-4">
                            	<label>loading date</label>
                                <div class="form-group">
                                    <input type="date" class="form-control @if ($errors->has('loading_date')) has-error @endif" name="loading_date" placeholder="loading date" value="{{ Request::old('loading_date') }}" required>
                                </div> 
                            </div>
                               
                            <div class="col-md-4">
                            	<label>decl surrender office</label>
                                <div class="form-group">
                                    <input type="text" class="form-control @if ($errors->has('decl_surrender_office')) has-error @endif" name="decl_surrender_office" placeholder="decl surrender office" value="{{ Request::old('decl_surrender_office') }}" required>
                                </div> 
                            </div>
                               
                            <div class="col-md-4">
                            	<label>cut off date</label>
                                <div class="form-group">
                                    <input type="date" class="form-control @if ($errors->has('cut_off_date')) has-error @endif" name="cut_off_date" placeholder="cut off date" value="{{ Request::old('cut_off_date') }}" required>
                                </div> 
                            </div>
                
                            <div class="col-md-4">
                                <label>Terminal</label>
                                <div class="form-group">
                                    <input type="text" class="form-control @if ($errors->has('terminal')) has-error @endif" name="terminal" placeholder="terminal" value="{{ Request::old('terminal') }}" required>
                                </div> 
                            </div>               

                            <div class="col-md-4">
                            	<label>Booking Ref No</label>
                                <div class="form-group">
                                    <input type="text" class="form-control @if ($errors->has('booking_ref_no')) has-error @endif" name="booking_ref_no" placeholder="Booking Ref No." value="{{ Request::old('booking_ref_no') }}" required>
                                </div> 
                            </div>
                                               
                            <div class="col-md-4">
                                <label>Warehouse Stuffing Attendant</label>
                                <div class="form-group">
                                    <input type="text" class="form-control @if ($errors->has('warehouse_stuffing_att')) has-error @endif" name="warehouse_stuffing_att" placeholder="Warehouse stuffing Attendant" value="{{ Request::old('warehouse_stuffing_att') }}" required>
                                </div> 
                            </div>
                                               
                            <div class="col-md-12">
                                <label>Container Number</label>
                                <div class="form-group">
                                    <input type="text" class="form-control @if ($errors->has('container_no')) has-error @endif" name="container_no" placeholder="Container Number" value="{{ Request::old('container_no') }}" required>
                                </div> 
                            </div>
                               
                            <div class="col-md-12">
                                <label>notes</label>
                                <div class="form-group">
                                    <input type="text" class="form-control @if ($errors->has('notes')) has-error @endif" name="notes" placeholder="notes" value="{{ Request::old('notes') }}" required>
                                </div> 
                            </div>
                                                               
                            <div class="col-md-3">                        	
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary" value="submit">  
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