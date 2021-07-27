@extends('layouts.usertemplate')
@section('content')
<!-- Top Bar End -->
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-right">
                </div>
                <h4 class="page-title">Edit  Vessel</h4>
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
                        <form id="editvessel" action="{{ route('vessel.edit', $vessels['id']) }}" method="post" enctype="mutipart/form-data" class="col-md-12">
                        @csrf

                        <div class="row">    
                            <div class="col-md-4">
                                <div class="form-group">
                                	<label>vessel name</label>
                                	<input type="text" class="form-control @if ($errors->has('vessel_name')) has-error @endif" id="vessel_name" name="vessel_name" placeholder="Imo No" value="{{ $vessels['vessel_name'] }}" required>                                  
                                </div> 
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                	<label>Imo Number</label>
                                    <input type="text" class="form-control @if ($errors->has('imo_no')) has-error @endif" id="imo_no" name="imo_no" placeholder="Imo No" value="{{ $vessels['imo_no'] }}" required>
                                </div> 
                            </div>
                                
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Flag</label>
                                    <select class="form-control @if ($errors->has('flag')) has-error @endif" id="flag" name="flag" required>
                                        <option value="">Country Flag</option>
                                        <?php foreach ($countryname as $countryname1): ?>
                                            <option {{ ( $countryname1->name == $vessels['flag']) ? 'selected' : '' }} value="<?php echo $countryname1->name; ?>">
                                                {{ $countryname1->name }}
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div> 
                            </div>
                                   
                            <div class="col-md-4">
                            	<label>built date</label>
                                <div class="form-group">
                                    <input type="text"  class="form-control @if ($errors->has('built_date')) has-error @endif" id="built_date" name="built_date" placeholder="Built Date Only year" value="{{ Request::old('built_date') }} {{ $vessels['built_date'] }}" required>
                                </div> 
                            </div> 
                            
                            <div class="col-md-4">
                            	<label>nmsi </label>                            	
                                <div class="form-group">
                                    <input type="text" class="form-control @if ($errors->has('nmsi')) has-error @endif" id="nmsi" name="nmsi" placeholder="NMSI" value="{{ $vessels['nmsi'] }}" >
                                </div>                                           
                            </div>
                            
                            <div class="col-md-4">
                            	<label>Call Sign</label>                            	
                                <div class="form-group">
                                    <input type="text" class="form-control @if ($errors->has('call_sign')) has-error @endif" id="call_sign" name="call_sign" placeholder="Call Siign" value="{{ $vessels['call_sign'] }}" >
                                </div>                                           
                            </div>
                                                               
                            <div class="col-md-12">                        	
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