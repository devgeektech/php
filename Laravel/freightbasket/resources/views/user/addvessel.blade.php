@extends('layouts.usertemplate')
@section('content')
<!-- Top Bar End -->
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-right">
                </div>
                <h4 class="page-title">Add New Vessel</h4>
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
                        <form id="addfreight" action="{{ action('VesselController@store') }}" method="post" enctype="mutipart/form-data" class="col-md-12">
                        @csrf
                        <div class="row">    
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control @if ($errors->has('vessel_name')) has-error @endif" name="vessel_name" placeholder="vessel name" value="{{ Request::old('vessel_name') }}" required>
                                </div> 
                            </div>
                                   
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control @if ($errors->has('nmsi')) has-error @endif" name="nmsi" placeholder="NMSI" value="{{ Request::old('nmsi') }}"   >
                                </div> 
                            </div>
                                   
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control @if ($errors->has('call_sign')) has-error @endif" name="call_sign" placeholder="Call Sign" value="{{ Request::old('call_sign') }}">
                                </div> 
                            </div>
                                   
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="number"  class="form-control @if ($errors->has('built_date')) has-error @endif" name="built_date" placeholder="Built Date Only year" value="{{ Request::old('built_date') }}" required>
                                </div> 
                            </div>
                                   
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="number" class="form-control @if ($errors->has('imo_no')) has-error @endif" name="imo_no" placeholder="Imo Number" value="{{ Request::old('imo_no') }}" required>
                                </div> 
                            </div>  
                                                   
                                   
                            <div class="col-md-6">
                                <div class="form-group">
                                    <select class="form-control @if ($errors->has('flag')) has-error @endif" id="flag" name="flag" required>
                                        <option value="">Country Flag</option>
                                        <?php foreach ($countryname as $countryname1): ?>
                                            <option value="<?php echo $countryname1->name; ?>">
                                                {{ $countryname1->name }}
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div> 
                            </div>
                                   
                            <div class="col-md-6">
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