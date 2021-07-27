<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>{{ config('app.name', 'Laravel') }} | Dashboard</title>
		<meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
		<meta content="Premium Multipurpose Admin & Dashboard Template" name="description">
		<meta content="" name="author"><meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="_token" content="{{csrf_token()}}" />
		<!-- App favicon -->
		<link rel="shortcut icon" href="https://mannatthemes.com/metrica/metrica_live/assets/images/favicon.ico">
		<!-- App css -->
		<link href="{{asset('user/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
		<link href="{{ asset('user/css/jquery-ui.min.css') }}" rel="stylesheet">
		<link href="{{ asset('user/css/icons.min.css') }}" rel="stylesheet" type="text/css">
		<link href="{{ asset('user/css/metisMenu.min.css') }}" rel="stylesheet" type="text/css">
		<link href="{{ asset('user/css/app.min.css') }}" rel="stylesheet" type="text/css">
		<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/css/multi-select.min.css" integrity="sha256-l4jVb17uOpLwdXQPDpkVIxW9LrCRFUjaZHb6Fqww/0o=" crossorigin="anonymous" />-->
		<link href="{{ asset('css/userdashboard_main.css') }}" rel="stylesheet">
		<link href="{{ asset('css/bootstrap-multiselect.css') }}" rel="stylesheet">
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.4/css/jquery.dataTables.min.css">
		<link rel="stylesheet" href="//cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">
		<link rel="stylesheet" href="//cdn.datatables.net/1.10.2/css/jquery.dataTables.css">
		<!----Datatable script-------->
		@yield('css-styles')
		<style>
		    .help-block{
		        margin-top:2rem;
		    }
		</style>
		<script type="text/javascript">var path = {!! json_encode(url('/')) !!}</script>
	</head>
	<body class="dark-sidenav">
	    <div class="se-pre-con"></div>
		<!-- Left Sidenav -->
		<div class="left-sidenav">
			<!-- LOGO -->
			<div class="topbar-left">
				<a href="" class="logo">
					<span>
						<img src="{{ asset('images/logo.png') }}" alt="logo-small" class="logo-sm" style="height:40px;">
					</span>
					
				</a>
			</div>
			<!--end logo-->
			<div class="leftbar-profile p-3 w-100">
				<div class="media position-relative">
					<div class="leftbar-user online">
						@if(Auth::user()->avatar == "users/default.png" )
					    <img src="{{ asset('uploads/profiles/default-user-photo.jpg') }}" alt="" class="thumb-md rounded-circle">
					    @else
					    <img src="{{ asset( 'uploads/profiles') }}/{{ Auth::user()->avatar }}" alt="" class="thumb-md rounded-circle">
					    @endif
					</div>
					<div class="media-body align-self-center text-truncate ml-3">
						<h5 class="mt-0 mb-1 font-weight-semiboldk">{{ Auth::user()->name }}</h5>
						<p class="text-muted text-uppercase mb-0 font-12">Dashboard</p>
					</div>
					<!--end media-body-->
				</div>
			</div>
			<ul class="metismenu left-sidenav-menu slimscroll">
				<li class="nav-item {{ (request()->segment(1) == 'Udashboard') ? 'active' : '' }}">
					<a class="nav-link" href="{{ route('Udashboard') }}">
						<i class="fa fa-home" aria-hidden="true"></i>
						    Dashboard
					</a>
				</li>
			
				@if(Auth::User()->role_id == "6")
					<li class="nav-item">
							<a class="nav-link" href="{{ route('Udashboard') }}">
    							<i class="fa fa-home" aria-hidden="true"></i>
    						    Request Company
    						</a>
					</li>
				@else
					<li class="nav-item {{ (request()->segment(1) == 'myprofile') ? 'active' : '' }}">
						<a class="nav-link" href="{{ route('myprofile') }}"><i class="fa fa-user" aria-hidden="true"></i>My Profile</a>
					</li>
					<li class="nav-item {{ (request()->segment(1) == 'companyprofile') ? 'active' : '' }}">
						<a class="nav-link" href="{{ route('companyprofile') }}"><i class="fa fa-address-card" aria-hidden="true"></i>Company Profile</a>
					</li>
					
					<li class="nav-item {{ (request()->segment(1) == 'managestaff') ? 'active' : '' }}">
					    <a class="nav-link" href="{{ route('managestaff') }}">
    					<i class="fa fa-venus-mars" aria-hidden="true"></i>Manage Staff</a>
    				</li>

					@if(Auth::User()->role_id == "3")
						<li class="nav-item {{ (request()->segment(2) == 'products') ? 'active' : '' }}">
						    <a class="nav-link" href="{{ url('user/products') }}">
	    					<i class="fa fa-venus-mars" aria-hidden="true"></i>Products</a>
	    				</li>
	    				<li class="nav-item {{ (request()->segment(2) == 'freights') ? 'active' : '' }}">
	    				    <a class="nav-link" href="{{ route('allFreights') }}">
	    					<i class="fa fa-cog" aria-hidden="true"></i>Services, Freights </a>
	    				</li>
					@endif

    				@php
    				$results = DB::select('select * from companydetails where user_id = ?', [Auth::user()->id]);
    				@endphp
    				@if(!empty($results) && !empty($results[0]->companyservice))
    					@php
	    				$result_fnl = unserialize($results[0]->companyservice)
    					@endphp
	    				
	    				@if (array_key_exists("fre-fwrs",$result_fnl))
		    				<li class="nav-item {{ (request()->segment(1) == 'freights') ? 'active' : '' }}">
		    				    <a class="nav-link" href="{{ route('managefreights') }}">
		    					<i class="fa fa-cog" aria-hidden="true"></i>Services, Freights </a>
		    				</li>
						@endif


						@if(array_key_exists("custom-brokers",$result_fnl))
							<li class="nav-item {{ (request()->segment(2) == 'services') ? 'active' : '' }}">
		    				    <a class="nav-link" href="{{ route('customerbrokerservices') }}">
		    					<i class="fa fa-cog" aria-hidden="true"></i>Services </a>
		    				</li>
						@endif
					@endif

					@if(Auth::User()->role_id == "4" || Auth::User()->role_id == "5")
    				<li class="nav-item {{ (request()->segment(1) == 'shipments') ? 'active' : '' }}">
    				    <a class="nav-link" href="#">
    					<i class="fa fa-cog" aria-hidden="true"></i>LOGISTICS E.R.P </a>
    					<ul class="dropdown-menu">
							<li><a href="{{ route('manageshipments') }}">Manage Shipment</a></li>
							<li><a href="{{ route('offer') }}">Send an Offer</a></li>
							<li><a href="{{ route('offer.received') }}">Offer Received</a></li>
						</ul>
    				</li>
					@endif
    				<li class="nav-item {{ (request()->segment(1) == 'shipments') ? 'active' : '' }}">
    				    <a class="nav-link" href="{{ route('customer') }}">
    					<i class="fa fa-cog" aria-hidden="true"></i>Customer Data </a>
    				</li>
					<li class="nav-item"> 
					    <a class="nav-link {{ (request()->segment(1) == 'vessel') ? 'active' : '' }}" href="{{ route('vessel') }}"><i class="fa fa-cog" aria-hidden="true"></i>Global Vessel dets</a>
				    </li>

				    <?php
    				if(!empty($results) && !empty($results[0]->companyservice)){
	    				// $result_fnl = unserialize($results[0]->companyservice);
	    				if (array_key_exists("fre-fwrs",$result_fnl)){ ?>
						    <li class="nav-item {{ (request()->segment(2) == 'schedule') ? 'active' : '' }}">
						        <a class="nav-link" href="{{ route('vessel.schedule') }}"><i class="fa fa-cog" aria-hidden="true"></i>Vessel Schedule</a>
		    				</li>
						<?php }
					} ?>

					<li  class="nav-item {{ (request()->segment(2) == 'special-rate') ? 'active' : '' }}"><a href="{{ route('user.specialrate') }}"><i class="fa fa-cog" aria-hidden="true"></i>Special Rates</a></li>

					<li class="nav-item {{ (request()->segment(2) == 'members') ? 'active' : '' }}">
    				    <a class="nav-link" href="#">
    					<i class="fa fa-cog" aria-hidden="true"></i>Members </a>
    					<ul class="dropdown-menu">
							<li><a href="{{ route('user.search.members', 'air') }}">Air Freight Co.</a></li>
							<li><a href="{{ route('user.search.members', 'land') }}">Land Freight Co.</a></li>
							<li><a href="{{ route('user.search.members', 'sea') }}">Sea Freight Co.</a></li>
							<li><a href="{{ route('user.search.members', 'custom-brokers') }}">Customs Broker</a></li>
							<li><a href="{{ route('user.search.members', 'International-land-fre-co') }}">International Land Fre companies</a></li>
							<li><a href="{{ route('user.search.members', 'lashing-securing') }}">Lashing and securing companies</a></li>
							<li><a href="{{ route('user.search.members', 'fumigation') }}">Fumigation companies</a></li>
							<li><a href="{{ route('user.search.members', 'insurance') }}">Insurance companies</a></li>
						</ul>
    				</li>

				@endif
						
			
	</ul>
</div>
<!-- end left-sidenav-->
	<!-- Top Bar Start -->
	<div class="topbar">
		<!-- Navbar -->
		<nav class="navbar-custom">
			<form class="form-inline cus-form">
						      <div class="md-form my-0">
						        <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
						        <button class="btn btn-md my-2 my-sm-0 ml-3 search-btn" type="submit"><i class="fa fa-search"></i></button>
						      </div>
						      
			</form>
		    <a href="{{ route('customer') }}" class="btn btn-info"> Customer Data </a>
		    
			<ul class="list-unstyled topbar-nav float-right mb-0">
				<li class="dropdown">
					<a class="nav-link waves-effect waves-light nav-user notifier_icn" href="{{ route('messenger', Auth::user()->id)}}" role="button" >
						<span id="notifier"><i class="fa fa-envelope"></i></span>
					</a>
				</li>
				<li class="dropdown">
					<a class="nav-link dropdown-toggle waves-effect waves-light nav-user notifier_icn" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
						<span id="notifier"><i class="fa fa-bell"></i></span>
					</a>
					<div class="dropdown-menu dropdown-menu-right notifier_data">
						@if(Auth::user()->notification)
							@if(!empty(Auth::user()->notification))
								@foreach(Auth::user()->notification as $notification)
									<a href="{{ url('/user/member_profile/'.$notification->user_id.'?notification='.$notification->id) }}">{{$notification->name}}</a>
								@endforeach
							@endif
						@endif
					</div>
				</li>
				<li class="dropdown">
					<a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
						@if(Auth::user()->avatar == "users/default.png" )
					    <img src="{{ asset('uploads/profiles/default-user-photo.jpg') }}" alt="" class="thumb-md rounded-circle">
					    @else
					    <img src="{{ asset( 'uploads/profiles') }}/{{ Auth::user()->avatar }}" alt="" class="thumb-md rounded-circle">
					    @endif
						<span class="ml-1 nav-user-name hidden-sm">{{ Auth::user()->name }}
							<i class="fa fa-chevron-down"></i>
						</span>
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<a class="dropdown-item" href="{{ route('myprofile') }}">
							<i class="fa fa-user text-muted mr-2"></i>
						Profile</a>
						<a class="dropdown-item" href="{{ url('/logout') }}">
							<i class="fa fa-power-off" aria-hidden="true"></i>

						Logout</a>
					</div>
				</li>
			</ul>
		</nav>
		<!-- end navbar-->
	</div>
	<div class="page-wrapper">
		<div class="page-content-tab">
			<div class="container-fluid">
				@yield('content')
				<footer class="footer text-center text-sm-left">&copy; 2019 - 2020 abc
					<span class="text-muted d-none d-sm-inline-block float-right">Crafted with <i class="fa fa-heart text-danger"></i> by abc</span>
				</footer>
			</div>
		</div>
	</div>
<script type="text/javascript">
    var BASE_URL = "{{ url('/') }}";
    var REQUEST_URL = "<?=Request::url()?>";
    var CSRF = "{{ csrf_token() }}";
    var WALL_ACTIVE = false;
</script>
<script src="{{ asset('user/js/jquery.min.js')}}"></script>
<script src="{{ asset('user/js/jquery-ui.min.js')}}"></script>
<script src="{{ asset('js/bootstrap-multiselect.js')}}"></script>
<script src="{{ asset('js/typehead.js')}}?{{ time() }}"></script>
<script src="{{ asset('user/js/custom.js')}}?{{ time() }}"></script>
<script src="{{ asset('user/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{ asset('user/js/bootstrap.bundle.min.js')}}"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.9/js/bootstrap-dialog.min.js"></script>
<script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>

<script type="text/javascript" src="{{ asset('user/js/validation.js') }}"></script>
<script src="{{ asset('user/js/script.js')}}"></script>
<script src="{{ asset('js/wall.js') }}"></script>
@yield('js-scripts')
<script>
    $( function() {
		$( "#freightvalidity" ).datepicker();
	});
</script>

<!-- Modal For Background image upload start here-->
<div class="modal fade" id="background_image_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
	<form action="{{ route('backgroundupdate') }}" method="post" enctype="multipart/form-data">
	@csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Bckground Image Upload</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
			<div class="form-group">
			    <input type="hidden" name="oldimage" value="Auth::user()->background_image == """>
                <input type="file" class="form-control-file" name="background_image" id="background_image" aria-describedby="fileHelp" required>
                <small id="fileHelp" class="form-text text-muted">Please upload a valid image file. Size of image should not be more than 1MB.</small>
            </div>		
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </div>
    </form>
  </div>
</div>
<!-- Modal For Background image upload End here-->
<!-- Modal For profile image upload start here-->
<div class="modal fade" id="profile_image_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
	<form action="{{ route('profilepictureupdate') }}" method="post" enctype="multipart/form-data">
	@csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Profile Picture</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
			<div class="form-group">
                <input type="file" class="form-control-file" name="profilepic" id="profilepic" aria-describedby="fileHelp" required>
                <small id="fileHelp" class="form-text text-muted">Please upload a valid image file. Size of image should not be more than 1MB.</small>
            </div>		
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </div>
    </form>
  </div>
</div>
<!-- Modal For profile image upload End here-->

<script>
	$(document).ready(function(){
	    
	    $('#datatable').dataTable();

		var role_id = {{ Auth::user()->role_id }} ;
		if(role_id == '2'){
			$("#registermodal").modal('show');
		}
		$('#Cservice2').hide();
		// for select box
		
	});
</script>
    
</body>
</html>