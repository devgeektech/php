@extends('layouts.usertemplate')
@section('content')
<!-- Top Bar End -->
<!-- Page-Title -->
<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
			</div>
			<h4 class="page-title">Company Profile</h4>
		</div>
			@if (Session::has('success'))
		<p class="alert alert-success">{!! Session::get('success') !!}<span class="close" style="cursor:pointer;" data-dismiss="alert">&times;</span></p>
		@endif
	</div>
</div>
<div class="row">
  <div class="col-lg-12 myprofile_backround">
      <div class="parallax__image-container"> 
        
          @if($user->background_image == "" )
          <img src="{{ asset('/images/default-user-bg.jpg') }}" alt="" class="img-fluid mx-auto d-block parallax__image">
          @else
          <img src="{{ asset('uploads/background-image') }}/{{$user->background_image}}" alt="" class="img-fluid mx-auto d-block parallax__image">
          @endif
      </div>
    
      <div class="parallax__content">
          <div class="row">
              <!--<div class="col-md-4"></div>-->
              <div class="col-md-10">
                  <div class="cover-content">
                      <h2 class="white--text ">{{ $user->name }}</h2>
                      <div>at <span class="regular-link">{{ $user->name }} COMPANY</span></div>
                  </div> 
              </div>
              <div class="col-md-2">
                @if(Auth::User()->id == $user->id)
                <div class="p-image">
                  <i class="fa fa-camera upload-button" data-toggle="modal" data-target="#background_image_popup"></i>
                </div>
                @endif
              </div>
          </div>
      </div>
  </div>
	<div class="col-lg-3">
		
		<div class="card">
			<div class="card-body">
			    <h4 class="mt-2 header-title">offices({{ $compantdetails->count() }})</h4>
			    @if($compantdetails)
    				@foreach($compantdetails as $row)
              <p class="mt-2 header-title"><i class="fa fa-map-marker" aria-hidden="true"></i> {{ $row->countryname }},{{ $row->companycity }}</p>
              <p><i class="fa fa-briefcase" aria-hidden="true"></i> {{ $row->companyaddress }}</p>
              <p class="mt-2 header-title"><i class="fa fa-phone"></i> {{ $row->companyphone }}</p>
              <p class="mt-2 header-title"><i class="fa fa-envelope"></i> {{ $row->companyemail }}</p>
              <hr>
    				@endforeach
				  @endif
				
		      @if(Auth::User()->id == $user->id)
		      <p><span class="text-mute ml-2"><a href="#addoffice" data-toggle="modal"><b><i class="fa fa-plus"></i> Add office</b></a></span></p>
          @endif
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<h5>Employee({{ $staff->count() }})</h5>
				@if($staff)
  				@foreach($staff as $single )

            @if(Auth::User()->id == $user->id)
				  	<a href="{{ route ( 'singlestaff',['id'=>$single->id] ) }}" title="{{ $single->name }}">
            @endif
  					    @if($single->avatar == 'users/default.png' || $single->avatar == "")
  					    <img src="{{ asset('uploads/profiles/default-user-photo.jpg') }}" class="w-25">
  					    @else
  					    <img src="{{ asset($single->avatar) }}" class="w-25">
  					    @endif
				    @if(Auth::User()->id == $user->id)
            </a>
            @endif
  				@endforeach
				@endif

        @if(Auth::User()->id == $user->id)
				<p class="mt-2"><span class="text-mute ml-2"><a href="#addstaff" data-toggle="modal"><b> <i class="fa fa-plus"></i>Add Staff</b></a></span></p>
        @endif
			</div>
		</div>
		
	</div>
	<div class="col-md-9">
		<div class="card">
			<div class="card-body">
				<nav>
                      <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#AboutCompany" role="tab" aria-controls="nav-home" aria-selected="true">About Company</a>
                        <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#Services" role="tab" aria-controls="nav-profile" aria-selected="false">Services</a>
                        <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#Photos" role="tab" aria-controls="nav-contact" aria-selected="false">Photos</a>
                        <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#CompanyDocuments" role="tab" aria-controls="nav-contact" aria-selected="false">Company Documents</a>
                      </div>
                    </nav>
                    <div class="tab-content text-capitialize py-5" id="nav-tabContent">
                      <div class="tab-pane fade show active" id="AboutCompany" role="tabpanel" aria-labelledby="nav-home-tab">
                          @foreach($compantdetails as $row)
                          @if($row->officetype == "main")
                          <h4>{{ $row->aboutcompany }}</h4>
                          @endif
                          @endforeach
                          
                          @if(Auth::User()->id == $user->id)
                            <button class="btn edit-btn" data-target="#aboutcompany" data-toggle="modal"><i class="fa fa-pencil"></i></button>
                          @endif

                          <h3>Offices</h3>
                          <div class="row">
                             
                            @if($compantdetails)
                      				@foreach($compantdetails as $row)
                      				<div class="col-md-4">
                          			    <p class="mt-2 header-title"><i class="fa fa-map-marker" aria-hidden="true"></i> {{ $row->countryname }},{{ $row->companycity }}</p>
                          				<p><i class="fa fa-briefcase" aria-hidden="true"></i> {{ $row->companyaddress }}</p>
                          				<p class="mt-2 header-title"><i class="fa fa-envelope"></i> {{ $row->companyemail }}</p>
                          				<p class="mt-2 header-title"><i class="fa fa-phone"></i> {{ $row->companyphone }}</p>
                          			</div>
                      				
                  				@endforeach
                  				@endif
                          </div>
                         </div>
                      <div class="tab-pane fade py-3" id="Services" role="tabpanel" aria-labelledby="nav-profile-tab">
                          @foreach($compantdetails as $row)
                            @if(!empty($row->companyservice) || $row->companyservice != null)
                              @php 
                            	$servicetypeasda['asd'] = unserialize($row->companyservice)
                              @endphp
                              
                              <!-- Lashing, insurance and storage listing Start here-->
                              @foreach($servicetypeasda as $service1)
                                @foreach($service1 as $single_ser_key1 => $single_service1)  
                                  @if($single_ser_key1 == "others")                 
                                    <h3>Other Services</h3>
                                    @foreach($single_service1 as $single_service2)                   
                                      @if($single_service2 == "lashing-securing" || $single_service2 == "fumigation" || $single_service2 == "insurance")
                                          
                                          @if(Auth::User()->id == $user->id)
                                          <h4><a href="{{ route('otherservice.create', $single_service2) }}"><i class="fa fa-list" aria-hidden="true"></i> Add {{$single_service2}} Info</a></h4>
                                          @endif
                                          <div class="row col-md-12">
                                            @php
                                            $user_other_services = $user->other_services()
                                                                                ->where('service_type', $single_service2)
                                                                                ->where('status', 1)
                                                                                ->paginate(10);
                                            @endphp
                                            @if(count($user_other_services) > 0)
                                              <table class="table table-stripped table-bordered">
                                                  <thead>
                                                      <tr>
                                                        <th>Service No.</th>
                                                        <th>Name</th>
                                                        <th>Description</th>
                                                        <th>Action</th>
                                                      </tr>
                                                  </thead>
                                                  <tbody>
                                                  @foreach($user_other_services as $other_service)
                                                    @include('otherservice.index')
                                                  @endforeach
                                                  </tbody>
                                              </table>
                                              <div class="custom_pagination row col-md-12">{{$user_other_services->links()}}</div>
                                            @else
                                              <h4 colspan="6">No data Found </h4>
                                            @endif
                                          </div>
                                      @endif                   
                                    @endforeach
                                  @endif                   
                                @endforeach
                              @endforeach
                              <!-- Lashing, insurance and storage listing End here-->

								              @foreach($servicetypeasda as $service)
                                @foreach($service as $single_ser_key => $service)
                              
                									@if($service == "")
                									@else
				                              <!-- freights listing Start here-->
                                      	@if($single_ser_key == "fre-fwrs")
                                				<h3>Your Freights </h3>
                              				  
                                        @if(Auth::User()->id == $user->id)
                                        <h4><a href="{{ route('freight') }}"><i class="fa fa-list" aria-hidden="true"></i> Add Freight</a></h4>
                                        @endif

                                        	<div class="row col-md-12">
  	                                      	@if(!empty($user->freights))
  		                                        @php
	                                          	$no = 1;
	                                          	@endphp
  		                                        <div class="tab-pane fade container <?php if($no=="1"){ echo 'in active' ; } ?> py-3">
  		                                            <table class="table table-stripped table-bordered">
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
  		                                              	@php
  	                                              		$all_freight_user = $user->freights()->paginate(10)
  		                                              	@endphp
    	                                          			@foreach($all_freight_user as $freight)
				                                       		      @include('freights.index')
  	                                              		@endforeach
  		                                              	</tbody>
  		                                            </table>
  	                                          	</div>
  	                                          	<div class="custom_pagination row col-md-12">{{$all_freight_user->links()}}</div>
  	                                      	@else
  		                                        <h4 colspan="6">No data Found </h4>
  	                                      	@endif
                                          </div>
                                      	@endif
      				                        <!-- freights listing End here-->
      				                        
	                                    <p><b>
                											@if($single_ser_key == "fre-fwrs")
                											<h4>Freight Forwards</h4>

                											@endif

                											@if($single_ser_key == "custom-brokers")
                											<h4>Custom Brokers</h4>
                											@endif

                											@if($single_ser_key == "others")
                											<h4>Others</h4>
                											@endif
	                                      
	                                    </b></p>
	                                    @foreach($service as $single_service)                                      
	                                    	<p><i class="fa fa-list" aria-hidden="true"></i> {{ $single_service }}</p>
	                                    @endforeach
                                  	@endif
			                  	        @endforeach
                              	@endforeach
                              @endif
                            @endforeach
                          
                      </div>
                      <div class="tab-pane fade" id="Photos" role="tabpanel" aria-labelledby="nav-contact-tab">...</div>
                      <div class="tab-pane fade" id="CompanyDocuments" role="tabpanel" aria-labelledby="nav-contact-tab">
                          <div class="gallery-sec">
                          <div class="row">
                        @if($compnay_documents->companydocuments != "" ) 
                            
                              <?php
                                $path = 'public/'.$compnay_documents->companydocuments ;
                                 if($path !=""){
                                $imagepath = glob("$path/*");
                                
                                    foreach($imagepath as $image){ ?>
                                      <div class="col-md-4 py-2">
                                        
                                            <img src="{{ $image }}"  class="img-fluid mx-auto d-block">
                                            
                                            @if(Auth::User()->id == $user->id)
                                            <p class="text-center mt-2"><span class="action-icon"  onclick="openmodal('{{ $image }}')" style="cursor:pointer;"><i class="fa fa-trash fa-2x"></i></span></p>
                                            @endif
                                     </div>  
                                  <?php  }
                                }
                        
                              ?>
                              
                              @endif
                          </div>
                          </div>
                      </div>
     
                    </div>
			</div>
		</div>
	</div>
</div>

@if(Auth::User()->id == $user->id)
<!-- testing modal -->
<!-- Button trigger modal -->
<!-- Modal -->
<div class="modal fade" id="addstaff">
	<div class="modal-dialog">
		<div class="modal-content ">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Add Employee
				</h4>
				 <span class="close" data-dismiss="modal" style="cursor:pointer;">&times;</span> 
			</div>
			<div class="modal-body py-5">
        <div class="card">
          <div class="card-body">
            <form action="{{ route('addemployee') }}" method="post" id="addemployeeofc">
              @csrf
                <input type="hidden" name="role_id" value="6">
                <input type="hidden" name="refrence_id" value="{{ Auth::User()->id }}">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" name="name" class="form-control" required>
                  </div>
                </div>
                 <div class="col-md-12">
                  <div class="form-group">
                    <label for="">Email</label>
                    <input type="email" name="email" id="checkemail" class="form-control" required>
                    <p class="error mt-2"></p>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">Phone</label>
                    <input type="number" name="phone" class="form-control" required >
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">Proficiency</label>
                    <input type="text" name="proficiency" class="form-control" required >
                  </div>
                </div>
               
                <div class="col-md-12">
                  <div class="form-group float-right">
                   <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
                   <button type="submit" class="btn btn-secondary">Add Employee</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="addoffice">
	<div class="modal-dialog modal-lg">
		<div class="modal-content ">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Add Office
				</h4>
				 <span class="close" data-dismiss="modal" style="cursor:pointer;">&times;</span>
			</div>
			<div class="modal-body py-5">
                
        <form action="{{ route('addoffice') }}" method="post">
          @csrf
          
          <div class="row">
            <div class="form-group col-md-6">
            
              <input type="text" name="countryname" class="form-control" required placeholder="Country">
            </div>
             <div class="form-group col-md-6">
            
              <input type="text" name="companycity" class="form-control" required placeholder="City">
            </div>
          </div>
            <div class="row">
            <div class="form-group col-md-6">
            
              <input type="text" name="tax_adminstration" class="form-control" required placeholder="Tax Aadministration">
            </div>
             <div class="form-group col-md-6">
                  <input type="number" name="companytax" class="form-control" required placeholder="Tax No">
            </div>
          </div>
          <div class="form-group">
            
            <input type="text" name="companyaddress" class="form-control" required="" placeholder="Address">
          </div>
          <div class="form-group">
            
            <input type="number" name="companyphone" class="form-control" required="" placeholder="Phone">
          </div>
           <div class="form-group">
            
            <input type="email" name="companyemail" class="form-control" required="" placeholder="Email">
          </div>
          <div class="form-group float-right">
            <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Add Office</button>
          </div>
        </form>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="aboutcompany">
	<div class="modal-dialog">
		<div class="modal-content ">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">About Company
				</h4>
				 <span class="close" data-dismiss="modal" style="cursor:pointer;">&times;</span> 
			</div>
			<div class="modal-body py-5">
        <div class="card">
          <div class="card-body">
            <form action="{{ route('aboutcompany') }}" method="post" id="addemployeeofc">
              @csrf
              <div class="col-md-12">
                <div class="form-group">
                  <h4>Write Something About Your Company</h4>
                  <textarea name="aboutcompany" class="form-control" required Placeholder="About Your Company" rows="5"></textarea>    
                </div>
              </div>
             
              <div class="col-md-12">
                <div class="form-group float-right">
                 <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
                 <button type="submit" class="btn btn-secondary">Update</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
		</div>
	</div>
</div>
@endif

@endsection