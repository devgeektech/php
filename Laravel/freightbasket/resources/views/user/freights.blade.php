@extends('layouts.usertemplate')
@section('content')
<!-- Top Bar End -->
<!-- Page-Title -->
<div class="row">
	<div class="col-sm-12">
		
		<div class="page-title-box">
			<div class="float-right">
			</div>
			<h4 class="page-title">Manage Freights</h4>
		</div>
		
		@if (Session::has('success'))
		<p class="alert alert-success">{!! Session::get('success') !!}<span class="close" style="cursor:pointer;" data-dismiss="alert">&times;</span></p>
		@endif
		@if (Session::has('error'))
		<p class="alert alert-danger">{!! Session::get('error') !!}<span class="close" style="cursor:pointer;" data-dismiss="alert">&times;</span></p>
		@endif
	</div>
</div>
	
<div class="row">	
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<table class="table table-stripped table-bordered" id="datatable">
                  	<thead>
                      <tr>
                          <th>Service No.</th>
                          <th>Category</th>
                          <!--<th>Type</th>-->
                          <th>Departure Port</th>
                          <th>Arriaval Port</th>
                          <th>Validity Date</th>
                          <th>Action</th>
                      </tr>
                  	</thead>
                  	<tbody>
	              
					@foreach($freights as $freight)
	                       @include('freights.index')
	              	@endforeach
	                
             	 	</tbody>
                </table>
			</div>
		</div>
	</div>
</div>
<!-- testing modal -->
<!-- Button trigger modal -->
<!-- Modal -->

@endsection