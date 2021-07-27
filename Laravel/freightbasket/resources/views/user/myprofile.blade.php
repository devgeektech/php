@extends('layouts.usertemplate')
@section('content')
<!-- Top Bar End -->
<!-- Page-Title -->

<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
			</div>
			<h4 class="page-title">My Profile</h4>
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
	<!--<div class="col-lg-5 myprofile_backround">
			<div class="parallax__image-container">	
				
				@if(Auth::user()->background_image == "" )
			    <img src="{{ asset('/images/default-user-bg.jpg') }}" alt="" class="img-fluid mx-auto d-block parallax__image">
			    @else
			    <img src="{{ asset('uploads/background-image') }}/{{Auth::user()->background_image}}" alt="" class="img-fluid mx-auto d-block parallax__image">
			    @endif
			</div>
		
			<div class="parallax__content">
			    <div class="row">
			        <div class="col-md-4"></div>
			        <div class="col-md-6">
			        </div>
			        <div class="col-md-2">
			            <div class="p-image">
			            	<i class="fa fa-camera upload-button" data-toggle="modal" data-target="#background_image_popup"></i>
					     </div>
			        </div>
			    </div>
			</div>
			<div class="cover-content">
			                <h2 class="white--text ">{{ Auth::user()->name }}</h2>
			                <div>at <span class="regular-link">{{ Auth::user()->name }} COMPANY</span></div>
			</div> 
	</div>-->
	<div class="col-lg-4 custom-sidebar-myprofile">
		
		<div class="card">
			<div class="card-body">
		    	
		    	<div class="profile-pic">
				    @if(Auth::user()->avatar == "users/default.png" )
				    <img src="{{ asset('uploads/profiles/default-user-photo.jpg') }}" alt="" class="img-fluid mx-auto d-block">
				    @else
				    <img src="{{ asset('uploads/profiles/'.Auth::user()->avatar ) }}" alt="" class="img-fluid mx-auto d-block">
				    @endif
				    <div class="p-image">
				       <i class="fa fa-camera upload-button" data-toggle="modal" data-target="#profile_image_popup"></i>
			     	</div>
		    	</div>
				
				<h5>About</h5>
				<h4 class="mt-2 header-title"><span class="mr-2"><i class="fa fa-user"></i></span>{{ Auth::user()->name }}</h4>
				<p class="mb-2"><i class="fa fa-envelope"></i><span class="text-mute ml-2">{{ Auth::user()->email }}</span></p>
				<p><i class="fa fa-phone"></i><span class="text-mute ml-2">123456789</span></p>
			</div>
		</div>
	</div>
	<div class="col-md-8">

		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-body">
						<nav class="nav nav-pills nav-justified">
							<a class="nav-item nav-link bg-secondary text-white active" href="#home" data-toggle="tab">Personal Detail</a>
						
							<a class="nav-item nav-link bg-secondary text-white" href="#profile"  data-toggle="tab">Change Password</a>
						</nav>
						<div class="tab-content" id="myTabContent">
							<!-- first tab -->
							<div class="tab-pane fade show active" id="home">
								<h1 class="mt-3">Personal Detail</h1>
								<form action="{{ route('profileupdate') }}" method="post" enctype="multipart/form-data">
									@csrf
									<input type="hidden" name="id" value="{{ Auth::user()->id }}">
									<input type="hidden" name="oldavatar" value="{{ Auth::user()->avatar }}">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="">Name</label>
												<input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ Auth::user()->name }}"  autocomplete="name" autofocus>
												@error('name')
												<span class="invalid-feedback" role="alert">
													<strong>{{ $message }}</strong>
												</span>
												@enderror
											</div>
										</div>
										<div class="col-md-6">
											<label for="">Phone</label>
											<input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ Auth::user()->phone }}" autocomplete="phone">
											@error('email')
											<span class="invalid-feedback" role="alert">
												<strong>{{ $message }}</strong>
											</span>
											@enderror
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="">Email</label>
												<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ Auth::user()->email }}" required autocomplete="email">
												@error('email')
												<span class="invalid-feedback" role="alert">
													<strong>{{ $message }}</strong>
												</span>
												@enderror
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="">Profile Picture</label>
												<input type="file" name="avatar" id="avatar" class="form-control">
												@error('avatar')
												<span class="invalid-feedback" role="alert">
													<strong>{{ $message }}</strong>
												</span>
												@enderror
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												<label for="">Address</label>
												<textarea class="form-control" name="address" autocomplete="">{{ Auth::user()->address }}</textarea>
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												<label for="">About</label>
												<textarea class="form-control" name="About" autocomplete="" placeholder="">{{ Auth::user()->about }}</textarea>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<button type="submit" class="btn btn-primary">
											{{ __('Update') }}
											</button>
										</div>
									</div>
								</form>
							</div>
							<!-- end of first tab -->
							
							<!-- third tab -->
							<div class="tab-pane fade" id="profile">
								<div class="row">
									<div class="col-md-12">
										<h1 class="mt-3">Change Password</h1>
										<form action="{{ route('updatepassword') }}" method="post">
											@csrf
											<input type="hidden" name="id" value="{{ Auth::user()->id }}">							<div class="form-group">
												<label for="">New Password</label>
												<input type="password" name="newpassword" placeholder="Enter Your New Password" class="form-control" required>
											</div>
											<div class="form-group">
												<label for="">Confirm New Password</label>
												<input type="password" name="confirm_new_password" placeholder="Confirm New Password" class="form-control" required>
											</div>
											<div class="form-group">
												<button type="submit" class="btn btn-primary">
												{{ __('Update Password') }}
												</button>
											</div>
										</form>
									</div>
								</div>
								
							</div>
							<!-- end of third tab -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection



