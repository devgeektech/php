@extends('layouts.usertemplate')
@section('content')
<!-- Top Bar End -->
<!-- Page-Title -->
<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
			</div>
			<h4 class="page-title">Vessel<a href="{{ route('vessel.edit', $vessels->id) }}" class="btn btn-success float-right">Edit</a></h4>
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
					            <th>Vessel Name</th>
					            <th>Imo Number</th>
					            <th>Flag</th>
					            <th>Built Date</th>
					            <th>NMSI</th>
					            <th>CALL SIGN</th>
				            </tr>
				            <tr>
				                <td>{{ $vessels->vessel_name }}</td>
				                <td>{{ $vessels->imo_no }}</td>
				                <td>{{ $vessels->flag }}</td>
				                <td>{{ $vessels->built_date }}</td>
				                <td>{{ $vessels->nmsi }}</td>
				                <td>{{ $vessels->call_sign }}</td>
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