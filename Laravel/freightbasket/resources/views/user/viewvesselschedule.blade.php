@extends('layouts.usertemplate')
@section('content')
<!-- Top Bar End -->
<!-- Page-Title -->
<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
			</div>
			<h4 class="page-title">Vessel Schedule<a href="{{ route('vessel.schedule.edit', $vessel_schedules->vsID) }}" class="btn btn-success float-right">Edit</a></h4>
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
		            <div class="col-md-12 p-5">
	                 	<table class="table table-striped  table-bordered text-uppercase">
				            <tr>
					            <th>BOOKING REF. NO</th>
					            <th>Vessel Name</th>
					            <th>Voyage Number</th>
					            <th>Imo Number</th>
					            <th>Flag</th>
					            <th>Built Date</th>
				            </tr>
				            <tr>
				             	<td>{{ $vessel_schedules->booking_ref_no }}</td>
				                <td>{{ $vessel_schedules->vessel_name }}</td>
				                <td>{{ $vessel_schedules->voyage_no }}</td>
				                <td>{{ $vessel_schedules->imo_no }}</td>
				                <td>{{ $vessel_schedules->flag }}</td>
				                <td>{{ $vessel_schedules->built_date }}</td>
				            </tr>
			            </table>
		                <table class="table table-striped  table-bordered text-uppercase">
					        <tr>
					            <th>DEPT Country</th>
					            <th>DEPT Port</th>
					            <th>Terminal</th>
					            <th>EST DEPT Date</th>
					            <th>Arrival Country</th>
					            <th>Arrival Port</th>
					            <th>EST Arrival Date</th>
					        </tr>
					        <tr>
				             	<td>{{ $vessel_schedules->departure_country }}</td>
				             	<td>{{ $vessel_schedules->departure_port }}</td>
				             	<td>{{ $vessel_schedules->terminal }}</td>
				             	<td>{{ $vessel_schedules->est_departure_date }}</td>
				             	<td>{{ $vessel_schedules->arrival_country }}</td>
				             	<td>{{ $vessel_schedules->arrival_port }}</td>
				             	<td>{{ $vessel_schedules->est_arrival_date }}</td>
				            </tr>
			            </table>
		                <table class="table table-striped  table-bordered text-uppercase">
					        <tr>
					            <th>Loading Date</th>
					            <th>Decl DELIVERY Office</th>
					            <th>Cut Off Date</th>
					            <th>W/HOUSE ATTENDENT</th>
					            <th>Liner Agent</th>
					            <th>Ship Role</th>
				            </tr>
					        <tr>
				             	<td>{{ $vessel_schedules->loading_date }}</td>
				             	<td>{{ $vessel_schedules->decl_surrender_office }}</td>
				             	<td>{{ $vessel_schedules->cut_off_date }}</td>
				             	<td>{{ $vessel_schedules->warehouse_stuffing_att }}</td>
				                <td>{{ $vessel_schedules->liner_agent }}</td>
				             	<td>{{ $vessel_schedules->ship_role }}</td>
					        </tr>
					    </table>					   
					    <table class="table table-striped  table-bordered text-uppercase">
					        <tr width="100%">
					            <th width="100%">Conatainer Number</th>
					        </tr>
					        <tr width="100%">
				             	<td width="100%">{{ $vessel_schedules->container_no }}</td>
					        </tr>					   
					    </table>						   
					    <table class="table table-striped  table-bordered text-uppercase">
					        <tr width="100%">
					            <th width="100%">Notes</th>
					        </tr>
					        <tr width="100%">
				             	<td width="100%">{{ $vessel_schedules->notes }}</td>
					        </tr>					   
					    </table>					   
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