@extends('layouts.usertemplate')
@section('content')
<!-- Top Bar End -->
<!-- Page-Title -->
<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
			</div>
			<h4 class="page-title">View Freights</h4>
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
			@if(!empty($freight))
			<div class="card-body">
			 
		        <div class="row">
		            <div class="col-md-12 p-5">
		                @if($freight->service_category == "air")
		                 <table class="table table-striped  table-bordered">
				        <tr>
				            <th>Service Number </th>
				             <td>{{ $freight->id }}</td>
				        </tr>
				        <tr>
				            <th>Servie Type</th>
				            <td>{{ $freight->service_category }}</td>
				        </tr>
				        <tr>
				            <th>Departure Country </th>
				             <td>{{ $freight->departure_country }}</td>
				        </tr>
				        <tr>
				            <th>Departure City </th>
				             <td>{{ $freight->departure_city }}</td>
				        </tr>
				        <tr>
				            <th>Departure Port </th>
				             <td>{{ $freight->departure_port }}</td>
				        </tr>
				        
				        <tr>
				            <th>ESTIMATED TRANSIT TIME </th>
				             <td>{{ $freight->estimate_time }} Days</td>
				        </tr>
				        <tr>
				            <th>Arriaval Country </th>
				             <td>{{ $freight->arriaval_country }}</td>
				        </tr>
				        <tr>
				            <th>Arriaval  City </th>
				             <td>{{ $freight->arriaval_city }}</td>
				        </tr>
				        <tr>
				            <th>Arriaval Port </th>
				             <td>{{ $freight->arriaval_port }}</td>
				        </tr>
				         <tr>
				            <th>Client Type </th>
				             <td><ul><?php 
				             	$row = explode(',',$freight->client_type);
				             	foreach($row as $data)
				             	{ ?>
				             		<li>{{ $data }}</li>
				             	<?php }
				             ?> </ul></td>
				        </tr>
				        <tr>
				            <th>Location Type </th>
				             <td>

				             	<ul><?php 
				             	$row = explode(',',$freight->location_type);
				             	foreach($row as $data){ ?>
				             		<li>{{ $data }}</li>
				             	<?php }
				             ?> </ul>

				             </td>
				        </tr>
				        <tr>
				            <th>Freight Validity </th>
				             <td>{{ $freight->freightvalidity }}</td>
				        </tr>
				        <tr>
				            <th>LINER AGENT / COLOADER</th>
				            <th>@if($freight->comment != ""){{ $freight->comment  }} @else No Data Found @endif</th>
				        </tr>
				        <tr>
				            <th>Price </th>
				             <td>
				                 <table class="table">
				                     <tr>
				                         <th>Cost Type</th>
				                         <th>Calculation</th>
				                         <th>Quantity</th>
				                         <th>Price</th>
				                     </tr>
				                     	<?php 
				                     	$price_list = $freight->airport_price;
			                            $data = Unserialize($price_list);
			                            $count = count($data);
			                            for($i=0; $i<$count;$i++){ ?>
			                            <tr>
			                                <td> {{ $data[$i]['cost_type'] }} </td>
			                                <td>{{ $data[$i]['calculation'] }}</td>
			                                <td>@if($data[$i]['quantity'] == "SET") {{ $data[$i]['quantity'] }} @else Above {{ $data[$i]['quantity'] }} Kg @endif </td>
			                                <td>{{ $data[$i]['price'] }} {{ $data[$i]['currency_type'] }}</td>
			                            </tr>
			                            <?php 
			                            }
			                            ?>
				                 </table>
				                 {{ $freight->price }}</td>
				        </tr>
				        
				    </table>	
		                @endif
		                @if($freight->service_category == "land")
		                 <table class="table table-striped  table-bordered">
				        <tr>
				            <th>Service Number </th>
				             <td>{{ $freight->id }}</td>
				        </tr>
				        <tr>
				            <th>Servie Type</th>
				            <td>{{ $freight->service_category }}</td>
				        </tr>
				        @if($freight->service_type != "")
				        <tr>
				            <th>Type</th>
				            <td>{{ $freight->service_type }}</td>
				        </tr>
				        @endif
				       
				        <tr>
				            <th>Departure Country </th>
				             <td>{{ $freight->departure_country }}</td>
				        </tr>
				        <tr>
				            <th>Departure City </th>
				             <td>{{ $freight->departure_city }}</td>
				        </tr>
				        <tr>
				            <th>Departure Port </th>
				             <td>{{ $freight->departure_port }}</td>
				        </tr>
				        
				        <tr>
				            <th>ESTIMATED TRANSIT TIME </th>
				             <td>{{ $freight->estimate_time }} Days</td>
				        </tr>
				        <tr>
				            <th>Arriaval Country </th>
				             <td>{{ $freight->arriaval_country }}</td>
				        </tr>
				        <tr>
				            <th>Arriaval  City </th>
				             <td>{{ $freight->arriaval_city }}</td>
				        </tr>
				        <tr>
				            <th>Arriaval Port </th>
				             <td>{{ $freight->arriaval_port }}</td>
				        </tr>
				         <tr>
				            <th>Client Type </th>
				             <td><ul><?php 
				             	$row = explode(',',$freight->client_type);
				             	foreach($row as $data)
				             	{ ?>
				             		<li>{{ $data }}</li>
				             	<?php }
				             ?> </ul></td>
				        </tr>
				        <tr>
				            <th>Location Type </th>
				             <td>

				             	<ul><?php 
				             	$row = explode(',',$freight->location_type);
				             	foreach($row as $data){ ?>
				             		<li>{{ $data }}</li>
				             	<?php }
				             ?> </ul>

				             </td>
				        </tr>
				        <tr>
				            <th>Freight Validity </th>
				             <td>{{ $freight->freightvalidity }}</td>
				        </tr>
				    
				        <tr>
				            <th>LINER AGENT / COLOADER</th>
				            <th>@if($freight->comment != ""){{ $freight->comment  }} @else No Data Found @endif</th>
				        </tr>
				
				        <tr>
				            <th>Price </th>
				            <td>
				                 <table class="table">
				                     <tr>
				                         <th>Cost Type</th>
				                         <th>Calculation</th>
				                         <th>Price</th>
				                     </tr>
				                     <?php 
				                     $price_list = $freight->airport_price;
                            $data = Unserialize($price_list);
                            $count = count($data);
                            for($i=0; $i<$count;$i++){ ?>
                            <tr>
                                <td> {{ $data[$i]['cost_type'] }} </td>
                                <td>{{ $data[$i]['calculation'] }}</td>
                                
                                <td>{{ $data[$i]['price'] }} {{ $data[$i]['currency_type'] }}</td>
                            </tr>
                            <?php 
                            }
                            ?>
				                 </table>
				                </td>
				        </tr>
				        
				    </table>	
		                @endif
		                @if($freight->service_category == "sea")
		                 <table class="table table-striped  table-bordered">
				        <tr>
				            <th>Service Number </th>
				             <td>{{ $freight->id }}</td>
				        </tr>
				        <tr>
				            <th>Servie Type</th>
				            <td>{{ $freight->service_category }}</td>
				        </tr>
				        @if($freight->service_type != "")
				        <tr>
				            <th>Type</th>
				            <td>{{ $freight->service_type }}</td>
				        </tr>
				        @endif
				      
				        <tr>
				            <th>Departure Country </th>
				             <td>{{ $freight->departure_country }}</td>
				        </tr>
				        <tr>
				            <th>Departure City </th>
				             <td>{{ $freight->departure_city }}</td>
				        </tr>
				        <tr>
				            <th>Departure Port </th>
				             <td>{{ $freight->departure_port }}</td>
				        </tr>
				        
				        <tr>
				            <th>ESTIMATED TRANSIT TIME</th>
				             <td>{{ $freight->estimate_time }} Days</td>
				        </tr>
				        <tr>
				            <th>Arriaval Country </th>
				             <td>{{ $freight->arriaval_country }}</td>
				        </tr>
				        <tr>
				            <th>Arriaval  City </th>
				             <td>{{ $freight->arriaval_city }}</td>
				        </tr>
				        <tr>
				            <th>Arriaval Port </th>
				             <td>{{ $freight->arriaval_port }}</td>
				        </tr>
				         <tr>
				            <th>Client Type </th>
				             <td><ul><?php 
				             	$row = explode(',',$freight->client_type);
				             	foreach($row as $data)
				             	{ ?>
				             		<li>{{ $data }}</li>
				             	<?php }
				             ?> </ul></td>
				        </tr>
				        <tr>
				            <th>Location Type </th>
				             <td>

				             	<ul><?php 
				             	$row = explode(',',$freight->location_type);
				             	foreach($row as $data){ ?>
				             		<li>{{ $data }}</li>
				             	<?php }
				             ?> </ul>

				             </td>
				        </tr>
				        <tr>
				            <th>LINER AGENT / COLOADER</th>
				            <th>@if($freight->comment != ""){{ $freight->comment  }} @else No Data Found @endif</th>
				        </tr>
				        <tr>
				            <th>Freight Validity </th>
				             <td>{{ $freight->freightvalidity }}</td>
				        </tr>
				         <tr>
				            <th>Price </th>
				            <td>
				                 <table class="table">
				                     <tr>
				                         <th>Cost Type</th>
				                         <th>Calculation</th>
				                         <th>Price</th>
				                     </tr>
				                     <?php 
				                     $price_list = $freight->airport_price;
                            $data = Unserialize($price_list);
                            $count = count($data);
                            for($i=0; $i<$count;$i++){ ?>
                            <tr>
                                <td> {{ $data[$i]['cost_type'] }} </td>
                                <td>{{ $data[$i]['calculation'] }}</td>
                                
                                <td>{{ $data[$i]['price'] }} {{ $data[$i]['currency_type'] }}</td>
                            </tr>
                            <?php 
                            }
                            ?>
				                 </table>
				                </td>
				        </tr>
				        
				    </table>	
		                @endif
				   
				    </div>
		        </div>
			   
			</div>
			@else
			No Data found
			@endif
		</div>
	</div>
</div>
@endsection