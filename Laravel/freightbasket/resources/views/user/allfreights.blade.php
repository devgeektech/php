@extends('layouts.usertemplate')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-right">
                </div>
                <h4 class="page-title">Search Freights</h4>
            </div>
            @if (Session::has('success'))
            <p class="alert alert-success">{!! Session::get('success') !!}<span class="close" style="cursor:pointer;" data-dismiss="alert">&times;</span></p>
            @endif
            @if (Session::has('error'))
            <p class="alert alert-danger">{!! Session::get('error') !!}<span class="close" style="cursor:pointer;" data-dismiss="alert">&times;</span></p>
            @endif
            @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>        
                @endforeach
            </div>
            @endif            
    		<div class="row">
    	        <div class="col-md-12">
    	            <div class="card">
    	                <div class="card-body">
                            @if(!empty($freights))
        	               	<table class="table table-bordered data-table" id="datatable">
    					        <thead>
	                                <tr>
	                                    <th>Service No.</th>
							            <th>Type</th>
							            <th>Departure City</th>
							            <th>Arriaval City</th>
							            <th>Validity Date</th>
							            <th>Action</th>
	                                </tr>
	                            </thead>
	                            <tbody>                                
	                                @foreach($freights as $freight)                               
	                                <tr>
										<td>{{ $freight->id }}</td>
										<td>{{ $freight->service_type }}</td>
										<td>{{ $freight->departure_city }}</td>
										<td>{{ $freight->arriaval_city }}</td>
										<td>{{ $freight->freightvalidity }} 
											@if($freight->freightvalidity >= date('m/d/Y'))
												<span class="text-success">Active</span>
											@else
												<span class="text-danger">Expired</span>
											@endif
										</td>
										<td>
											<a href="{{ route('singlefreight',$freight->id) }}" class="action-icons" ><i class="fa fa-eye  "></i></a>
										</td>
									</tr>                               
	                                @endforeach
	                            </tbody>
    				       	</table>
                            @else
                            	<h3>No Data Found..!!!</h3>
                            @endif                    
    	                </div>
    	            </div>
    	        </div>
    	    </div>
        </div>
    </div>
@endsection
