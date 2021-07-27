@extends('layouts.usertemplate')
@section('content')
<!-- Top Bar End -->
<!-- Page-Title -->
<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
			</div>
			<h4 class="page-title">Edit Service</h4>
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
			   @if($freight->service_category == "air")
		        <div class="row">
					<form id="addfreight" action="{{ route('updatefeight') }}" method="post" enctype="mutipart/form-data" class="col-md-12">
				    @csrf
		            
		          	<div class="col-md-10 offset-md-1 py-5">      
					<!--Step 1-->
					<input type="hidden" name="user_id" value="{{ Auth::User()->id }}">
					<input type="hidden" name="id" value="{{ $freight->id }}">
					<input type="hidden" name="service_category" value="{{ $freight->service_category }}">
					<div id="freightstep1">
					   	<div class="row">
					       	<div class="col-md-12">
					           <div class="form-group">
					               <label>Sevice Category</label>
					               	@php
							        $data = unserialize($compantdetails->companyservice);
							        @endphp
					               	<select name="service_category" id="service_category" class="custom-select" disabled>
					                   	<option value="">Select Category</option>
					                   	@foreach($data["fre-fwrs"] as $row)
					                       	<option value="{{$row}}" @if($freight->service_category == $row) selected @endif>{{$row}}</option>
					                   	@endforeach
					               	</select>
					           </div>
					      	</div>
					 	</div>
					    <a class="btn btn-primary text-white float-right gonext">Next</a>
					</div>

			        <!--End of step 1-->
			        
			        <!--step 2-->
			        
			        
			        <div id="freightstep2" style="display:none;">
			            <!-- For Air -->
			            <div class="row condition1">
			               <div class="col-md-4">
			                   <h4><i class="fa fa-map-marker"></i> Departure</h4>
			                   <div class="form-group">
			                       <label>Country</label>
			                       <select name="departure_country1" id="airport_D_country" class="custom-select"> <!--D is used for departure-->
			                       <option value="">Select Country</option>
                                        @foreach($airports as $row)
                        				    <option value="{{ $row->countryName }}" @if($freight->departure_country == $row->countryName) selected @endif >{{ $row->countryName }}</option>
                        			    @endforeach
                        		    </select>
			                 </div>
			                 <div class="form-group">
			                       <label> City </label>
			                       <select name="departure_city1" id="airport_D_city" class="custom-select"> <!--D is used for departure-->
                                        <option value="">Select City</option>
                                        @if($freight->service_category == "air")   
                                            <option value="{{ $freight->departure_city }}" selected> {{ $freight->departure_city }} </option>
                                        @endif
                        		    </select>
			                     </div>
			                     <div class="form-group">
			                       <label>Airport </label>
			                       <select name="departure_port1" id="airport_D_port" class="custom-select"> <!--D is used for departure-->
                                        <option value="">Select Port</option>
                                           <option value="{{ $freight->departure_port }}" selected> {{ $freight->departure_port }} </option>
                                    </select>
			                     </div>
			               </div>
			                <div class="col-md-4">
			                   <h4><i class="fa fa-exchange" aria-hidden="true"></i> Transport </h4>
			                   <div class="form-group">
			                       <label>Estimated Time ( Days )</label>
			                         <input type="number" name="estimate_time1" id="estimate_time3" class="form-control" placeholder="Enter Estimated Time" value="{{ $freight->estimate_time }}">
			                   </div>
			                     <div class="form-group">
			                         <label>Transhipment Country ( Optional )</label>
			                         <input type="text" class="form-control" name="transhipment_country1" value="{{ $freight->transhipment_country }}">
                        		   
			                     </div>
			                     <div class="form-group">
			                         <label>Transhipment Port ( Optional )</label>
			                         <input type="text" class="form-control" name="transhipment_port1" value="{{ $freight->transhipment_port }}">
			                         
			                     </div>
			                  </div>
			                <div class="col-md-4">
			                   <h4><i class="fa fa-map-marker"></i> Arrival</h4>
			                   <div class="form-group">
			                       <label>Country</label>
			                       <select name="arriaval_country1" id="airport_A_country" class="custom-select"> <!--A is used for Arriavl-->
			                       <option value="">Select Country</option>
                                       @foreach($airports as $row)
                        				    <option value="{{ $row->countryName }}" @if($freight->arriaval_country == $row->countryName) selected @endif>{{ $row->countryName }}</option>
                        			    @endforeach
                        		    </select>
			                 </div>
			                 <div class="form-group">
			                       <label> City </label>
			                       <select name="arriaval_city1" id="airport_A_city" class="custom-select"> <!--A is used for Arriavl-->
                                        <option value="">Select City</option>  
                                        <option value="{{ $freight->arriaval_city }}" selected> {{ $freight->arriaval_city }} </option>
                        		    </select>
			                     </div>
			                     <div class="form-group">
			                       <label>Airport </label>
			                       <select name="arriaval_port1" id="airport_A_port" class="custom-select"> <!--A is used for Arriavl-->
                                        <option value="">Select Port</option>  
                                       
                                            <option value="{{ $freight->arriaval_port }}" selected> {{ $freight->arriaval_port }} </option>
                                       
                        		    </select>
			                     </div>
			               </div>
			            </div>
			            
			            
			            <!--End of air-->
			            
			       
			            
			            
			            
			            
			             <a class="gonext btn btn-primary text-white float-right">Next</a>
			            <a class="btn btn-secondary text-white goprevious">Previous</a>
			        </div>
			        
			        <!--end of step 2-->
			        
			        <!--step 3-->
			        
			        <div id="freightstep3" style="display:none;">
			            <div class="row">
			                <div class="col-md-12"> <b><p class="lead">Service Visibility</p></b> </div>
			             
			                <div class="col-md-4">
			                    <div class="row">
			                         <label class="col-md-12">Client Type</label> <br>
                                        <div class="form-group col-md-6">
                                              
                                        		<input type="checkbox" name="client_type[]" value="Freelancer" 
                                        		<?php 
                        			                    
                        			                  $result = explode(',',$freight->client_type);
                                                        foreach($result as $row){
                                                            if($row == "Freelancer"){
                                                                echo"checked";
                                                            }
                                                        }			                
			                                 ?>
			                           >Freelancer
                                        </div>
                                        
                                         <div class="form-group col-md-6">
                                        		<input type="checkbox" name="client_type[]" value="Producers" 	
                                        		<?php 
                        			                    
                        			                  $result = explode(',',$freight->client_type);
                                                        foreach($result as $row){
                                                            if($row == "Producers"){
                                                                echo"checked";
                                                            }
                                                        }			                
			                                 ?>
			                                 >Producers
                                        </div>
                                        
                                         <div class="form-group col-md-6">
                                        		<input type="checkbox" name="client_type[]" value="Freight Forwards"
                                        		<?php 
                        			                    
                        			                  $result = explode(',',$freight->client_type);
                                                        foreach($result as $row){
                                                            if($row == "Freight Forwards"){
                                                                echo"checked";
                                                            }
                                                        }			                
			                                 ?>
			                                 >Freight Forwards
                                        </div>
                                        
                                         <div class="form-group col-md-6">
                                        		<input type="checkbox" name="client_type[]" value="Local Client"
                                        		<?php 
                        			                    
                        			                  $result = explode(',',$freight->client_type);
                                                        foreach($result as $row){
                                                            if($row == "Local Client"){
                                                                echo"checked";
                                                            }
                                                        }			                
			                                 ?>>Local Client
                                        </div>
                                        
                                         <div class="form-group col-md-6">
                                        		<input type="checkbox" name="client_type[]" value="Student"
                                        		<?php 
                        			                    
                        			                  $result = explode(',',$freight->client_type);
                                                        foreach($result as $row){
                                                            if($row == "Student"){
                                                                echo"checked";
                                                            }
                                                        }			                
			                                 ?>>Student
                                        </div>
                                        
                                         <div class="form-group col-md-6">
                                        		<input type="checkbox" name="client_type[]" value="Individual" 
                                        		<?php 
                        			                    
                        			                  $result = explode(',',$freight->client_type);
                                                        foreach($result as $row){
                                                            if($row == "Individual"){
                                                                echo"checked";
                                                            }
                                                        }			                
			                                 ?>
                                        		>Individual
                                        </div>
                                </div>
                            </div>
			                <div class="col-md-4">
			                    <div class="form-group">
			                        <label>Location</label> <br>
			                        <input type="checkbox" name="location_type[]" value="Local Customer" 
			                        <?php 
                        			                    
                        			                  $result = explode(',',$freight->location_type);
                                                        foreach($result as $row){
                                                            if($row == "Local Customer"){
                                                                echo"checked";
                                                            }
                                                        }			                
			                                 ?>
			                        >Local Customer
			                    </div>
			                    <div class="form-group">
			                        <input type="checkbox" name="location_type[]" value="Cross Border Customer" 
			                            <?php 
                        			                    
                        			                  $result = explode(',',$freight->location_type);
                                                        foreach($result as $row){
                                                            if($row == "Cross Border Customer"){
                                                                echo"checked";
                                                            }
                                                        }			                
			                                 ?>
			                        >Cross Border Customer
			                    </div>
			                </div>
			                <div class="col-md-4">
			                    <div class="form-group">
			                        <label>Validity Date</label>
			                        <input type="date" name="freightvalidity" class="form-control" id="" placeholder="Enter Validity Date" value="{{ $freight->freightvalidity }}">
			                    </div>
			                    <div class="form-group">
			                        <label>LINER AGENT / COLOADER ( Optional )</label>
			                        <input type="text" name="comment" class="form-control" placeholder="Enter Comment For Rate List">
			                    </div>
			                    
			                </div>
			            </div>
			            <hr>
			            
			              
			              <!--air 3rd step-->
			              
                        <div  id="forair">
                            <div id="air_price_list" >
                            <div class="row" >
                         		<div class="col-md-12"> <b><p class="lead">Service Costs</p></b> </div>
                         	</div>
                            
                         	
                         	<?php 
                            $price_list = $freight->airport_price;
                            $data = Unserialize($price_list); ?>
                        	
                        	@if(!empty($data))
                        		@php
                        			$count = count($data);
                        		@endphp
                        	@else
                        		@php
                        			$count = 0;
                        		@endphp
                        	@endif

                            <?php 
                            for($i=0; $i<$count;$i++){ 
                            if($i == 0){
                            ?>
                            <div class="row">
	                            <div class="col">
				                    <div class="form-group">
				                        <label>Cost Type</label>
				                        <select name="air_cost_type[]" class="custom-select">
				                            <option value="">Select Cost Type</option>
				                             <option value="AIR FREIGHT" @if($data[$i]['cost_type'] == "AIR FREIGHT") selected @endif>AIR FREIGHT</option>
	                                        <option value="AIR WAY BILL" @if($data[$i]['cost_type'] == "AIR WAY BILL") selected @endif>AIR WAY BILL</option>
	                                        <option value="DELIVERY ORDER" @if($data[$i]['cost_type'] == "DELIVERY ORDER") selected @endif>DELIVERY ORDER</option>
	                                        <option value="WEIGHT COST" @if($data[$i]['cost_type'] == "WEIGHT COST") selected @endif>WEIGHT COST</option>
	                                        <option value="CUS" @if($data[$i]['cost_type'] == "CUS") selected @endif>CUS</option>
	                                        <option value="CAS" @if($data[$i]['cost_type'] == "CAS") selected @endif>CAS</option>
	                                         <option value="I.C.S" @if($data[$i]['cost_type'] == "I.C.S") selected @endif>I.C.S</option>
	                                         <option value="C.H.A" @if($data[$i]['cost_type'] == "C.H.A") selected @endif>C.H.A.</option>
	                                         <option value="DEVAINING" @if($data[$i]['cost_type'] == "DEVAINING") selected @endif>DEVAINING</option>
	                                         <option value="F.C.S" @if($data[$i]['cost_type'] == "F.C.S") selected @endif>F.C.S.</option>
	                                         <option value="S.C.C" @if($data[$i]['cost_type'] == "S.C.C") selected @endif>S.C.C </option>
	                                         <option value="M.O.C" @if($data[$i]['cost_type'] == "M.O.C") selected @endif>M.O.C</option>
				                        </select>
				                    </div>
				                </div>
			                 	<div class="col">
	                         	            <div class="form-group">
	                         			<label for="">Calculation</label>
	                         			<select name="air_calculaion[]" id="" class="custom-select" required>
	                         				<option value="">Select Calculation Type</option>
	                         				<option value="KG" @if($data[$i]['calculation'] == "KG") selected @endif >KG</option>
	                         				<option value="SET" @if($data[$i]['calculation'] == "SET") selected @endif>SET</option>
	                         			</select>
	                         		</div>
	                     	  	</div>
	                     	 	<div class="col">
	                                <div class="form-group">
	                         			<label for="">Quantity ( Above ) </label>
	                         			<select name="airquantity[]" class="custom-select" required>
	                         			    <option value="">Select Quantity</option>
	                         			    <option value="45" @if($data[$i]['quantity']=="45") selected @endif>45</option>
	                                        <option value="100" @if($data[$i]['quantity']=="100") selected @endif>100</option>
	                                        <option value="300" @if($data[$i]['quantity']=="300") selected @endif>300</option>
	                                        <option value="500" @if($data[$i]['quantity']=="500") selected @endif>500</option>
	                                        <option value="1000" @if($data[$i]['quantity']=="1000") selected @endif>1000</option>
	                         			</select>
	                         			<!--<input type="text" name="airquantity[]" value="{{ $data[$i]['quantity'] }}" placeholder="Enter Quantity In KG For Example 40 kg" class="form-control" required>-->
	                         		</div>
	                         	</div>
	                         	<div class="col">
	                         	     
	                              <div class="form-group">
	                        			 <label>Currency</label>
	                        			<select name="aircurrency_type[]" class="custom-select" required>
	                        			    <option value="">Select Currency</option>
	                        			    <option value="American Dollar" @if($data[$i]['currency_type'] == "American Dollar") selected @endif>American Dollar</option>
	                        			    <option value="Euro" @if($data[$i]['currency_type'] == "Euro") selected @endif>Euro</option>
	                        			    <option value="Great British Pound" @if($data[$i]['currency_type'] == "Great British Pound") selected @endif>Great British Pound</option>
	                        			    <option value="Turish Lira" @if($data[$i]['currency_type'] == "Turish Lira") selected @endif>Turish Lira</option>
	                        			    <option value="Australian Dollar" @if($data[$i]['currency_type'] == "Australian Dollar") selected @endif>Australian Dollar</option>
	                        			    <option value="Canadian Dollar" @if($data[$i]['currency_type'] == "Canadian Dollar") selected @endif>Canadian Dollar</option>
	                        			</select>
	                                </div>
	                        	 </div>
	                        	 <div class="col">
	                        	     
	                                	<div class="form-group">
	                        			    <label>Price</label>
	                        			    <input type="number" name="airprice[]" value="{{ $data[$i]['price'] }}" class="form-control" placeholder="Enter Price" required>
	                        	        </div>
	                        	</div>
	                        	<div class="col">
	                        	   <span class="btn btn-success mt-4" id="AddField" >Add More</span>
	                        		
	                        	</div>   
                        	</div>
                        	<?php } else{ ?>
                                    
                                    
                         	<div class="row">
                           	<div class="col">
			                    <div class="form-group">
			                        <label>Cost Type</label>
			                        <select name="air_cost_type[]" class="custom-select">
			                            <option value="">Select Cost Type</option>
                                        <option value="AIR WAY BILL" @if($data[$i]['cost_type'] == "AIR WAY BILL") selected @endif>AIR WAY BILL</option>
                                        <option value="DELIVERY ORDER" @if($data[$i]['cost_type'] == "DELIVERY ORDER") selected @endif>DELIVERY ORDER</option>
                                        <option value="WEIGHT COST" @if($data[$i]['cost_type'] == "WEIGHT COST") selected @endif>WEIGHT COST</option>
                                        <option value="CUS" @if($data[$i]['cost_type'] == "CUS") selected @endif>CUS</option>
                                        <option value="CAS" @if($data[$i]['cost_type'] == "CAS") selected @endif>CAS</option>
                                         <option value="I.C.S" @if($data[$i]['cost_type'] == "I.C.S") selected @endif>I.C.S</option>
                                         <option value="C.H.A" @if($data[$i]['cost_type'] == "C.H.A") selected @endif>C.H.A.</option>
                                         <option value="DEVAINING" @if($data[$i]['cost_type'] == "DEVAINING") selected @endif>DEVAINING</option>
                                         <option value="F.C.S" @if($data[$i]['cost_type'] == "F.C.S") selected @endif>F.C.S.</option>
                                         <option value="S.C.C" @if($data[$i]['cost_type'] == "S.C.C") selected @endif>S.C.C </option>
                                         <option value="M.O.C" @if($data[$i]['cost_type'] == "M.O.C") selected @endif>M.O.C</option>
			                        </select>
			                    </div>
			                </div>
			                 <div class="col">
                         	            <div class="form-group">
                         			<label for="">Calculation</label>
                         			<select name="air_calculaion[]" id="" class="custom-select" required>
                         				<option value="">Select Calculation Type</option>
                         				<option value="KG" @if($data[$i]['calculation'] == "KG") selected @endif >KG</option>
                         				<option value="SET" @if($data[$i]['calculation'] == "SET") selected @endif>SET</option>
                         			</select>
                         		</div>
                         	  </div>
                         	 <div class="col">
                         	      
                           
                                <div class="form-group">
                         			<label for="">Quantity ( Above Kg ) </label>
                         			<select name="airquantity[]" class="custom-select" required>
                         			    <option value="">Select Quantity</option>
                         			    <option value="45" @if($data[$i]['quantity']=="45") selected @endif>45</option>
                                        <option value="100" @if($data[$i]['quantity']=="100") selected @endif>100</option>
                                        <option value="300" @if($data[$i]['quantity']=="300") selected @endif>300</option>
                                        <option value="500" @if($data[$i]['quantity']=="500") selected @endif>500</option>
                                        <option value="1000" @if($data[$i]['quantity']=="1000") selected @endif>1000</option>
                         			</select>
                         			<!--<input type="text" name="airquantity[]" value="{{ $data[$i]['quantity'] }}" placeholder="Enter Quantity In KG For Example 40 kg" class="form-control" required>-->
                         		</div>
                           
                         		
                         	</div>
                         	<div class="col">
                         	     
                              <div class="form-group">
                        			 <label>Currency</label>
                        			<select name="aircurrency_type[]" class="custom-select" required>
                        			    <option value="">Select Currency</option>
                        			    <option value="American Dollar" @if($data[$i]['currency_type'] == "American Dollar") selected @endif>American Dollar</option>
                        			    <option value="Euro" @if($data[$i]['currency_type'] == "Euro") selected @endif>Euro</option>
                        			    <option value="Great British Pound" @if($data[$i]['currency_type'] == "Great British Pound") selected @endif>Great British Pound</option>
                        			    <option value="Turish Lira" @if($data[$i]['currency_type'] == "Turish Lira") selected @endif>Turish Lira</option>
                        			    <option value="Australian Dollar" @if($data[$i]['currency_type'] == "Australian Dollar") selected @endif>Australian Dollar</option>
                        			    <option value="Canadian Dollar" @if($data[$i]['currency_type'] == "Canadian Dollar") selected @endif>Canadian Dollar</option>
                        			</select>
                                </div>
                        	 </div>
                        	 <div class="col">
                        	     
                                	<div class="form-group">
                        			    <label>Price</label>
                        			    <input type="number" name="airprice[]" value="{{ $data[$i]['price'] }}" class="form-control" placeholder="Enter Price" required>
                        	        </div>
                        	</div>
                        	<div class="col">
                        	  
                        		<p class="btn btn-secondary mt-4"  onclick="deleterow(this)" style="cursor:pointer;">Remove</p>
                        	</div>   
                        	</div>        
                                        
                        <?php 
                            
                        } 
                                
                            }
                        ?>
                         	
                         
                         </div>
                        
                         
                         <!--end of air 3rd step-->
                         
			            <button class="btn btn-success float-right" type="submit">Submit</button>
			           
			            <a class="btn btn-secondary text-white goprevious">Previous</a>
			        </div>
			        
			        <!--end of step 3-->
			        
			        
			         </div>
 				</form>
		        </div>
		        
			   @endif
			   @if($freight->service_category == "land")
		        <div class="row">
					<form id="addfreight" action="{{ route('updatefeight') }}" method="post" enctype="mutipart/form-data" class="col-md-12">
				    @csrf
		            
			          <div class="col-md-10 offset-md-1 py-5">      
			           <!--Step 1-->
			           <input type="hidden" name="user_id" value="{{ Auth::User()->id }}">
		            <input type="hidden" name="id" value="{{ $freight->id }}">
		             <input type="hidden" name="service_category" value="{{ $freight->service_category }}">
			        <div id="freightstep1">
			           
			           <div class="row">
			               <div class="col-md-12">
				                   <div class="form-group">
				                       	<label>Sevice Category</label>				                       
				                       	@php
								        $data = unserialize($compantdetails->companyservice);
								        @endphp
						               	<select name="service_category" id="service_category" class="custom-select" disabled>
						                   	<option value="">Select Category</option>
						                   	@foreach($data["fre-fwrs"] as $row)
						                       	<option value="{{$row}}" @if($freight->service_category == $row) selected @endif>{{$row}}</option>
						                   	@endforeach
						               	</select>
		                       		</div>
			                       
			                       <!--sea-->
			                       <div class="form-group">
			                            <label>Service Type</label>
			                        <select name="service_type" id="service_type"  class="custom-select" required>
			                         
			                          <option value="" >Select Service Type</option>
			                          <option value="FTL" @if($freight->service_type == "FTL") selected @endif>FTL</option>
			                          <option value="LTL" @if($freight->service_type == "LTL") selected @endif>LTL</option>
                        		    </select>
                        		    </div>
                        		    
                        		    <!--end of sea-->
                        		    
                        		   
                                    
			                   </div>
			               
			           </div>
			            <a class="btn btn-primary text-white float-right gonext">Next</a>
			        </div>
			        
			        <!--End of step 1-->
			        
			        <!--step 2-->
			        
			        
			        <div id="freightstep2" style="display:none;">
			            
			            
			          
			            
			          
			            
			            
			            
			            <!--For Land-->
			            
			            
			            <div class="row">
			               <div class="col-md-4">
			                   <h4><i class="fa fa-map-marker"></i> Departure</h4>
			                   <div class="form-group">
			                       <label>Country</label>
			                       <select name="departure_country3" id="land_D_country" class="custom-select"> <!--D is used for departure-->
			                       <option value="">Select Country</option>
                                        @foreach($land as $row)
                        				    <option value="{{ $row->name }}" @if($freight->service_category == "land" && $freight->departure_country == $row->name) selected @endif>{{ $row->name }}</option>
                        			    @endforeach
                        		    </select>
			                 </div>
			                 <div class="form-group">
			                       <label> City </label>
			                       <select name="departure_city3" id="land_D_city" class="custom-select"> <!--D is used for departure-->
                                        <option value="">Select City</option>  
                                        @if($freight->service_category == "land")   
                                            <option value="{{ $freight->departure_city }}" selected> {{ $freight->departure_city }} </option>
                                        @endif
                        		    </select>
			                     </div>
			                     <div class="form-group">
			                       <label> Address </label>
			                       <input type="text" class="form-control" name="departure_port3"  @if($freight->service_category == "land") value="{{ $freight->departure_port }}" @endif>
			                       <!--<select name="departure_port3" id="" class="custom-select"> <!--D is used for departure-->
                          <!--              <option value="">Select Port</option>  -->
                        		<!--    </select>-->
			                     </div>
			               </div>
			               <div class="col-md-4">
			                   <h4><i class="fa fa-exchange" aria-hidden="true"></i> Transport </h4>
			                   <div class="form-group">
			                       <label>Estimated Time ( Days )</label>
			                         <input type="number" name="estimate_time3" id="estimate_time3" class="form-control" placeholder="Enter Estimated Time" @if($freight->service_category == "land")value="{{ $freight->estimate_time }}" @endif">
			                   </div>
			                     <div class="form-group">
			                         <label>DOMESTIC CUSTOMS ( Optional )</label>
			                         <input type="text" class="form-control" name="transhipment_country3" @if($freight->service_category == "land") value="{{ $freight->transhipment_country }}"@endif>
                        		   
			                     </div>
			                     <div class="form-group">
			                         <label>DESTINATION CUSTOMS ( Optional )</label>
			                         <input type="text" class="form-control" name="transhipment_port3" @if($freight->service_category == "land") value="{{ $freight->transhipment_port }}"@endif>
			                         
			                     </div>
			                  </div>
			                <div class="col-md-4">
			                   <h4><i class="fa fa-map-marker"></i> Arrival</h4>
			                   <div class="form-group">
			                       <label>Country</label>
			                       <select name="arriaval_country3" id="land_A_country" class="custom-select"> <!--A is used for departure-->
			                       <option value="">Select Country</option>
                                       @foreach($land as $row)
                        				    <option value="{{ $row->name }}" @if($freight->service_category == "land" && $freight->arriaval_country == $row->name) selected @endif>{{ $row->name }}</option>
                        			    @endforeach
                        		    </select>
			                 </div>
			                 <div class="form-group">
			                       <label> City </label>
			                       <select name="arriaval_city3" id="land_A_city" class="custom-select"> <!--A is used for departure-->
                                      <option value="">Select City</option>    
                                       @if($freight->service_category == "land")   
                                            <option value="{{ $freight->arriaval_city }}" selected> {{ $freight->arriaval_city }} </option>
                                        @endif
                        		    </select>
			                     </div>
			                     <div class="form-group">
			                       <label> Address </label>
			                       <input type="text" class="form-control" name="arriaval_port3" @if($freight->service_category == "land") value="{{ $freight->arriaval_port }}" @endif>
			                       
			                     </div>
			               </div>
			            </div>
			            <!--End of land-->
			            
			            
			             <a class="gonext btn btn-primary text-white float-right">Next</a>
			            <a class="btn btn-secondary text-white goprevious">Previous</a>
			        </div>
			        
			        <!--end of step 2-->
			        
			        <!--step 3-->
			        
			        <div id="freightstep3" style="display:none;">
			            <div class="row">
			                <div class="col-md-12"> <b><p class="lead">Service Visibility</p></b> </div>
			             
			                <div class="col-md-4">
			                    
			                    <div class="row">
			                         <label class="col-md-12">Client Type</label> <br>
                                        <div class="form-group col-md-6">
                                              
                                        		<input type="checkbox" name="client_type[]" value="Freelancer" 
                                        		<?php 
                        			                    
                        			                  $result = explode(',',$freight->client_type);
                                                        foreach($result as $row){
                                                            if($row == "Freelancer"){
                                                                echo"checked";
                                                            }
                                                        }			                
			                                 ?>
			                           >Freelancer
                                        </div>
                                        
                                         <div class="form-group col-md-6">
                                        		<input type="checkbox" name="client_type[]" value="Producers" 	
                                        		<?php 
                        			                    
                        			                  $result = explode(',',$freight->client_type);
                                                        foreach($result as $row){
                                                            if($row == "Producers"){
                                                                echo"checked";
                                                            }
                                                        }			                
			                                 ?>
			                                 >Producers
                                        </div>
                                        
                                         <div class="form-group col-md-6">
                                        		<input type="checkbox" name="client_type[]" value="Freight Forwards"
                                        		<?php 
                        			                    
                        			                  $result = explode(',',$freight->client_type);
                                                        foreach($result as $row){
                                                            if($row == "Freight Forwards"){
                                                                echo"checked";
                                                            }
                                                        }			                
			                                 ?>
			                                 >Freight Forwards
                                        </div>
                                        
                                         <div class="form-group col-md-6">
                                        		<input type="checkbox" name="client_type[]" value="Local Client"
                                        		<?php 
                        			                    
                        			                  $result = explode(',',$freight->client_type);
                                                        foreach($result as $row){
                                                            if($row == "Local Client"){
                                                                echo"checked";
                                                            }
                                                        }			                
			                                 ?>>Local Client
                                        </div>
                                        
                                         <div class="form-group col-md-6">
                                        		<input type="checkbox" name="client_type[]" value="Student"
                                        		<?php 
                        			                    
                        			                  $result = explode(',',$freight->client_type);
                                                        foreach($result as $row){
                                                            if($row == "Student"){
                                                                echo"checked";
                                                            }
                                                        }			                
			                                 ?>>Student
                                        </div>
                                        
                                         <div class="form-group col-md-6">
                                        		<input type="checkbox" name="client_type[]" value="Individual" 
                                        		<?php 
                        			                    
                        			                  $result = explode(',',$freight->client_type);
                                                        foreach($result as $row){
                                                            if($row == "Individual"){
                                                                echo"checked";
                                                            }
                                                        }			                
			                                 ?>
                                        		>Individual
                                        </div>
                                </div>
                            </div>
			                <div class="col-md-4">
			                    <div class="form-group">
			                        <label>Location</label> <br>
			                        <input type="checkbox" name="location_type[]" value="Local Customer" 
			                        <?php 
                        			                    
                        			                  $result = explode(',',$freight->location_type);
                                                        foreach($result as $row){
                                                            if($row == "Local Customer"){
                                                                echo"checked";
                                                            }
                                                        }			                
			                                 ?>
			                        >Local Customer
			                    </div>
			                    <div class="form-group">
			                        <input type="checkbox" name="location_type[]" value="Cross Border Customer" 
			                            <?php 
                        			                    
                        			                  $result = explode(',',$freight->location_type);
                                                        foreach($result as $row){
                                                            if($row == "Cross Border Customer"){
                                                                echo"checked";
                                                            }
                                                        }			                
			                                 ?>
			                        >Cross Border Customer
			                    </div>
			                </div>
			                <div class="col-md-4">
			                    <div class="form-group">
			                        <label>Validity Date</label>
			                        <input type="date" name="freightvalidity" class="form-control" id="" placeholder="Enter Validity Date" value="{{ $freight->freightvalidity }}">
			                    </div>
			                    <div class="form-group">
			                        <label>LINER AGENT / COLOADER ( Optional )</label>
			                        <input type="text" name="comment" class="form-control" placeholder="Enter Comment For Rate List" value="{{ $freight->comment }}">
			                    </div>
			                    
			                </div>
			            </div>
			            <hr>
			             <div id="landpricelist">
			                <div class="row">
			                <div class="col-md-12"> <b><p class="lead">Service Costs</p></b> </div>
			                
			                		<?php 
                            $price_list = $freight->airport_price;
                            $data = Unserialize($price_list);
                            $count = count($data);
                            for($i=0; $i<$count;$i++){ 
                            if($i == 0){
                            ?>
                            <div class="row">
			                <div class="col">
			                    <div class="form-group">
			                        <label>Cost Type</label>
			                        <select name="cost_type_for_land[]" class="custom-select">
			                            <option value="">Select Cost Type</option>
			                            <option value="OCEAN FREIGHT" @if($data[$i]['cost_type'] == "OCEAN FREIGHT") selected @endif >OCEAN FREIGHT</option>
			                            <option value="O-THC" @if($data[$i]['cost_type'] == "O-THC") selected @endif>O-THC</option>
			                            <option value="D-THC" @if($data[$i]['cost_type'] == "D-THC") selected @endif>D-THC</option>
			                            <option value="BILL OF LADING" @if($data[$i]['cost_type'] == "BILL OF LADING") selected @endif>BILL OF LADING</option>
			                            <option value="DELIEVER ORDER" @if($data[$i]['cost_type'] == "DELIEVER ORDER") selected @endif>DELIEVER ORDER</option>
			                            <option value="E.N.S" @if($data[$i]['cost_type'] == "E.N.S") selected @endif>E.N.S</option>
			                            <option value="LCL SERVICE FEE" @if($data[$i]['cost_type'] == "LCL SERVICE FEE") selected @endif>LCL SERVICE FEE</option>
                                        <option value="LOW SULPHURE SRC" @if($data[$i]['cost_type'] == "LOW SULPHURE SRC") selected @endif>LOW SULPHURE SRC</option>
                                        <option value="IMO 2020" @if($data[$i]['cost_type'] == "IMO 2020") selected @endif>IMO 2020</option>
                                        <option value="SEAL FEE" @if($data[$i]['cost_type'] == "SEAL FEE") selected @endif>SEAL FEE </option>
                                        <option value="DOCUMENTATION FEE" @if($data[$i]['cost_type'] == "DOCUMENTATION FEE") selected @endif>DOCUMENTATION FEE</option>
                                        <option value="FREE ZONE EXTRA FEE" @if($data[$i]['cost_type'] == "FREE ZONE EXTRA FEE") selected @endif>FREE ZONE EXTRA FEE </option>
                                        <option value="FREE ZONE EXTRA FEE " @if($data[$i]['cost_type'] == "FREE ZONE EXTRA FEE") selected @endif>FREE ZONE EXTRA FEE </option>
                                        <option value="BONDED TRUCK FEE" @if($data[$i]['cost_type'] == "BONDED TRUCK FEE") selected @endif>BONDED TRUCK FEE</option>
                                        <option value="WAREHOUSE FEE" @if($data[$i]['cost_type'] == "WAREHOUSE FEE") selected @endif>WAREHOUSE FEE</option>
                                        <option value="STORAGE FEE" @if($data[$i]['cost_type'] == "STORAGE FEE") selected @endif>STORAGE FEE</option>
                                        <option value="HANDLING FEE" @if($data[$i]['cost_type'] == "HANDLING FEE") selected @endif>HANDLING FEE</option>
                                        <option value="STUFFING FEE" @if($data[$i]['cost_type'] == "STUFFING FEE") selected @endif>STUFFING FEE</option>
                                        <option value="CERTIFICATE FEE" @if($data[$i]['cost_type'] == "CERTIFICATE FEE") selected @endif>CERTIFICATE FEE</option>
                                        <option value="LAND FREIGHT" @if($data[$i]['cost_type'] == "LAND FREIGHT") selected @endif>LAND FREIGHT</option>
                                        <option value="C.M.R." @if($data[$i]['cost_type'] == "C.M.R.") selected @endif>C.M.R.</option>
			                        </select>
			                    </div>
			                </div>
			                <div class="col" >
			                    <div class="form-group">
			                        <label>Calculation</label>
			                        <select name="calculaion_for_land[]" class="custom-select text-uppercase">
			                           
			                           <option value="">SELECT TRAILER</option>
			                            <option value="FLAT BED TRAILER" @if($data[$i]['calculation'] == "FLAT BED TRAILER") selected @endif>FLAT BED TRAILER</option>
                                        <option value="DRY VAN AND ENCLOSED TRAILERS" @if($data[$i]['calculation'] == "DRY VAN AND ENCLOSED TRAILERS") selected @endif>DRY VAN AND ENCLOSED TRAILERS</option>
                                        <option value="REFRIGERATED TRAILERS AND REEFERS" @if($data[$i]['calculation'] == "REFRIGERATED TRAILERS AND REEFERS") selected @endif>REFRIGERATED TRAILERS AND REEFERS</option>
                                        <option value="LOWBOY TRAILER" @if($data[$i]['calculation'] == "LOWBOY TRAILER") selected @endif>LOWBOY TRAILER</option>
                                        <option value="STEP DECK TRAILERS – SINGLE DROP TRAILERS" @if($data[$i]['calculation'] == "STEP DECK TRAILERS – SINGLE DROP TRAILERS") selected @endif>STEP DECK TRAILERS – SINGLE DROP TRAILERS</option>
                                        <option value="EXTENDABLE FLATBED STRETCH TRAILERS" @if($data[$i]['calculation'] == "EXTENDABLE FLATBED STRETCH TRAILERS") selected @endif>EXTENDABLE FLATBED STRETCH TRAILERS</option>
                                        <option value="STRETCH SINGLE DROP DECK TRAILER" @if($data[$i]['calculation'] == "STRETCH SINGLE DROP DECK TRAILER") selected @endif>STRETCH SINGLE DROP DECK TRAILER</option>
                                        <option value="STRETCH DOUBLE DROP TRAILERS" @if($data[$i]['calculation'] == "STRETCH DOUBLE DROP TRAILERS") selected @endif>STRETCH DOUBLE DROP TRAILERS</option>
                                        <option value="EXTENDABLE DOUBLE DROP TRAILERS" @if($data[$i]['calculation'] == "EXTENDABLE DOUBLE DROP TRAILERS") selected @endif>EXTENDABLE DOUBLE DROP TRAILERS</option>
                                        <option value="RGN OR REMOVABLE GOOSENECK TRAILERS" @if($data[$i]['calculation'] == "RGN OR REMOVABLE GOOSENECK TRAILERS") selected @endif>RGN OR REMOVABLE GOOSENECK TRAILERS</option>
                                        <option value="STRETCH RGN OR REMOVABLE GOOSENECK TRAILERS" @if($data[$i]['calculation'] == "STRETCH RGN OR REMOVABLE GOOSENECK TRAILERS") selected @endif>STRETCH RGN OR REMOVABLE GOOSENECK TRAILERS</option>
                                        <option value="CONESTOGA TRAILERS" @if($data[$i]['calculation'] == "CONESTOGA TRAILERS") selected @endif>CONESTOGA TRAILERS</option>
                                        <option value="SIDE KIT TRAILERS" @if($data[$i]['calculation'] == "SIDE KIT TRAILERS") selected @endif>SIDE KIT TRAILERS</option>
                                        <option value="POWER ONLY" @if($data[$i]['calculation'] == "POWER ONLY") selected @endif>POWER ONLY</option>
                                        <option value="SPECIALIZED TRAILERS" @if($data[$i]['calculation'] == "SPECIALIZED TRAILERS") selected @endif>SPECIALIZED TRAILERS</option>
                                        <option value="SEMI TRAILER" @if($data[$i]['calculation'] == "SEMI TRAILER") selected @endif>SEMI TRAILER</option>
                                        <option value="JUMBO- BOX TRAILER" @if($data[$i]['calculation'] == "JUMBO- BOX TRAILER") selected @endif>JUMBO- BOX TRAILER</option>
                                        <option value="MEGA TRAILER" @if($data[$i]['calculation'] == "MEGA TRAILER") selected @endif>MEGA TRAILER</option>
                                        <option value="REEFER TRAILER" @if($data[$i]['calculation'] == "REEFER TRAILER") selected @endif>REEFER TRAILER</option>
                                        <option value="CURTAIN TRAILER" @if($data[$i]['calculation'] == "CURTAIN TRAILER") selected @endif>CURTAIN TRAILER</option>
                                        <option value="TARPAULIN TRAILER" @if($data[$i]['calculation'] == "TARPAULIN TRAILER") selected @endif>TARPAULIN TRAILER</option>
                                        <option value="SET" @if($data[$i]['calculation'] == "SET") selected @endif>SET</option>
			                        </select>
			                        
			                    </div>
			                </div>
			                <div class="col">
    			                    <div class="form-group">
    			                        <label>Currency</label>
    			                        <select name="currency_type_for_land[]" class="custom-select">
    			                             <option value="">Select Currency</option>
    			                            <option value="American Dollar" @if($data[$i]['currency_type'] == "American Dollar") selected @endif>American Dollar</option>
    			                            <option value="Euro" @if($data[$i]['currency_type'] == "Euro") selected @endif>Euro</option>
    			                            <option value="Great British Pound" @if($data[$i]['currency_type'] == "Great British Pound") selected @endif>Great British Pound</option>
    			                            <option value="Turish Lira" @if($data[$i]['currency_type'] == "Turish Lira") selected @endif>Turish Lira</option>
    			                            <option value="Australian Dollar" @if($data[$i]['currency_type'] == "Australian Dollar") selected @endif>Australian Dollar</option>
    			                            <option value="Canadian Dollar" @if($data[$i]['currency_type'] == "Canadian Dollar") selected @endif>Canadian Dollar</option>
    			                        </select>
    			                    </div>
    			                </div>
    			                <div class="col">
    			                    <div class="form-group">
    			                        <label>Price</label>
    			                        <input type="number" name="price_for_land[]" class="form-control" placeholder="Enter Price" value="{{ $data[$i]['price'] }}">
    			                    </div>
    			                </div>
    			             <div class="col">
                        	    <span class="btn btn-success mt-4" id="AddFieldforland" >Add More</span>
                        	</div>
                        	</div>
			                <?php
			                } 
			                else {
			                ?>
                            <div class="row">
			                <div class="col">
			                    <div class="form-group">
			                        <label>Cost Type</label>
			                        <select name="cost_type_for_land[]" class="custom-select">
			                            <option value="">Select Cost Type</option>
			                            <option value="OCEAN FREIGHT" @if($data[$i]['cost_type'] == "OCEAN FREIGHT") selected @endif >OCEAN FREIGHT</option>
			                            <option value="O-THC" @if($data[$i]['cost_type'] == "O-THC") selected @endif>O-THC</option>
			                            <option value="D-THC" @if($data[$i]['cost_type'] == "D-THC") selected @endif>D-THC</option>
			                            <option value="BILL OF LADING" @if($data[$i]['cost_type'] == "BILL OF LADING") selected @endif>BILL OF LADING</option>
			                            <option value="DELIEVER ORDER" @if($data[$i]['cost_type'] == "DELIEVER ORDER") selected @endif>DELIEVER ORDER</option>
			                            <option value="E.N.S" @if($data[$i]['cost_type'] == "E.N.S") selected @endif>E.N.S</option>
			                            <option value="LCL SERVICE FEE" @if($data[$i]['cost_type'] == "LCL SERVICE FEE") selected @endif>LCL SERVICE FEE</option>
                                        <option value="LOW SULPHURE SRC" @if($data[$i]['cost_type'] == "LOW SULPHURE SRC") selected @endif>LOW SULPHURE SRC</option>
                                        <option value="IMO 2020" @if($data[$i]['cost_type'] == "IMO 2020") selected @endif>IMO 2020</option>
                                        <option value="SEAL FEE" @if($data[$i]['cost_type'] == "SEAL FEE") selected @endif>SEAL FEE </option>
                                        <option value="ISPS" @if($data[$i]['cost_type'] == "ISPS") selected @endif>ISPS </option>
                                        <option value="FREE IN" @if($data[$i]['cost_type'] == "FREE IN") selected @endif>FREE IN</option>
                                        <option value="FREE OUT" @if($data[$i]['cost_type'] == "FREE OUT") selected @endif>FREE OUT</option>
                                        <option value="LINER IN" @if($data[$i]['cost_type'] == "LINER IN") selected @endif>LINER IN</option>
                                        <option value="LINER OUT" @if($data[$i]['cost_type'] == "LINER OUT") selected @endif>LINER OUT</option>
                                        <option value="DOCUMENTATION FEE" @if($data[$i]['cost_type'] == "DOCUMENTATION FEE") selected @endif>DOCUMENTATION FEE</option>
                                        <option value="FREE ZONE EXTRA FEE" @if($data[$i]['cost_type'] == "FREE ZONE EXTRA FEE") selected @endif>FREE ZONE EXTRA FEE </option>
                                        <option value="FREE ZONE EXTRA FEE " @if($data[$i]['cost_type'] == "FREE ZONE EXTRA FEE") selected @endif>FREE ZONE EXTRA FEE </option>
                                        <option value="BONDED TRUCK FEE" @if($data[$i]['cost_type'] == "BONDED TRUCK FEE") selected @endif>BONDED TRUCK FEE</option>
                                        <option value="WAREHOUSE FEE" @if($data[$i]['cost_type'] == "WAREHOUSE FEE") selected @endif>WAREHOUSE FEE</option>
                                        <option value="STORAGE FEE" @if($data[$i]['cost_type'] == "STORAGE FEE") selected @endif>STORAGE FEE</option>
                                        <option value="HANDLING FEE" @if($data[$i]['cost_type'] == "HANDLING FEE") selected @endif>HANDLING FEE</option>
                                        <option value="STUFFING FEE" @if($data[$i]['cost_type'] == "STUFFING FEE") selected @endif>STUFFING FEE</option>
                                        <option value="CERTIFICATE FEE" @if($data[$i]['cost_type'] == "CERTIFICATE FEE") selected @endif>CERTIFICATE FEE</option>
                                        <option value="SUEZ CANAL SRC" @if($data[$i]['cost_type'] == "SUEZ CANAL SRC") selected @endif>SUEZ CANAL SRC</option>
                                        <option value="B.O.F." @if($data[$i]['cost_type'] == "B.O.F.") selected @endif>B.O.F.</option>
                                        <option value="B.A.F." @if($data[$i]['cost_type'] == "B.A.F.") selected @endif>B.A.F.</option>
                                        <option value="C.A.F." @if($data[$i]['cost_type'] == "C.A.F.") selected @endif>C.A.F.</option>
			                        </select>
			                    </div>
			                </div>
			                 <div class="col" >
			                    <div class="form-group">
			                        <label>Calculation</label>
			                        <select name="calculaion_for_land[]" class="custom-select text-uppercase">
			                           
			                           <option value="">SELECT TRAILER</option>
			                            <option value="FLAT BED TRAILER" @if($data[$i]['calculation'] == "FLAT BED TRAILER") selected @endif>FLAT BED TRAILER</option>
                                        <option value="DRY VAN AND ENCLOSED TRAILERS" @if($data[$i]['calculation'] == "DRY VAN AND ENCLOSED TRAILERS") selected @endif>DRY VAN AND ENCLOSED TRAILERS</option>
                                        <option value="REFRIGERATED TRAILERS AND REEFERS" @if($data[$i]['calculation'] == "REFRIGERATED TRAILERS AND REEFERS") selected @endif>REFRIGERATED TRAILERS AND REEFERS</option>
                                        <option value="LOWBOY TRAILER" @if($data[$i]['calculation'] == "LOWBOY TRAILER") selected @endif>LOWBOY TRAILER</option>
                                        <option value="STEP DECK TRAILERS – SINGLE DROP TRAILERS" @if($data[$i]['calculation'] == "STEP DECK TRAILERS – SINGLE DROP TRAILERS") selected @endif>STEP DECK TRAILERS – SINGLE DROP TRAILERS</option>
                                        <option value="EXTENDABLE FLATBED STRETCH TRAILERS" @if($data[$i]['calculation'] == "EXTENDABLE FLATBED STRETCH TRAILERS") selected @endif>EXTENDABLE FLATBED STRETCH TRAILERS</option>
                                        <option value="STRETCH SINGLE DROP DECK TRAILER" @if($data[$i]['calculation'] == "STRETCH SINGLE DROP DECK TRAILER") selected @endif>STRETCH SINGLE DROP DECK TRAILER</option>
                                        <option value="STRETCH DOUBLE DROP TRAILERS" @if($data[$i]['calculation'] == "STRETCH DOUBLE DROP TRAILERS") selected @endif>STRETCH DOUBLE DROP TRAILERS</option>
                                        <option value="EXTENDABLE DOUBLE DROP TRAILERS" @if($data[$i]['calculation'] == "EXTENDABLE DOUBLE DROP TRAILERS") selected @endif>EXTENDABLE DOUBLE DROP TRAILERS</option>
                                        <option value="RGN OR REMOVABLE GOOSENECK TRAILERS" @if($data[$i]['calculation'] == "RGN OR REMOVABLE GOOSENECK TRAILERS") selected @endif>RGN OR REMOVABLE GOOSENECK TRAILERS</option>
                                        <option value="STRETCH RGN OR REMOVABLE GOOSENECK TRAILERS" @if($data[$i]['calculation'] == "STRETCH RGN OR REMOVABLE GOOSENECK TRAILERS") selected @endif>STRETCH RGN OR REMOVABLE GOOSENECK TRAILERS</option>
                                        <option value="CONESTOGA TRAILERS" @if($data[$i]['calculation'] == "CONESTOGA TRAILERS") selected @endif>CONESTOGA TRAILERS</option>
                                        <option value="SIDE KIT TRAILERS" @if($data[$i]['calculation'] == "SIDE KIT TRAILERS") selected @endif>SIDE KIT TRAILERS</option>
                                        <option value="POWER ONLY" @if($data[$i]['calculation'] == "POWER ONLY") selected @endif>POWER ONLY</option>
                                        <option value="SPECIALIZED TRAILERS" @if($data[$i]['calculation'] == "SPECIALIZED TRAILERS") selected @endif>SPECIALIZED TRAILERS</option>
                                        <option value="SEMI TRAILER" @if($data[$i]['calculation'] == "SEMI TRAILER") selected @endif>SEMI TRAILER</option>
                                        <option value="JUMBO- BOX TRAILER" @if($data[$i]['calculation'] == "JUMBO- BOX TRAILER") selected @endif>JUMBO- BOX TRAILER</option>
                                        <option value="MEGA TRAILER" @if($data[$i]['calculation'] == "MEGA TRAILER") selected @endif>MEGA TRAILER</option>
                                        <option value="REEFER TRAILER" @if($data[$i]['calculation'] == "REEFER TRAILER") selected @endif>REEFER TRAILER</option>
                                        <option value="CURTAIN TRAILER" @if($data[$i]['calculation'] == "CURTAIN TRAILER") selected @endif>CURTAIN TRAILER</option>
                                        <option value="TARPAULIN TRAILER" @if($data[$i]['calculation'] == "TARPAULIN TRAILER") selected @endif>TARPAULIN TRAILER</option>
                                        <option value="SET" @if($data[$i]['calculation'] == "SET") selected @endif>SET</option>
			                        </select>
			                        
			                    </div>
			                </div>
			                <div class="col">
    			                    <div class="form-group">
    			                        <label>Currency</label>
    			                        <select name="currency_type_for_land[]" class="custom-select">
    			                             <option value="">Select Currency</option>
    			                            <option value="American Dollar" @if($data[$i]['currency_type'] == "American Dollar") selected @endif>American Dollar</option>
    			                            <option value="Euro" @if($data[$i]['currency_type'] == "Euro") selected @endif>Euro</option>
    			                            <option value="Great British Pound" @if($data[$i]['currency_type'] == "Great British Pound") selected @endif>Great British Pound</option>
    			                            <option value="Turish Lira" @if($data[$i]['currency_type'] == "Turish Lira") selected @endif>Turish Lira</option>
    			                            <option value="Australian Dollar" @if($data[$i]['currency_type'] == "Australian Dollar") selected @endif>Australian Dollar</option>
    			                            <option value="Canadian Dollar" @if($data[$i]['currency_type'] == "Canadian Dollar") selected @endif>Canadian Dollar</option>
    			                        </select>
    			                    </div>
    			                </div>
    			                <div class="col">
    			                    <div class="form-group">
    			                        <label>Price</label>
    			                        <input type="number" name="price_for_land[]" class="form-control" placeholder="Enter Price" value="{{ $data[$i]['price'] }}">
    			                    </div>
    			                </div>
    			             <div class="col">
                        	    <p class="btn btn-secondary mt-4"  onclick="deleterow(this)" style="cursor:pointer;">Remove</p>
                        	</div>
    			                </div>
			               <?php  } }
			               ?>
			                </div>
			             <!--</div>-->
			         </div>
			            
			             
			              <button class="btn btn-success float-right" type="submit">Submit</button>
			           
			            <a class="btn btn-secondary text-white goprevious">Previous</a>
			        </div>
			        
			        <!--end of step 3-->
			        
			         </div>
 				</form>
		        </div>
		        
			   @endif
			   @if($freight->service_category == "sea")
		        <div class="row">
					<form id="addfreight" action="{{ route('updatefeight') }}" method="post" enctype="mutipart/form-data" class="col-md-12">
				    @csrf
		            <input type="hidden" name="user_id" value="{{ Auth::User()->id }}">
		            <input type="hidden" name="id" value="{{ $freight->id }}">
		             <input type="hidden" name="service_category" value="{{ $freight->service_category }}">
		             <!--<input type="hidden" name="service_type" value="{{ $freight->service_type }}">-->
			          <div class="col-md-10 offset-md-1 py-5">      
			           <!--Step 1-->
			           
			        <div id="freightstep1">
			           
			           <div class="row">
			               <div class="col-md-12">
			                   <div class="form-group">
			                       <label>Sevice Category</label>				                       
				                       	@php
								        $data = unserialize($compantdetails->companyservice);
								        @endphp
						               	<select name="service_category" id="service_category" class="custom-select" disabled>
						                   	<option value="">Select Category</option>
						                   	@foreach($data["fre-fwrs"] as $row)
						                       	<option value="{{$row}}" @if($freight->service_category == $row) selected @endif>{{$row}}</option>
						                   	@endforeach
						               	</select>
			                       </div>
			                       
			                       <!--sea-->
			                       <div class="form-group" id="category_condition1">
			                            <label>Service Type</label>
			                        <select name="service_type" id="service_type"  class="custom-select" required >
			                          <option value="">Select Service Type</option>
			                          <option value="LCL" @if($freight->service_type == "LCL") selected @endif>LCL</option>
			                          <option value="FCL" @if($freight->service_type == "FCL") selected @endif>FCL</option>
                        		    </select>
                        		    </div>
                        		    
                        		    <!--end of sea-->
                        		    
                        		   
                                    
			                   </div>
			               
			           </div>
			            <a class="btn btn-primary text-white float-right gonext">Next</a>
			        </div>
			        
			        <!--End of step 1-->
			        
			        <!--step 2-->
			        
			        
			        <div id="freightstep2" style="display:none;">
			           
			            
			            <!--For Sea-->
			            
			            
			            <div class="row">
			               <div class="col-md-4">
			                   <h4><i class="fa fa-map-marker"></i> Departure</h4>
			                   <div class="form-group">
			                       <label>Country</label>
			                       <select name="departure_country2" id="D_country" class="custom-select"> <!--D is used for departure-->
			                       <option value="">Select Country</option>
                                        @foreach($countrys as $row)
                        				    <option value="{{ $row->Countries }}" @if($freight->departure_country == $row->Countries) selected @endif>{{ $row->Countries }}</option>
                        			    @endforeach
                        		    </select>
			                 </div>
			                 <div class="form-group">
			                       <label> City </label>
			                       <select name="departure_city2" id="D_city" class="custom-select"> <!--D is used for departure-->
                                        <option value="">Select City</option>  
                                        @if($freight->service_category == "sea")   
                                            <option value="{{ $freight->departure_city }}" selected> {{ $freight->departure_city }} </option>
                                        @endif
                        		    </select>
			                     </div>
			                     <div class="form-group">
			                       <label>Seaport </label>
			                       <select name="departure_port2" id="D_port" class="custom-select"> <!--D is used for departure-->
                                        <option value="">Select Port</option>  
                                        @if($freight->service_category == "sea")   
                                            <option value="{{ $freight->departure_city }}" selected> {{ $freight->departure_city }} </option>
                                        @endif
                        		    </select>
			                     </div>
			               </div>
			               <div class="col-md-4">
			                   <h4><i class="fa fa-exchange" aria-hidden="true"></i> Transport </h4>
			                   <div class="form-group">
			                       <label>Estimated Time ( Days )</label>
			                         <input type="number" name="estimate_time2" id="estimate_time3" class="form-control" placeholder="Enter Estimated Time" @if($freight->service_category == "sea")value="{{ $freight->estimate_time }}" @endif">
			                   </div>
			                     <div class="form-group">
			                         <label>Transhipment Country ( Optional )</label>
			                         <input type="text" class="form-control" name="transhipment_country2" @if($freight->service_category == "sea") value="{{ $freight->transhipment_country }}"@endif>
                        		   
			                     </div>
			                     <div class="form-group">
			                         <label>Transhipment Port ( Optional )</label>
			                         <input type="text" class="form-control" name="transhipment_port2" @if($freight->service_category == "sea") value="{{ $freight->transhipment_port }}"@endif>
			                        
			                     </div>
			                  </div>
			                <div class="col-md-4">
			                   <h4><i class="fa fa-map-marker"></i> Arrival</h4>
			                   <div class="form-group">
			                       <label>Country</label>
			                       <select name="arriaval_country2" id="A_country" class="custom-select"> <!--A is used for Arriavl-->
			                       <option value="">Select Country</option>
                                       @foreach($countrys as $row)
                        				    <option value="{{ $row->Countries }}" @if($freight->service_category == "sea" && $freight->arriaval_country == $row->Countries) selected @endif>{{ $row->Countries }}</option>
                        			    @endforeach
                        		    </select>
			                 </div>
			                 <div class="form-group">
			                       <label> City </label>
			                       <select name="arriaval_city2" id="A_city" class="custom-select"> <!--A is used for Arriavl-->
                                        <option value="">Select City</option>  
                                         @if($freight->service_category == "sea")   
                                            <option value="{{ $freight->arriaval_city }}" selected> {{ $freight->arriaval_city }} </option>
                                        @endif
                        		    </select>
			                     </div>
			                     <div class="form-group">
			                       <label> Seaport </label>
			                       <select name="arriaval_port2" id="A_port" class="custom-select"> <!--A is used for Arriavl-->
                                        <option value="">Select Port</option>  
                                         @if($freight->service_category == "sea")   
                                            <option value="{{ $freight->arriaval_port }}" selected> {{ $freight->arriaval_port }} </option>
                                        @endif
                        		    </select>
			                     </div>
			               </div>
			            </div>
			            
			            <!--End of sea-->
			            
			           
			            
			             <a class="gonext btn btn-primary text-white float-right">Next</a>
			            <a class="btn btn-secondary text-white goprevious">Previous</a>
			        </div>
			        
			        <!--end of step 2-->
			        
			        <!--step 3-->
			        
			        <div id="freightstep3" style="display:none;">
			            <div class="row">
			                <div class="col-md-12"> <b><p class="lead">Service Visibility</p></b> </div>
			             
			                <div class="col-md-4">
			                    <div class="row">
			                         <label class="col-md-12">Client Type</label> <br>
                                        <div class="form-group col-md-6">
                                              
                                        		<input type="checkbox" name="client_type[]" value="Freelancer" 
                                        		<?php 
                        			                    
                        			                  $result = explode(',',$freight->client_type);
                                                        foreach($result as $row){
                                                            if($row == "Freelancer"){
                                                                echo"checked";
                                                            }
                                                        }			                
			                                 ?>
			                           >Freelancer
                                        </div>
                                        
                                         <div class="form-group col-md-6">
                                        		<input type="checkbox" name="client_type[]" value="Producers" 	
                                        		<?php 
                        			                    
                        			                  $result = explode(',',$freight->client_type);
                                                        foreach($result as $row){
                                                            if($row == "Producers"){
                                                                echo"checked";
                                                            }
                                                        }			                
			                                 ?>
			                                 >Producers
                                        </div>
                                        
                                         <div class="form-group col-md-6">
                                        		<input type="checkbox" name="client_type[]" value="Freight Forwards"
                                        		<?php 
                        			                    
                        			                  $result = explode(',',$freight->client_type);
                                                        foreach($result as $row){
                                                            if($row == "Freight Forwards"){
                                                                echo"checked";
                                                            }
                                                        }			                
			                                 ?>
			                                 >Freight Forwards
                                        </div>
                                        
                                         <div class="form-group col-md-6">
                                        		<input type="checkbox" name="client_type[]" value="Local Client"
                                        		<?php 
                        			                    
                        			                  $result = explode(',',$freight->client_type);
                                                        foreach($result as $row){
                                                            if($row == "Local Client"){
                                                                echo"checked";
                                                            }
                                                        }			                
			                                 ?>>Local Client
                                        </div>
                                        
                                         <div class="form-group col-md-6">
                                        		<input type="checkbox" name="client_type[]" value="Student"
                                        		<?php 
                        			                    
                        			                  $result = explode(',',$freight->client_type);
                                                        foreach($result as $row){
                                                            if($row == "Student"){
                                                                echo"checked";
                                                            }
                                                        }			                
			                                 ?>>Student
                                        </div>
                                        
                                         <div class="form-group col-md-6">
                                        		<input type="checkbox" name="client_type[]" value="Individual" 
                                        		<?php 
                        			                    
                        			                  $result = explode(',',$freight->client_type);
                                                        foreach($result as $row){
                                                            if($row == "Individual"){
                                                                echo"checked";
                                                            }
                                                        }			                
			                                 ?>
                                        		>Individual
                                        </div>
                                </div>
                            </div>
			                <div class="col-md-4">
			                    <div class="form-group">
			                        <label>Location</label> <br>
			                        <input type="checkbox" name="location_type[]" value="Local Customer" 
			                        <?php 
                        			                    
                        			                  $result = explode(',',$freight->location_type);
                                                        foreach($result as $row){
                                                            if($row == "Local Customer"){
                                                                echo"checked";
                                                            }
                                                        }			                
			                                 ?>
			                        >Local Customer
			                    </div>
			                    <div class="form-group">
			                        <input type="checkbox" name="location_type[]" value="Cross Border Customer" 
			                            <?php 
                        			                    
                        			                  $result = explode(',',$freight->location_type);
                                                        foreach($result as $row){
                                                            if($row == "Cross Border Customer"){
                                                                echo"checked";
                                                            }
                                                        }			                
			                                 ?>
			                        >Cross Border Customer
			                    </div>
			                </div>
			                <div class="col-md-4">
			                    <div class="form-group">
			                        <label>Validity Date</label>
			                        <input type="date" name="freightvalidity" class="form-control" id="" placeholder="Enter Validity Date" value="{{ $freight->freightvalidity }}">
			                    </div>
			                    <div class="form-group">
			                        <label>LINER AGENT / COLOADER ( Optional )</label>
			                        <input type="text" name="comment" class="form-control" placeholder="Enter Comment For Rate List" value="{{ $freight->comment }}">
			                    </div>
			                    
			                </div>
			            </div>
			            <hr>
			             <div id="forsealcl" @if($freight->service_type =="FCL") style="display: none;" @endif>
			             <div class="row">
			                <div class="col-md-12"> <b><p class="lead">Service Costs</p></b> </div>
			                </div>
			                		<?php 
                            $price_list = $freight->airport_price;
                            $data = Unserialize($price_list);
                            $count = count($data);
                            for($i=0; $i<$count;$i++){ 
                            if($i == 0){
                            ?>
							
			              <div class="row">
			                
			                <div class="col">
			                    <div class="form-group">
			                        <label>Cost Type</label>
			                        <select name="cost_type_for_sea_lcl[]" class="custom-select">
			                            <option value="OCEAN FREIGHT" @if($data[$i]['cost_type'] == "OCEAN FREIGHT") selected @endif >OCEAN FREIGHT</option>
			                            <option value="O-THC" @if($data[$i]['cost_type'] == "O-THC") selected @endif>O-THC</option>
			                            <option value="D-THC" @if($data[$i]['cost_type'] == "D-THC") selected @endif>D-THC</option>
			                            <option value="BILL OF LADING" @if($data[$i]['cost_type'] == "BILL OF LADING") selected @endif>BILL OF LADING</option>
			                            <option value="DELIEVER ORDER" @if($data[$i]['cost_type'] == "DELIEVER ORDER") selected @endif>DELIEVER ORDER</option>
			                            <option value="E.N.S" @if($data[$i]['cost_type'] == "E.N.S") selected @endif>E.N.S</option>
			                            <option value="LCL SERVICE FEE" @if($data[$i]['cost_type'] == "LCL SERVICE FEE") selected @endif>LCL SERVICE FEE</option>
                                        <option value="LOW SULPHURE SRC" @if($data[$i]['cost_type'] == "LOW SULPHURE SRC") selected @endif>LOW SULPHURE SRC</option>
                                        <option value="IMO 2020" @if($data[$i]['cost_type'] == "IMO 2020") selected @endif>IMO 2020</option>
                                        <option value="SEAL FEE" @if($data[$i]['cost_type'] == "SEAL FEE") selected @endif>SEAL FEE </option>
                                        <option value="ISPS" @if($data[$i]['cost_type'] == "ISPS") selected @endif>ISPS </option>
                                        <option value="FREE IN" @if($data[$i]['cost_type'] == "FREE IN") selected @endif>FREE IN</option>
                                        <option value="FREE OUT" @if($data[$i]['cost_type'] == "FREE OUT") selected @endif>FREE OUT</option>
                                        <option value="LINER IN" @if($data[$i]['cost_type'] == "LINER IN") selected @endif>LINER IN</option>
                                        <option value="LINER OUT" @if($data[$i]['cost_type'] == "LINER OUT") selected @endif>LINER OUT</option>
                                        <option value="DOCUMENTATION FEE" @if($data[$i]['cost_type'] == "DOCUMENTATION FEE") selected @endif>DOCUMENTATION FEE</option>
                                        <option value="FREE ZONE EXTRA FEE" @if($data[$i]['cost_type'] == "FREE ZONE EXTRA FEE") selected @endif>FREE ZONE EXTRA FEE </option>
                                        <option value="FREE ZONE EXTRA FEE " @if($data[$i]['cost_type'] == "FREE ZONE EXTRA FEE") selected @endif>FREE ZONE EXTRA FEE </option>
                                        <option value="BONDED TRUCK FEE" @if($data[$i]['cost_type'] == "BONDED TRUCK FEE") selected @endif>BONDED TRUCK FEE</option>
                                        <option value="WAREHOUSE FEE" @if($data[$i]['cost_type'] == "WAREHOUSE FEE") selected @endif>WAREHOUSE FEE</option>
                                        <option value="STORAGE FEE" @if($data[$i]['cost_type'] == "STORAGE FEE") selected @endif>STORAGE FEE</option>
                                        <option value="HANDLING FEE" @if($data[$i]['cost_type'] == "HANDLING FEE") selected @endif>HANDLING FEE</option>
                                        <option value="STUFFING FEE" @if($data[$i]['cost_type'] == "STUFFING FEE") selected @endif>STUFFING FEE</option>
                                        <option value="CERTIFICATE FEE" @if($data[$i]['cost_type'] == "CERTIFICATE FEE") selected @endif>CERTIFICATE FEE</option>
                                        <option value="SUEZ CANAL SRC" @if($data[$i]['cost_type'] == "SUEZ CANAL SRC") selected @endif>SUEZ CANAL SRC</option>
                                        <option value="B.O.F." @if($data[$i]['cost_type'] == "B.O.F.") selected @endif>B.O.F.</option>
                                        <option value="B.A.F." @if($data[$i]['cost_type'] == "B.A.F.") selected @endif>B.A.F.</option>
                                        <option value="C.A.F." @if($data[$i]['cost_type'] == "C.A.F.") selected @endif>C.A.F.</option>
			                        </select>
			                    </div>
			                </div>
			                <div class="col">
			                    <div class="form-group">
			                        <label>Calculation</label>
			                        <select name="calculaion_for_sea_lcl[]" class="custom-select">
			                            <option value="">Select Calculation Type</option>
			                            <option value="Cubic Meter" @if($data[$i]['calculation'] == "Cubic Meter") selected @endif>Cubic Meter</option>
			                            <option value="SET" @if($data[$i]['calculation'] == "SET") selected @endif>SET</option>
			                        </select>
			                    </div>
			                </div>
			                <div class="col">
    			                    <div class="form-group">
    			                        <label>Currency</label>
    			                        <select name="currency_type_for_sea_lcl[]" class="custom-select">
    			                            <option value="">Select Currency</option>
    			                            <option value="American Dollar" @if($data[$i]['currency_type'] == "American Dollar") selected @endif>American Dollar</option>
    			                            <option value="Euro" @if($data[$i]['currency_type'] == "Euro") selected @endif>Euro</option>
    			                            <option value="Great British Pound" @if($data[$i]['currency_type'] == "Great British Pound") selected @endif>Great British Pound</option>
    			                            <option value="Turish Lira" @if($data[$i]['currency_type'] == "Turish Lira") selected @endif>Turish Lira</option>
    			                            <option value="Australian Dollar" @if($data[$i]['currency_type'] == "Australian Dollar") selected @endif>Australian Dollar</option>
    			                            <option value="Canadian Dollar" @if($data[$i]['currency_type'] == "Canadian Dollar") selected @endif>Canadian Dollar</option>
    			                            
    			                        </select>
    			                    </div>
    			                </div>
    			            <div class="col">
    			                    <div class="form-group">
    			                        <label>Price</label>
    			                        <input type="number" name="price_for_sea_lcl[]" class="form-control" placeholder="Enter Price"  value="{{ $data[$i]['price'] }}">
    			                    </div>
    			                </div>
    			            <div class="col">
                        	    <span class="btn btn-success mt-4" id="AddFieldforlcl" >Add More</span>
                        	</div>
			              </div>
			              	
			           <?php }
			           else{ ?>
			           
			              <div class="row">
			                
			                <div class="col">
			                    <div class="form-group">
			                        <label>Cost Type</label>
			                        <select name="cost_type_for_sea_lcl[]" class="custom-select">
			                             <option value="">Select Cost Type</option>
			                            <option value="OCEAN FREIGHT" @if($data[$i]['cost_type'] == "OCEAN FREIGHT") selected @endif >OCEAN FREIGHT</option>
			                            <option value="O-THC" @if($data[$i]['cost_type'] == "O-THC") selected @endif>O-THC</option>
			                            <option value="D-THC" @if($data[$i]['cost_type'] == "D-THC") selected @endif>D-THC</option>
			                            <option value="BILL OF LADING" @if($data[$i]['cost_type'] == "BILL OF LADING") selected @endif>BILL OF LADING</option>
			                            <option value="DELIEVER ORDER" @if($data[$i]['cost_type'] == "DELIEVER ORDER") selected @endif>DELIEVER ORDER</option>
			                            <option value="E.N.S" @if($data[$i]['cost_type'] == "E.N.S") selected @endif>E.N.S</option>
			                            <option value="LCL SERVICE FEE" @if($data[$i]['cost_type'] == "LCL SERVICE FEE") selected @endif>LCL SERVICE FEE</option>
                                        <option value="LOW SULPHURE SRC" @if($data[$i]['cost_type'] == "LOW SULPHURE SRC") selected @endif>LOW SULPHURE SRC</option>
                                        <option value="IMO 2020" @if($data[$i]['cost_type'] == "IMO 2020") selected @endif>IMO 2020</option>
                                        <option value="SEAL FEE" @if($data[$i]['cost_type'] == "SEAL FEE") selected @endif>SEAL FEE </option>
                                        <option value="ISPS" @if($data[$i]['cost_type'] == "ISPS") selected @endif>ISPS </option>
                                        <option value="FREE IN" @if($data[$i]['cost_type'] == "FREE IN") selected @endif>FREE IN</option>
                                        <option value="FREE OUT" @if($data[$i]['cost_type'] == "FREE OUT") selected @endif>FREE OUT</option>
                                        <option value="LINER IN" @if($data[$i]['cost_type'] == "LINER IN") selected @endif>LINER IN</option>
                                        <option value="LINER OUT" @if($data[$i]['cost_type'] == "LINER OUT") selected @endif>LINER OUT</option>
                                        <option value="DOCUMENTATION FEE" @if($data[$i]['cost_type'] == "DOCUMENTATION FEE") selected @endif>DOCUMENTATION FEE</option>
                                        <option value="FREE ZONE EXTRA FEE" @if($data[$i]['cost_type'] == "FREE ZONE EXTRA FEE") selected @endif>FREE ZONE EXTRA FEE </option>
                                        <option value="FREE ZONE EXTRA FEE " @if($data[$i]['cost_type'] == "FREE ZONE EXTRA FEE") selected @endif>FREE ZONE EXTRA FEE </option>
                                        <option value="BONDED TRUCK FEE" @if($data[$i]['cost_type'] == "BONDED TRUCK FEE") selected @endif>BONDED TRUCK FEE</option>
                                        <option value="WAREHOUSE FEE" @if($data[$i]['cost_type'] == "WAREHOUSE FEE") selected @endif>WAREHOUSE FEE</option>
                                        <option value="STORAGE FEE" @if($data[$i]['cost_type'] == "STORAGE FEE") selected @endif>STORAGE FEE</option>
                                        <option value="HANDLING FEE" @if($data[$i]['cost_type'] == "HANDLING FEE") selected @endif>HANDLING FEE</option>
                                        <option value="STUFFING FEE" @if($data[$i]['cost_type'] == "STUFFING FEE") selected @endif>STUFFING FEE</option>
                                        <option value="CERTIFICATE FEE" @if($data[$i]['cost_type'] == "CERTIFICATE FEE") selected @endif>CERTIFICATE FEE</option>
                                        <option value="SUEZ CANAL SRC" @if($data[$i]['cost_type'] == "SUEZ CANAL SRC") selected @endif>SUEZ CANAL SRC</option>
                                        <option value="B.O.F." @if($data[$i]['cost_type'] == "B.O.F.") selected @endif>B.O.F.</option>
                                        <option value="B.A.F." @if($data[$i]['cost_type'] == "B.A.F.") selected @endif>B.A.F.</option>
                                        <option value="C.A.F." @if($data[$i]['cost_type'] == "C.A.F.") selected @endif>C.A.F.</option>
			                        </select>
			                    </div>
			                </div>
			                <div class="col">
			                    <div class="form-group">
			                        <label>Calculation</label>
			                        <select name="calculaion_for_sea_lcl[]" class="custom-select">
			                            <option value="">Select Calculation Type</option>
			                            <option value="Cubic Meter" @if($data[$i]['calculation'] == "Cubic Meter") selected @endif>Cubic Meter</option>
			                            <option value="SET" @if($data[$i]['calculation'] == "SET") selected @endif>SET</option>
			                        </select>
			                    </div>
			                </div>
			                <div class="col">
    			                    <div class="form-group">
    			                        <label>Currency</label>
    			                        <select name="currency_type_for_sea_lcl[]" class="custom-select">
    			                            <option value="">Select Currency</option>
    			                            <option value="American Dollar" @if($data[$i]['currency_type'] == "American Dollar") selected @endif>American Dollar</option>
    			                            <option value="Euro" @if($data[$i]['currency_type'] == "Euro") selected @endif>Euro</option>
    			                            <option value="Great British Pound" @if($data[$i]['currency_type'] == "Great British Pound") selected @endif>Great British Pound</option>
    			                            <option value="Turish Lira" @if($data[$i]['currency_type'] == "Turish Lira") selected @endif>Turish Lira</option>
    			                            <option value="Australian Dollar" @if($data[$i]['currency_type'] == "Australian Dollar") selected @endif>Australian Dollar</option>
    			                            <option value="Canadian Dollar" @if($data[$i]['currency_type'] == "Canadian Dollar") selected @endif>Canadian Dollar</option>
    			                            
    			                        </select>
    			                    </div>
    			                </div>
    			            <div class="col">
    			                    <div class="form-group">
    			                        <label>Price</label>
    			                        <input type="number" name="price_for_sea_lcl[]" class="form-control" placeholder="Enter Price"  value="{{ $data[$i]['price'] }}">
    			                    </div>
    			                </div>
    			            <div class="col">
                        	    <p class="btn btn-secondary mt-4"  onclick="deleterow(this)" style="cursor:pointer;">Remove</p>
                        	</div>
			              </div>
			              
			           <?php } } ?>

			           </div>
			           
			             <div id="forseafcl" @if($freight->service_type =="LCL") style="display: none;" @endif>
			              <div class="row">
			                <div class="col-md-12"> <b><p class="lead">Service Costs</p></b> </div>
			                </div>
			                   <?php 
                            $price_list = $freight->airport_price;
                            $data = Unserialize($price_list);
                            $count = count($data);
                            for($i=0; $i<$count;$i++){ 
                            if($i == 0){
                            ?>
							
			              <div class="row">
			                
			                <div class="col">
			                    <div class="form-group">
			                        <label>Cost Type</label>
			                        <select name="cost_type_for_sea_fcl[]" class="custom-select">
			                            <option value="">Select Cost Type</option>
			                            <option value="OCEAN FREIGHT" @if($data[$i]['cost_type'] == "OCEAN FREIGHT") selected @endif >OCEAN FREIGHT</option>
			                            <option value="O-THC" @if($data[$i]['cost_type'] == "O-THC") selected @endif>O-THC</option>
			                            <option value="D-THC" @if($data[$i]['cost_type'] == "D-THC") selected @endif>D-THC</option>
			                            <option value="BILL OF LADING" @if($data[$i]['cost_type'] == "BILL OF LADING") selected @endif>BILL OF LADING</option>
			                            <option value="DELIEVER ORDER" @if($data[$i]['cost_type'] == "DELIEVER ORDER") selected @endif>DELIEVER ORDER</option>
			                            <option value="E.N.S" @if($data[$i]['cost_type'] == "E.N.S") selected @endif>E.N.S</option>
			                            <option value="LCL SERVICE FEE" @if($data[$i]['cost_type'] == "LCL SERVICE FEE") selected @endif>LCL SERVICE FEE</option>
                                        <option value="LOW SULPHURE SRC" @if($data[$i]['cost_type'] == "LOW SULPHURE SRC") selected @endif>LOW SULPHURE SRC</option>
                                        <option value="IMO 2020" @if($data[$i]['cost_type'] == "IMO 2020") selected @endif>IMO 2020</option>
                                        <option value="SEAL FEE" @if($data[$i]['cost_type'] == "SEAL FEE") selected @endif>SEAL FEE </option>
                                        <option value="ISPS" @if($data[$i]['cost_type'] == "ISPS") selected @endif>ISPS </option>
                                        <option value="FREE IN" @if($data[$i]['cost_type'] == "FREE IN") selected @endif>FREE IN</option>
                                        <option value="FREE OUT" @if($data[$i]['cost_type'] == "FREE OUT") selected @endif>FREE OUT</option>
                                        <option value="LINER IN" @if($data[$i]['cost_type'] == "LINER IN") selected @endif>LINER IN</option>
                                        <option value="LINER OUT" @if($data[$i]['cost_type'] == "LINER OUT") selected @endif>LINER OUT</option>
                                        <option value="DOCUMENTATION FEE" @if($data[$i]['cost_type'] == "DOCUMENTATION FEE") selected @endif>DOCUMENTATION FEE</option>
                                        <option value="FREE ZONE EXTRA FEE" @if($data[$i]['cost_type'] == "FREE ZONE EXTRA FEE") selected @endif>FREE ZONE EXTRA FEE </option>
                                        <option value="FREE ZONE EXTRA FEE " @if($data[$i]['cost_type'] == "FREE ZONE EXTRA FEE") selected @endif>FREE ZONE EXTRA FEE </option>
                                        <option value="BONDED TRUCK FEE" @if($data[$i]['cost_type'] == "BONDED TRUCK FEE") selected @endif>BONDED TRUCK FEE</option>
                                        <option value="WAREHOUSE FEE" @if($data[$i]['cost_type'] == "WAREHOUSE FEE") selected @endif>WAREHOUSE FEE</option>
                                        <option value="STORAGE FEE" @if($data[$i]['cost_type'] == "STORAGE FEE") selected @endif>STORAGE FEE</option>
                                        <option value="HANDLING FEE" @if($data[$i]['cost_type'] == "HANDLING FEE") selected @endif>HANDLING FEE</option>
                                        <option value="STUFFING FEE" @if($data[$i]['cost_type'] == "STUFFING FEE") selected @endif>STUFFING FEE</option>
                                        <option value="CERTIFICATE FEE" @if($data[$i]['cost_type'] == "CERTIFICATE FEE") selected @endif>CERTIFICATE FEE</option>
                                        <option value="SUEZ CANAL SRC" @if($data[$i]['cost_type'] == "SUEZ CANAL SRC") selected @endif>SUEZ CANAL SRC</option>
                                        <option value="B.O.F." @if($data[$i]['cost_type'] == "B.O.F.") selected @endif>B.O.F.</option>
                                        <option value="B.A.F." @if($data[$i]['cost_type'] == "B.A.F.") selected @endif>B.A.F.</option>
                                        <option value="C.A.F." @if($data[$i]['cost_type'] == "C.A.F.") selected @endif>C.A.F.</option>
			                        </select>
			                    </div>
			                </div>
			                <div class="col">
			                    <div class="form-group">
			                        <label>CHARGE TYPES</label>
			                        <select name="calculaion_for_sea_fcl[]" class="custom-select text-uppercase">
			                            <option value="">SELECT CONTAINER TYPE</option>
			                            <option value="20 DV STANDART CNTR" @if($data[$i]['calculation'] == "20 DV STANDART CNTR") selected @endif>20 DV STANDART CNTR</option>
                                        <option value="40’DV STANDART CNTR" @if($data[$i]['calculation'] == "40’DV STANDART CNTR") selected @endif>40’DV STANDART CNTR</option>
                                        <option value="40’HC CNTR" @if($data[$i]['calculation'] == "40’HC CNTR") selected @endif>40’HC CNTR</option>
                                        <option value="45’HC CNTR" @if($data[$i]['calculation'] == "45’HC CNTR") selected @endif>45’HC CNTR</option>
                                        <option value="45’PW PALLET WIDE CNTR" @if($data[$i]['calculation'] == "45’PW PALLET WIDE CNTR") selected @endif>45’PW PALLET WIDE CNTR</option>
                                        <option value="20’RF REEFER CNTR" @if($data[$i]['calculation'] == "20’RF REEFER CNTR") selected @endif>20’RF REEFER CNTR</option>
                                        <option value="40’RF REEFER CNTR" @if($data[$i]['calculation'] == "40’RF REEFER CNTR") selected @endif>40’RF REEFER CNTR</option>
                                        <option value="20’OT OPEN TOP CNTR" @if($data[$i]['calculation'] == "20’OT OPEN TOP CNTR") selected @endif>20’OT OPEN TOP CNTR</option>
                                        <option value="40’OT OPEN TOP CNTR" @if($data[$i]['calculation'] == "40’OT OPEN TOP CNTR") selected @endif>40’OT OPEN TOP CNTR</option>
                                        <option value="SET" @if($data[$i]['calculation'] == "SET") selected @endif>SET</option>
			                        </select>
			                    </div>
			                </div>
			                <div class="col">
    			                    <div class="form-group">
    			                        <label>Currency</label>
    			                        <select name="currency_type_for_sea_fcl[]" class="custom-select">
    			                            <option value="">Select Currency</option>
    			                            <option value="American Dollar" @if($data[$i]['currency_type'] == "American Dollar") selected @endif>American Dollar</option>
    			                            <option value="Euro" @if($data[$i]['currency_type'] == "Euro") selected @endif>Euro</option>
    			                            <option value="Great British Pound" @if($data[$i]['currency_type'] == "Great British Pound") selected @endif>Great British Pound</option>
    			                            <option value="Turish Lira" @if($data[$i]['currency_type'] == "Turish Lira") selected @endif>Turish Lira</option>
    			                            <option value="Australian Dollar" @if($data[$i]['currency_type'] == "Australian Dollar") selected @endif>Australian Dollar</option>
    			                            <option value="Canadian Dollar" @if($data[$i]['currency_type'] == "Canadian Dollar") selected @endif>Canadian Dollar</option>
    			                        </select>
    			                    </div>
    			                </div>
    			           <div class="col">
    			                    <div class="form-group">
    			                        <label>Price</label>
    			                        <input type="number" name="price_for_sea_fcl[]" class="form-control" placeholder="Enter Price" value="{{ $data[$i]['price'] }}">
    			                    </div>
    			                </div>
    			           <div class="col">
                        	    <span class="btn btn-success mt-4" id="AddFieldforfcl" >Add More</span>
                        	</div>
			             </div>
			             <?php }
			           else{ ?> 

                    <div class="row">
			                
			                <div class="col">
			                    <div class="form-group">
			                        <label>Cost Type</label>
			                        <select name="cost_type_for_sea_fcl[]" class="custom-select">
			                            <option value="">Select Cost Type</option>
			                            <option value="OCEAN FREIGHT" @if($data[$i]['cost_type'] == "OCEAN FREIGHT") selected @endif >OCEAN FREIGHT</option>
			                            <option value="O-THC" @if($data[$i]['cost_type'] == "O-THC") selected @endif>O-THC</option>
			                            <option value="D-THC" @if($data[$i]['cost_type'] == "D-THC") selected @endif>D-THC</option>
			                            <option value="BILL OF LADING" @if($data[$i]['cost_type'] == "BILL OF LADING") selected @endif>BILL OF LADING</option>
			                            <option value="DELIEVER ORDER" @if($data[$i]['cost_type'] == "DELIEVER ORDER") selected @endif>DELIEVER ORDER</option>
			                            <option value="E.N.S" @if($data[$i]['cost_type'] == "E.N.S") selected @endif>E.N.S</option>
			                            <option value="LCL SERVICE FEE" @if($data[$i]['cost_type'] == "LCL SERVICE FEE") selected @endif>LCL SERVICE FEE</option>
                                        <option value="LOW SULPHURE SRC" @if($data[$i]['cost_type'] == "LOW SULPHURE SRC") selected @endif>LOW SULPHURE SRC</option>
                                        <option value="IMO 2020" @if($data[$i]['cost_type'] == "IMO 2020") selected @endif>IMO 2020</option>
                                        <option value="SEAL FEE" @if($data[$i]['cost_type'] == "SEAL FEE") selected @endif>SEAL FEE </option>
                                        <option value="ISPS" @if($data[$i]['cost_type'] == "ISPS") selected @endif>ISPS </option>
                                        <option value="FREE IN" @if($data[$i]['cost_type'] == "FREE IN") selected @endif>FREE IN</option>
                                        <option value="FREE OUT" @if($data[$i]['cost_type'] == "FREE OUT") selected @endif>FREE OUT</option>
                                        <option value="LINER IN" @if($data[$i]['cost_type'] == "LINER IN") selected @endif>LINER IN</option>
                                        <option value="LINER OUT" @if($data[$i]['cost_type'] == "LINER OUT") selected @endif>LINER OUT</option>
                                        <option value="DOCUMENTATION FEE" @if($data[$i]['cost_type'] == "DOCUMENTATION FEE") selected @endif>DOCUMENTATION FEE</option>
                                        <option value="FREE ZONE EXTRA FEE" @if($data[$i]['cost_type'] == "FREE ZONE EXTRA FEE") selected @endif>FREE ZONE EXTRA FEE </option>
                                        <option value="FREE ZONE EXTRA FEE " @if($data[$i]['cost_type'] == "FREE ZONE EXTRA FEE") selected @endif>FREE ZONE EXTRA FEE </option>
                                        <option value="BONDED TRUCK FEE" @if($data[$i]['cost_type'] == "BONDED TRUCK FEE") selected @endif>BONDED TRUCK FEE</option>
                                        <option value="WAREHOUSE FEE" @if($data[$i]['cost_type'] == "WAREHOUSE FEE") selected @endif>WAREHOUSE FEE</option>
                                        <option value="STORAGE FEE" @if($data[$i]['cost_type'] == "STORAGE FEE") selected @endif>STORAGE FEE</option>
                                        <option value="HANDLING FEE" @if($data[$i]['cost_type'] == "HANDLING FEE") selected @endif>HANDLING FEE</option>
                                        <option value="STUFFING FEE" @if($data[$i]['cost_type'] == "STUFFING FEE") selected @endif>STUFFING FEE</option>
                                        <option value="CERTIFICATE FEE" @if($data[$i]['cost_type'] == "CERTIFICATE FEE") selected @endif>CERTIFICATE FEE</option>
                                        <option value="SUEZ CANAL SRC" @if($data[$i]['cost_type'] == "SUEZ CANAL SRC") selected @endif>SUEZ CANAL SRC</option>
                                        <option value="B.O.F." @if($data[$i]['cost_type'] == "B.O.F.") selected @endif>B.O.F.</option>
                                        <option value="B.A.F." @if($data[$i]['cost_type'] == "B.A.F.") selected @endif>B.A.F.</option>
                                        <option value="C.A.F." @if($data[$i]['cost_type'] == "C.A.F.") selected @endif>C.A.F.</option>
			                        </select>
			                    </div>
			                </div>
			                <div class="col">
			                    <div class="form-group">
			                        <label>CHARGE TYPES</label>
			                        <select name="calculaion_for_sea_fcl[]" class="custom-select text-uppercase">
			                            <option value="">SELECT CONTAINER TYPE</option>
			                            <option value="20 DV STANDART CNTR" @if($data[$i]['calculation'] == "20 DV STANDART CNTR") selected @endif>20 DV STANDART CNTR</option>
                                        <option value="40’DV STANDART CNTR" @if($data[$i]['calculation'] == "40’DV STANDART CNTR") selected @endif>40’DV STANDART CNTR</option>
                                        <option value="40’HC CNTR" @if($data[$i]['calculation'] == "40’HC CNTR") selected @endif>40’HC CNTR</option>
                                        <option value="45’HC CNTR" @if($data[$i]['calculation'] == "45’HC CNTR") selected @endif>45’HC CNTR</option>
                                        <option value="45’PW PALLET WIDE CNTR" @if($data[$i]['calculation'] == "45’PW PALLET WIDE CNTR") selected @endif>45’PW PALLET WIDE CNTR</option>
                                        <option value="20’RF REEFER CNTR" @if($data[$i]['calculation'] == "20’RF REEFER CNTR") selected @endif>20’RF REEFER CNTR</option>
                                        <option value="40’RF REEFER CNTR" @if($data[$i]['calculation'] == "40’RF REEFER CNTR") selected @endif>40’RF REEFER CNTR</option>
                                        <option value="20’OT OPEN TOP CNTR" @if($data[$i]['calculation'] == "20’OT OPEN TOP CNTR") selected @endif>20’OT OPEN TOP CNTR</option>
                                        <option value="40’OT OPEN TOP CNTR" @if($data[$i]['calculation'] == "40’OT OPEN TOP CNTR") selected @endif>40’OT OPEN TOP CNTR</option>
                                       <option value="SET" @if($data[$i]['calculation'] == "SET") selected @endif>SET</option>
			                        </select>
			                    </div>
			                </div>
			                <div class="col">
    			                    <div class="form-group">
    			                        <label>Currency</label>
    			                        <select name="currency_type_for_sea_fcl[]" class="custom-select">
    			                            <option value="">Select Currency</option>
    			                            <option value="American Dollar" @if($data[$i]['currency_type'] == "American Dollar") selected @endif>American Dollar</option>
    			                            <option value="Euro" @if($data[$i]['currency_type'] == "Euro") selected @endif>Euro</option>
    			                            <option value="Great British Pound" @if($data[$i]['currency_type'] == "Great British Pound") selected @endif>Great British Pound</option>
    			                            <option value="Turish Lira" @if($data[$i]['currency_type'] == "Turish Lira") selected @endif>Turish Lira</option>
    			                            <option value="Australian Dollar" @if($data[$i]['currency_type'] == "Australian Dollar") selected @endif>Australian Dollar</option>
    			                            <option value="Canadian Dollar" @if($data[$i]['currency_type'] == "Canadian Dollar") selected @endif>Canadian Dollar</option>
    			                        </select>
    			                    </div>
    			                </div>
    			           <div class="col">
    			                    <div class="form-group">
    			                        <label>Price</label>
    			                        <input type="number" name="price_for_sea_fcl[]" class="form-control" placeholder="Enter Price" value="{{ $data[$i]['price'] }}">
    			                    </div>
    			                </div>
    			           <div class="col">
                        	    <p class="btn btn-secondary mt-4"  onclick="deleterow(this)" style="cursor:pointer;">Remove</p>
                        	</div>
			             </div>
			           	
			           <?php } } 
			           	?>
			             </div>
			            <button class="btn btn-success float-right" type="submit">Submit</button>
			           
			            <a class="btn btn-secondary text-white goprevious">Previous</a>
			        </div>
			        
			        <!--end of step 3-->
			        
			        <!--step 4-->
			        <!--<div id="freightstep4" style="display:none;">-->
           <!--             4th-->
			        <!--    <a class= "btn btn-secondary goprevious text-white">Previous</a>-->
			        <!--    <button class="btn btn-success float-right" type="submit">Submit</button>-->
			        <!--    </form>-->
			        <!--</div>-->
			        
			        <!--end of step 4-->
			        
			        
			         </div>
 				</form>
		        </div>
		        
			   @endif
			</div>
		</div>
	</div>
</div>
<!-- testing modal -->
<!-- Button trigger modal -->
<!-- Modal -->
@endsection