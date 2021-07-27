@extends('layouts.usertemplate')
@section('content')
<!-- Top Bar End -->
<!-- Page-Title -->

<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
			</div>
			<h4 class="page-title">Dashboard</h4>
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
	<div class="col-lg-3">
		
		<div class="card">
			<div class="card-body">
			    
			    @if($singleuser->avatar == "users/default.png" )
			    <img src="{{ asset('uploads/profiles/default-user-photo.jpg') }}" alt="" class="img-fluid mx-auto d-block">
			    @else
			    <img src="{{ asset($singleuser->avatar) }}" alt="" class="img-fluid mx-auto d-block">
			    @endif
				
				<h5>About</h5>
				<h4 class="mt-2 header-title"><span class="mr-2"><i class="fa fa-user"></i></span>{{ $singleuser->name }}</h4>
				<p class="mb-2"><i class="fa fa-envelope"></i><span class="text-mute ml-2">{{ $singleuser->email }}</span></p>
				<p><i class="fa fa-phone"></i><span class="text-mute ml-2">{{ $singleuser->phone }}</span></p>
			</div>
		</div>
	</div>
	<div class="col-md-9">
		<div class="card">
			<div class="card-body">
				<nav class="nav nav-pills nav-justified">
					<a class="nav-item nav-link bg-secondary text-white active" href="#tab1" data-toggle="tab">Tab1</a>
					<a class="nav-item nav-link bg-success text-white" href="#tab2"  data-toggle="tab">Tab2</a>
					<a class="nav-item nav-link bg-secondary text-white" href="#tab3"  data-toggle="tab">Tab3</a>
				</nav>
				<div class="tab-content" id="myTabContent">
					<!-- first tab -->
					<div class="tab-pane fade show active" id="tab1">
						<h3 class="mt-3">Under Maintenance</h3>
						
					</div>
					<!-- end of first tab -->
					
					<!-- second tab -->
					<div class="tab-pane fade show " id="tab2">
						
						<h3 class="mt-3">Under Maintenance</h3>
					
					</div>
					<div class="tab-pane fade show " id="tab3">
					    </div>
					<!-- end of second tab -->
				
				</div>
			</div>
		</div>
	</div>
</div>
@endsection