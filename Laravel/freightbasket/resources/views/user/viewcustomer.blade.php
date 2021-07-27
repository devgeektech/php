@extends('layouts.usertemplate')
@section('content')
<!-- Top Bar End -->
<!-- Page-Title -->
	<div class="page-title-box">
<div class="row">
	<div class="col-sm-12">
	
		
			<h4 class="page-title">View Customer</h4>
		
		
	
	
	@if (Session::has('success'))
		<p class="alert alert-success">{!! Session::get('success') !!}<span class="close" style="cursor:pointer;" data-dismiss="alert">&times;</span></p>
		@endif
		@if (Session::has('error'))
		<p class="alert alert-danger">{!! Session::get('error') !!}<span class="close" style="cursor:pointer;" data-dismiss="alert">&times;</span></p>
		@endif
		</div>
</div>
</div>

@if(Auth::User()->role_id == '4')
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body row">
				<table class="table table-bordered table-stripped col-md-8 offset-md-2">
				  
				        <tr>
				            <th>Company Name</th>
				            <td>{{ $customer->fullname}}</td>
				        </tr>
				        <tr>
				            <th>Phone</th>
				            <td>{{ $customer->phone}}</td>
				        </tr>
				        <tr>
				            <th>Company Address</th>
				            <td>{{ $customer->company_address}}</td>
				        </tr>
				        <tr>
				            <th>City</th>
				            <td>{{ $customer->city}}</td>
				        </tr>
				        <tr>
				            <th>Fax</th>
				            <td>{{ $customer->fax}}</td>
				        </tr>
				        <tr>
				            <th>Vat</th>
				            <td>{{ $customer->vat}}</td>
				        </tr>
				        <tr>
				            <th>Tax No</th>
				            <td>{{ $customer->tax_no}}</td>
				        </tr>
				        <tr>
				            <th>Person Incharge</th>
				            <td>{{ $customer->person_incharge}}</td>
				        </tr>
				        <tr>
				            <th>Group Name</th>
				            <td>{{ $customer->group_name}}</td>
				        </tr>
				        <tr>
				            <th>Mesis No</th>
				            <td>{{ $customer->mesis_no}}</td>
				        </tr>
				        <tr>
				        <th>
				            User Email
				        </th>
				        <td>
				            	<table class="table table-bordered table-stripped">
				            	    <thead>
				            	        <tr>
				            	            <th>Name</th>
				            	            <th>Email</th>
				            	            <th>Occupation</th>
				            	        </tr>
				            	    </thead>
				            	    <tbody>
				            	        <?php 
				            	        $data1 = $customer->multi_user;
        				               $data = Unserialize($data1); 
        				               $count = count($data);
        				              for($i = 0; $i< $count;$i++){ ?>
				                  <tr>
				                      <td>{{ $data[$i]['name'] }}</td>
				                      <td>{{ $data[$i]['email'] }}</td>
				                      <td>{{ $data[$i]['occupation'] }}</td>
				                      
				                  </tr>
				                       <?php }
				                  ?>
				            	    </tbody>
				            	    </table>
				            
				            </td>
				        </tr>
				</table>
		
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