@extends('layouts.usertemplate')
@section('content')
<!-- Top Bar End -->
<!-- Page-Title -->
	<div class="page-title-box">
<div class="row">
	<div class="col-sm-8">
	
		
			<h4 class="page-title">Manage Customer</h4>
		
		
	</div>
	<div class="col-sm-4">
	    	<div class="float-right">
			    <a href="{{ route('newcustomer') }}" class="btn btn-info">Add Customer </a>
			</div>
	</div>
	<div class="col-md-12">
	@if (Session::has('success'))
		<p class="alert alert-success">{!! Session::get('success') !!}<span class="close" style="cursor:pointer;" data-dismiss="alert">&times;</span></p>
		@endif
		@if (Session::has('error'))
		<p class="alert alert-danger">{!! Session::get('error') !!}<span class="close" style="cursor:pointer;" data-dismiss="alert">&times;</span></p>
		@endif
		</div>
</div>
</div>

<div class="row">
	
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<table class="table table-stripped table-bordered">
				    <thead>
				        <tr>
				            <th>S No.</th>
				            <th>Company Name</th>
				            <th>Phone Number</th>
				            
				            <th>Company Address</th>
				            <th>City</th>
				            <th>Group Name</th>
				            <th>Person Incharge</th>
				            <th>Action</th>  
				        </tr>
				    </thead>
				    <tbody>
				        
				        @php 
				            $i = $cusomerslist->perPage() * ($cusomerslist->currentPage() - 1); 
				            $i++;
				        @endphp
				        @foreach($cusomerslist as $cusomerslis)
				        <tr>
				            <td>{{ $i }}</td>
				            <td>{{ $cusomerslis->fullname }}</td>
				            <td>{{ $cusomerslis->phone }}</td>
				          
				            <td>{{ $cusomerslis->company_address }}</td>
				            <td>{{ $cusomerslis->city }}</td>
				            <td>{{ $cusomerslis->group_name }}</td>
				            <td>{{ $cusomerslis->person_incharge }}</td>
				            <td>
				                <a href="{{ route('customer/view',$cusomerslis->id)  }}" class="action-icons" ><i class="fa fa-eye  "></i></a>
				             <a href="{{ route('customer/edit',$cusomerslis->id)  }}" class="action-icons"><i class="fa fa-edit  "></i></a>
				             <a href="{{ route('customer/delete',$cusomerslis->id)  }}" class="action-icons" ><i class="fa fa-trash-o  "></i></a>
				             <a href="{{ $cusomerslis->id  }}" class="action-icons"><i class="fa fa-envelope" aria-hidden="true"></i></a>
				             </td>
				        </tr>
				        @php $i++ @endphp
				        @endforeach
				      
				       
				    </tbody>
				</table>
			{{ $cusomerslist->links() }}
			</div>
		</div>
	</div>
</div>
@endsection