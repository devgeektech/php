@extends('layouts.usertemplate')
@section('content')
<!-- Top Bar End -->
<!-- Page-Title -->
<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
			</div>
			<h4 class="page-title">Offer <a href="{{ route('offer.add') }}" class="btn btn-success float-right">Add offer</a></h4>
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
			    <table class="table table-stripped table-bordered text-uppercase" id="datatable">
				    <thead>
				        <tr>
				            <th>No.</th>
				            <th>Co. Name</th>
				            <th>Freight Details</th>
				            <th>validity </th>
				            <th>Customers</th>
				            <th>Action</th>
				        </tr>
				        
				    </thead>
				    <tbody>
				        @foreach($offers as $offer)
				        @php
				        $customer = json_decode($offer->customer);
				        $status = json_decode($offer->status);
				        $freight = $offer->freight;
				        $companyname = $offer->companydetails;
                        @endphp
				        <tr>
				            <td>{{$loop->iteration}}</td>
				            <td>{{ $companyname->companyname }}</td>
				            <td>{{ $freight->service_category. ' - ' .$freight->service_type. ' - ' .$freight->departure_country. ' to ' .$freight->arriaval_country }}</td>
			                <td>{{$freight->freightvalidity}}</td>
				            <td>
				                @foreach($status as $status1)
				                	<div class="col-md-12">
										<span>{{$status1->email}}</span>
				                		@if($status1->status == 0)
					                		<span class="text-primary">(Pending)</span>
			                			@elseif($status1->status == 1)
			                				<span class="text-success">(Accepted)</span>
		                				@else
		                					<span class="text-danger" data-toggle="popover" title="Rejected Reason" data-content="{{$status1->message}}">(Rejected)</span>
				                		@endif
			                		</div>
				                @endforeach
			                </td>
				            <td>
				            	<a href="{{ route('offer.view', $offer->id) }}" class="action-icons" ><i class="fa fa-eye"></i></a>
				            </td>
				        </tr>
				        @endforeach
				    </tbody>
				</table>
			</div>
        </div>
	</div>
</div>
@endsection