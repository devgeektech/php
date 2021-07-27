@extends('layouts.usertemplate')
@section('content')
<!-- Top Bar End -->
<!-- Page-Title -->
<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
			</div>
			<h4 class="page-title">Received Offer</h4>
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
			    @if(count($offer))
			    <table class="table table-stripped table-bordered text-uppercase" id="datatable">
				    <thead>
				        <tr>
				            <th>No.</th>
				            <th>Freight Details</th>
				            <th>validity </th>
				            <th>Status</th>
				            <th>Action</th>
				        </tr>
				        
				    </thead>
				    <tbody>
				        @foreach($offer as $offerr)
				        @php
				        $status = json_decode($offerr->status);
				        $freight = $offerr->freight;
                        @endphp
				        <tr>
				        	<td>{{$loop->iteration}}</td>
				            <td>{{ $freight->service_category. ' - ' .$freight->service_type. ' - ' .$freight->departure_country. ' to ' .$freight->arriaval_country }}</td>
			                <td>{{$freight->freightvalidity}}</td>
				            <td>
				                @foreach($status as $status1)
				                	@if($status1->email == Auth::User()->email)
				                		
				                		@if($status1->status == 0)
					                		{!! Form::open(['method' => 'POST', 'route' => "offer.invite"]) !!}
					                			{{ Form::hidden('offer_id', $offerr->id) }}
					                			{{ Form::hidden('status', "accept") }}
												<button type="submit" class="btn btn-default" aria-label="Left Align">Accept</button>
											{!! Form::close() !!}
				                			
				                			<button type="button" class="btn btn-default" data-val="{{$offerr->id}}" data-toggle="modal" data-target="#offerRejectmyModal">Reject</button>

			                			@elseif($status1->status == 1)
			                				Accepted
		                				@else
		                					Rejected
				                		@endif
				                	@endif
				                @endforeach
			                </td>
				            <td>
				            	<a href="{{ route('offer.view', $offerr->id) }}" class="action-icons" ><i class="fa fa-eye"></i></a>
				            </td>
				        </tr>
				        @endforeach
				    </tbody>
				</table>
				@else
				    No Result Found. You need to add Offer.
				@endif
				</div>
        </div>
	</div>
</div>
@endsection


<!-- The Modal -->
<div class="modal offer_reject" id="offerRejectmyModal">
	<div class="modal-dialog">
	  <div class="modal-content">
	  
	    <!-- Modal Header -->
	    <div class="modal-header">
	      <h4 class="modal-title">Reject Reason</h4>
	      <button type="button" class="close" data-dismiss="modal">&times;</button>
	    </div>
	    
	    <!-- Modal body -->
	    <div class="modal-body">
	      	{!! Form::open(['method' => 'POST', 'route' => "offer.invite"]) !!}
				{{ Form::hidden('status', "reject") }}
				{{ Form::text('message', null, ['class' => 'form-control']) }}
				<button type="submit" class="btn btn-primary" aria-label="Left Align">Reject</button>
			{!! Form::close() !!}
	    </div>
	    
	    <!-- Modal footer -->
	    <div class="modal-footer">
	      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
	    </div>
	    
	  </div>
	</div>
</div>
  
