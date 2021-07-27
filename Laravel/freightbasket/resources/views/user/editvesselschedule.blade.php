@extends('layouts.usertemplate')
@section('content')
<!-- Top Bar End -->
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-right">
                </div>
                <h4 class="page-title">Edit  Vessel Schedule</h4>
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
                        <form id="addvesselschedule" action="{{ route('vessel.schedule.edit', $vessel_schedules['vsID']) }}" method="post" enctype="mutipart/form-data" class="col-md-12">
                        @csrf

                        <div class="row">    
                            <div class="col-md-3">
                                <div class="form-group">
                                	<label>vessel name</label>
                                	<select class="form-control @if ($errors->has('vessel_id')) has-error @endif" id="vessel_name" name="vessel_id" required>
                                        <option value="">vessel name</option>
                                        <?php foreach ($vessels as $vessel_key => $vessel): ?>
                                            <option value="<?php echo $vessel->id; ?>" {{ ( $vessel_key == $vessel_schedules['vessel_id']) ? 'selected' : '' }}>
                                                {{ $vessel->vessel_name }}
                                            </option>
                                        <?php endforeach; ?>
                                    </select>                                    
                                </div> 
                            </div>
                                   
                            <div class="col-md-3">
                                <div class="form-group">
                                	<label>Imo Number</label>
                                    <input type="text" class="form-control @if ($errors->has('imo_no')) has-error @endif" id="imo_no" name="imo_no" placeholder="Imo No" value="{{ $vessel_schedules['imo_no'] }}" disabled>
                                </div> 
                            </div>
                                   
                            <div class="col-md-3">
                            	<label>vessel flag</label>
                                <div class="form-group">
                                    <input type="text" class="form-control @if ($errors->has('flag')) has-error @endif" id="flag" name="flag" placeholder="Flag" value="{{ Request::old('flag') }} {{ $vessel_schedules['flag'] }}"  disabled>
                                </div> 
                            </div>
                                   
                            <div class="col-md-3">
                            	<label>built date</label>
                                <div class="form-group">
                                    <input type="text"  class="form-control @if ($errors->has('built_date')) has-error @endif" id="built_date" name="built_date" placeholder="Built Date Only year" value="{{ Request::old('built_date') }} {{ $vessel_schedules['built_date'] }}" disabled>
                                </div> 
                            </div>
                                   
                            <div class="col-md-4">
                            	<label>voyage no</label>
                                <div class="form-group">
                                    <input type="text" class="form-control @if ($errors->has('voyage_no')) has-error @endif" name="voyage_no" placeholder="voyage no" value="{{ Request::old('voyage_no') }} {{ $vessel_schedules['voyage_no'] }}"  required>
                                </div> 
                            </div>                         
                            
                            <div class="col-md-4">
                            	<label>liner agent</label>                            	
                                <div class="form-group">
                                    <input type="text" class="form-control @if ($errors->has('liner_agent')) has-error @endif" id="liner_agent" name="liner_agent" placeholder="liner agent" value="{{ $vessel_schedules['liner_agent'] }}" required>
                                </div>                                           
                            </div>

                            <div class="col-md-4">
                            	<label>Ship role</label>
                                <div class="form-group">
                                    <select class="form-control @if ($errors->has('ship_role')) has-error @endif" id="ship_role" name="ship_role" required>
                                        <option {{ ( $vessel_schedules['ship_role'] == "main_vessel") ? 'selected' : '' }} value="main_vessel">main vessel</option>
                                        <option {{ ( $vessel_schedules['ship_role'] == "feeder_vessel") ? 'selected' : '' }} value="feeder_vessel">feeder vessel</option>
                                        <option {{ ( $vessel_schedules['ship_role'] == "bulk_vessel") ? 'selected' : '' }} value="bulk_vessel">bulk vessel</option>
                                        <option {{ ( $vessel_schedules['ship_role'] == "tanker") ? 'selected' : '' }} value="tanker">tanker</option>
                                    </select>
                                </div> 
                            </div>
                               
                            <div class="col-md-4">
                            	<label>departure country</label>
                                <div class="form-group">
                                    <select class="form-control @if ($errors->has('departure_country')) has-error @endif" id="departure_country" name="departure_country" required>
                                        <option value="">Departure Country</option>
                                        <?php foreach ($countryname_seaports as $countryname_seaport): 
                                        	$seaportt = $countryname_seaport->Countries;
                                        	?>
                                            <option {{ ( $vessel_schedules['departure_country'] == $seaportt) ? 'selected' : '' }} value="<?php echo $countryname_seaport->Countries; ?>">
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
                                        <option value="{{ $vessel_schedules['departure_port'] }}">{{ $vessel_schedules['departure_port'] }}</option>                                        
                                    </select>
                                </div> 
                            </div>
                               
                            <div class="col-md-4">
                            	<label>est departure date</label>
                                <div class="form-group">
                                    <input type="date" class="form-control @if ($errors->has('est_departure_date')) has-error @endif" name="est_departure_date" value="{{ $vessel_schedules['est_departure_date'] }}" required>
                                </div> 
                            </div>
                               
                            <div class="col-md-4">
                            	<label>arrival country</label>
                                <div class="form-group">
                                    <select class="form-control @if ($errors->has('arrival_country')) has-error @endif" id="arrival_country" name="arrival_country" required>
                                        <option value="">Departure Country</option>
                                        <?php foreach ($countryname_seaports as $countryname_seaport): 
                                        	$seaportt1 = $countryname_seaport->Countries;
                                        	?>
                                            <option {{ ( $vessel_schedules['arrival_country'] == $seaportt1) ? 'selected' : '' }} value="<?php echo $countryname_seaport->Countries; ?>">
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
                                        <option value="{{$vessel_schedules['arrival_port']}}">{{$vessel_schedules['arrival_port']}}</option>
                                    </select>
                                </div> 
                            </div>
                               
                            <div class="col-md-4">
                            	<label>est arrival date</label>
                                <div class="form-group">
                                    <input type="date" class="form-control @if ($errors->has('est_arrival_date')) has-error @endif" name="est_arrival_date" value="{{ $vessel_schedules['est_arrival_date'] }}" required>
                                </div> 
                            </div>
                               
                            <div class="col-md-4">
                            	<label>loading date</label>
                                <div class="form-group">
                                    <input type="date" class="form-control @if ($errors->has('loading_date')) has-error @endif" name="loading_date" placeholder="loading date" value="{{ $vessel_schedules['loading_date'] }}" required>
                                </div> 
                            </div>
                               
                            <div class="col-md-4">
                            	<label>decl surrender office</label>
                                <div class="form-group">
                                    <input type="text" class="form-control @if ($errors->has('decl_surrender_office')) has-error @endif" name="decl_surrender_office" placeholder="decl surrender office" value="{{ $vessel_schedules['decl_surrender_office'] }}" required>
                                </div> 
                            </div>
                               
                            <div class="col-md-4">
                            	<label>cut off date</label>
                                <div class="form-group">
                                    <input type="date" class="form-control @if ($errors->has('cut_off_date')) has-error @endif" name="cut_off_date" placeholder="cut off date" value="{{ $vessel_schedules['cut_off_date'] }}" required>
                                </div> 
                            </div>
                
                            <div class="col-md-4">
                                <label>Terminal</label>
                                <div class="form-group">
                                    <input type="text" class="form-control @if ($errors->has('terminal')) has-error @endif" name="terminal" placeholder="terminal" value="{{ $vessel_schedules['terminal'] }}" required>
                                </div> 
                            </div>               

                            <div class="col-md-4">
                            	<label>Booking Ref No</label>
                                <div class="form-group">
                                    <input type="text" class="form-control @if ($errors->has('booking_ref_no')) has-error @endif" name="booking_ref_no" placeholder="Booking Ref No." value="{{ $vessel_schedules['booking_ref_no'] }}" required>
                                </div> 
                            </div>
                                               
                            <div class="col-md-4">
                                <label>Warehouse Stuffing Attendant</label>
                                <div class="form-group">
                                    <input type="text" class="form-control @if ($errors->has('warehouse_stuffing_att')) has-error @endif" name="warehouse_stuffing_att" placeholder="Warehouse stuffing Attendant" value="{{ $vessel_schedules['warehouse_stuffing_att'] }}" required>
                                </div> 
                            </div>

                            <div class="col-md-12">
                                <label>Container Number</label>
                                <div class="form-group">
                                    <input type="text" class="form-control @if ($errors->has('container_no')) has-error @endif" name="container_no" placeholder="Container Number" value="{{ $vessel_schedules['container_no'] }}" required>
                                </div> 
                            </div>
                               
                            <div class="col-md-12">
                                <label>notes</label>
                                <div class="form-group">
                                    <input type="text" class="form-control @if ($errors->has('notes')) has-error @endif" name="notes" placeholder="notes" value="{{ $vessel_schedules['notes'] }}" required>
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