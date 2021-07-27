@extends('layouts.usertemplate')
@section('content')
<!-- Top Bar End -->
<!-- Page-Title -->
<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
			</div>
			<h4 class="page-title">Timeline</h4>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-12 mb-4">
		@if($errors->any())
		    {!! implode('', $errors->all('<div class="text-danger">:message</div>')) !!}
		@endif
		<form action="{{ route('timeline.save') }}" method="post"  enctype="multipart/form-data" class="border border-primary p-2">
     	@csrf
    		<input type="hidden" name="user_id" value="{{Auth::user()->id}}">

     		<fieldset id="">
        		<div class="row">
        			<div class="col-md-12">
    					<div class="row">
        					<div class="col-md-12">
        						<div class="form-group">
        							<label for=""> Start a post* </label>
        							<input type="textbox" col="4" name="message" class="form-control" required>
        						</div>
        					</div>
        					<div class="col-xs-12 col-sm-12 col-md-12">
					            <div class="form-group">
					                <strong>Images</strong>
					                <input type="file" name="images[]" id="image" class="form-control" multiple>
					            </div>
					        </div>
        					<div class="col-xs-12 col-sm-12 col-md-12">
					            <div class="form-group">
		                			<button type="submit" id="submit_customer_data" class="btn btn-secondary float-right ml-3">Submit</button>
					            </div>
					        </div>
    					</div>
        			</div>
        		</div>
			</fieldset>
     	</form>
	</div>
	<div class="col-lg-3">
		<div class="card">
			<div class="card-body">
				@if(Auth::user()->avatar == "users/default.png" )
			    <img src="{{ asset('uploads/profiles/default-user-photo.jpg') }}" alt="" class="img-fluid mx-auto d-block">
			    @else
			    <img src="{{ asset( 'uploads/profiles/'.Auth::user()->avatar ) }}" alt="" class="img-fluid mx-auto d-block">
			    @endif
				
				<h5>About</h5>
				<h4 class="mt-2 header-title"><span class="mr-2"><i class="fa fa-user"></i></span>{{ Auth::user()->name }}</h4>
				<p class="mb-2"><i class="fa fa-envelope"></i><span class="text-mute ml-2">{{ Auth::user()->email }}</span></p>
				<p><i class="fa fa-phone"></i><span class="text-mute ml-2">{{ Auth::User()->phone }}</span></p>
			</div>
		</div>
		
	</div>
	<div class="col-md-9">
		<div class="card">
			<div class="card-body">
				@if(count($timeline) > 0)
            @php($i = 0)
            @php($post_max_id = 0)
            @php($post_min_id = 0)
            @foreach($timeline as $post)
                @if($i == 0)
                    @php($post_max_id = $post->id)
                @endif
                @php($post_min_id = $post->id)

                @include('widgets.post_detail.single_post')

                @php($i++)
            @endforeach
          @else 
            Not Found any timeline yet..!!!
          @endif
			</div>
		</div>
	</div>
</div>

<!-- testing modal -->
<!-- Button trigger modal -->
<!-- Modal -->
<div class="modal fade" id="registermodal" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content ">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Complete Your Registration Process
				</h4>
				<!--  <span class="close" data-dismiss="modal" style="cursor:pointer;">&times;</span> -->
			</div>
			<div class="modal-body py-5">

		<form method="post" action="{{ route('addcompany') }}" id="myform" enctype="multipart/form-data">
							@csrf
			<fieldset id="account_information" class="">
					<input type="hidden" name="id" value="{{ Auth::User()->id }}">
							<div class="form-group" id="Cservice">
								<label for="">Who are you?</label>
								<select name="companytype" id="Ctype" class="form-control">
								    <option value="">Select Who Are You</option>
									@foreach($roles as $role)
									<option value="{{ $role->id }}">{{ $role->name }}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group"  id="Cservice2">
								<label for="">Choose Service Type</label>
								<br>								
								<ul class="list-group">
									<li class="list-group-item">
										<label class="checkbox-container">Freight Forwards</label>
										<ul class="list-group">
											<li class="list-group-item"><label class="checkbox-container">Air<input type="checkbox" name="companyservice[fre-fwrs][]" value="air"><span class="checkmark"></span></label></li>
											<li class="list-group-item"><label class="checkbox-container">Land<input type="checkbox" name="companyservice[fre-fwrs][]" value="land"><span class="checkmark"></span></label></li>
											<li class="list-group-item"><label class="checkbox-container">Sea<input type="checkbox" name="companyservice[fre-fwrs][]" value="sea"><span class="checkmark"></span></label></li>
										</ul>
									</li>
									<li class="list-group-item">
										<label class="checkbox-container">Customs Brokers</label>
										<ul class="list-group">
											<li class="list-group-item"><label class="checkbox-container">Customs Brokerage<input type="checkbox" name="companyservice[custom-brokers][]" value="custom-brokerage"><span class="checkmark"></span></label></li>
											<li class="list-group-item"><label class="checkbox-container">Export and Import Decleration<input type="checkbox" name="companyservice[custom-brokers][]" value="export-import-decl"><span class="checkmark"></span></label></li>
											<li class="list-group-item"><label class="checkbox-container">In-Transit and Free Zone Declerations<input type="checkbox" name="companyservice[custom-brokers][]" value="in-transit-free-zone-decl"><span class="checkmark"></span></label></li>
											<li class="list-group-item"><label class="checkbox-container">Container and Truck Tracing<input type="checkbox" name="companyservice[custom-brokers][]" value="container-truck-tracing"><span class="checkmark"></span></label></li>
											<li class="list-group-item"><label class="checkbox-container">T1 – EURO1 Documents<input type="checkbox" name="companyservice[custom-brokers][]" value="t1-euro01-docs"><span class="checkmark"></span></label></li>
											<li class="list-group-item"><label class="checkbox-container">Return Cargo<input type="checkbox" name="companyservice[]" value="return-cargo"><span class="checkmark"></span></label></li>
											<li class="list-group-item"><label class="checkbox-container">Re-Export<input type="checkbox" name="companyservice[custom-brokers][]" value="re-export"><span class="checkmark"></span></label></li>
											<li class="list-group-item"><label class="checkbox-container">WareHouse Customs Process<input type="checkbox" name="companyservice[custom-brokers][]" value="warehouse-customs-process"><span class="checkmark"></span></label></li>
											<li class="list-group-item"><label class="checkbox-container">Temporary Import Process<input type="checkbox" name="companyservice[custom-brokers][]" value="temp-import-proc"><span class="checkmark"></span></label></li>
											<li class="list-group-item"><label class="checkbox-container">Temporary Export Process<input type="checkbox" name="companyservice[custom-brokers][]" value="temp-export-proc"><span class="checkmark"></span></label></li>
											<li class="list-group-item"><label class="checkbox-container">Agriculture and Healt Certificates<input type="checkbox" name="companyservice[custom-brokers][]" value="agr-health-cert"><span class="checkmark"></span></label></li>
											<li class="list-group-item"><label class="checkbox-container">T.S.E. Applications and Process<input type="checkbox" name="companyservice[custom-brokers][]" value="tse-app-proc"><span class="checkmark"></span></label></li>
											<li class="list-group-item"><label class="checkbox-container">TAREX<input type="checkbox" name="companyservice[custom-brokers][]" value="tarex"><span class="checkmark"></span></label></li>
											<li class="list-group-item"><label class="checkbox-container">Summary Customs Declerations<input type="checkbox" name="companyservice[custom-brokers][]" value="summary-customs-decl"><span class="checkmark"></span></label></li>
											<li class="list-group-item"><label class="checkbox-container">Customs Policies, Applications and Consultancy<input type="checkbox" name="companyservice[custom-brokers][]" value="customs-policies-app-consult"><span class="checkmark"></span></label></li>
										</ul>
									</li>
									<li class="list-group-item"><label class="checkbox-container">International Land Fre companies<input type="checkbox" name="companyservice[others][]" value="International-land-fre-co"><span class="checkmark"></span></label></li>
									<li class="list-group-item"><label class="checkbox-container">Lashing and securing companies<input type="checkbox" name="companyservice[others][]" value="lashing-securing"><span class="checkmark"></span></label></li>
									<li class="list-group-item"><label class="checkbox-container">Fumigation companies<input type="checkbox" name="companyservice[others][]" value="fumigation"><span class="checkmark"></span></label></li>
									<li class="list-group-item"><label class="checkbox-container">Insurance companies<input type="checkbox" name="companyservice[others][]" value="insurance"><span class="checkmark"></span></label></li>
								</ul>
								
								<!--<label class="checkbox-container"><input type="checkbox" name="service[]" value=""><span class="checkmark"></span>-->
								<!--</label>-->
								<!--<label class="checkbox-container"><input type="checkbox" name="service[]" value=""><span class="checkmark"></span>-->
								<!--</label>-->
        <!--                        <label class="checkbox-container"><input type="checkbox" name="service[]" value=""><span class="checkmark"></span>-->
								<!--</label>-->
                                								
								<!--<select name="companyservice[]" multiple  class="form-control">-->
								<!--	<option value="Air">Air</option>-->
								<!--	<option value="Land">Land</option>-->
								<!--	<option value="Sea">Sea</option>-->
								<!--</select>-->
							</div>
					<p><a class="btn btn-primary text-white next">Next</a></p>
				</fieldset>
				<fieldset id="company_information" style="display:none">
					<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="">Company Name</label>
									<input type="text" class="form-control" name="companyname"  >
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="">Country Name</label>
									<input type="text" class="form-control" name="countryname" >
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="">City</label>
									<input type="text" class="form-control" name="companycity"  >
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="">Tax</label>
									<input type="text" class="form-control" name="companytax" >
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="">Comany Email</label>
									<input type="email" class="form-control" name="companyemail" >
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="">Company Phone</label>
									<input type="text" class="form-control" name="companyphone" >
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-12">
								<label for="">Company Address</label>
								<textarea class="form-control" name="companyaddress" placeholder="Enter Your Company Address" ></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="">Company Documents</label>
							<input type="file" name="companydocuments[]" multiple="" class="form-control" >
						</div>
						<div class="float-right">
							<button type="submit" class="btn btn-success" >Submit</button>
						</div>
						<p><a class="btn btn-primary text-white" id="previous" >Previous</a></p>
				</fieldset>
				<fieldset id="personal_information" class="d-none">
					
				</fieldset>
		</form>
			</div>
		</div>
	</div>
</div>
<!-- end of testing modal -->
<!--<script src="{{ asset('user/js/jquery.min.js')}}"></script>-->



@endsection