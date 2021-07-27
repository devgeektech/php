@extends('layouts.app')
@section('content')
@section('class', 'inner-page-main')
<!-- Top Bar End -->
<!-- Page-Title -->
<div class="container">
    <div class="row">
    	<div class="col-sm-12">
    		<div class="page-title-box">
    			<div class="float-right">
    			</div>
    			<h4 class="page-title">Confirmation Offer</h4>
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
    		        <h2>Check You email Notification about this process.</h2>   
    		        <h3>{{ $success }}</h3>
    			</div>
            </div>
    	</div>
    </div>
</div>
@endsection