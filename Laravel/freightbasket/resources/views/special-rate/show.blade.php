@extends('layouts.usertemplate')
@section('content')
<!-- Top Bar End -->
<!-- Page-Title -->
<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
			</div>
			<h4 class="page-title">Special Rate Request</h4>
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
			 
		        <div class="row">
		            <div class="col-md-12 p-5">
	                 	<table class="table table-striped  table-bordered text-uppercase">
				            <tr>
					            <th></th>
				            	<td></td>
			                </tr>			                
			            </table>		   
				    </div>
		        </div>
			   
			</div>
		</div>
	</div>
</div>
@endsection