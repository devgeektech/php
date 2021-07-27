@extends('layouts.usertemplate')
@section('content')
<!-- Top Bar End -->
<!-- Page-Title -->
<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
			</div>
			<h4 class="page-title">Add New Services</h4>
		</div>
		@if (Session::has('success'))
		<p class="alert alert-success">{!! Session::get('success') !!}<span class="close" style="cursor:pointer;" data-dismiss="alert">&times;</span></p>
		@endif
		@if (Session::has('error'))
		<p class="alert alert-danger">{!! Session::get('error') !!}<span class="close" style="cursor:pointer;" data-dismiss="alert">&times;</span></p>
		@endif
	</div>
</div>
@if(Auth::User()->role_id == '4')
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
			   
		        <div class="row">
					<form id="addfreight" action="{{ route('addfreight') }}" method="post" enctype="mutipart/form-data" class="col-md-12">
				    @csrf
		            
			          <div class="col-md-10 offset-md-1 py-5">      
			           <!--Step 1-->
			           
			        <div id="freightstep1">
			           
			           <div class="row">
			               <div class="col-md-12">
			                   <div class="form-group">
			                       <label>Service Category</label>
			                       		@php
								        $data = unserialize($compantdetails->companyservice);
								        @endphp
				                       	<select name="service_category" id="service_category" class="custom-select" required>
				                           	<option value="">Select Category</option>
				                           	@foreach($data["fre-fwrs"] as $row)
					                           	<option value="{{$row}}">{{$row}}</option>
				                           	@endforeach
				                       	</select>
			                       </div>
			                       
			                       <!--sea-->
			                       <div class="form-group" id="category_condition1" style="display:none;" >
			                            <label>Service Type</label>
			                        <select name="service_type" id="service_type"  class="custom-select" required>
			                          <!--  <option value="">Select Service Type</option>-->
			                          <!--<option value="LCL">LCL</option>-->
			                          <!--<option value="FCL">FCL</option>-->
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
			            
			            
			            <!-- For Air -->
			            
			            <div class="row condition1">
			               <div class="col-md-4">
			                   <h4><i class="fa fa-map-marker"></i> Departure</h4>
			                   <div class="form-group">
			                       <label>Country</label>
			                       <select name="departure_country1" id="airport_D_country" class="custom-select" required> <!--D is used for departure-->
			                       <option value="">Select Country</option>
                                        @foreach($airports as $row)
                        				    <option value="{{ $row->countryName }}">{{ $row->countryName }}</option>
                        			    @endforeach
                        		    </select>
			                 </div>
			                 <div class="form-group">
			                       <label> City </label>
			                       <select name="departure_city1" id="airport_D_city" class="custom-select" required> <!--D is used for departure-->
                                        <option value="">Select City</option>  
                        		    </select>
			                     </div>
			                     <div class="form-group">
			                       <label>Airport </label>
			                       <select name="departure_port1" id="airport_D_port" class="custom-select" required> <!--D is used for departure-->
                                        <option value="">Select Port</option>  
                        		    </select>
			                     </div>
			               </div>
			                <div class="col-md-4">
			                   <h4><i class="fa fa-exchange" aria-hidden="true"></i> Transport </h4>
			                   <div class="form-group">
			                       <label>Estimated Time ( Days )</label>
			                         <input type="number" name="estimate_time1" id="estimate_time3" class="form-control" placeholder="Enter Estimated Time" required>
			                   </div>
			                     <div class="form-group">
			                         <label id="landcontent1">Transhipment Country ( Optional )</label>
			                         <input type="text" class="form-control" name="transhipment_country1" >
                        		   
			                     </div>
			                     <div class="form-group">
			                         <label id="landcontent1">Transhipment Port ( Optional )</label>
			                         <input type="text" class="form-control" name="transhipment_port1" >
			                         
			                     </div>
			                  </div>
			                <div class="col-md-4">
			                   <h4><i class="fa fa-map-marker"></i> Arrival</h4>
			                   <div class="form-group">
			                       <label>Country</label>
			                       <select name="arriaval_country1" id="airport_A_country" class="custom-select" required> <!--A is used for Arriavl-->
			                       <option value="">Select Country</option>
                                       @foreach($airports as $row)
                        				    <option value="{{ $row->countryName }}">{{ $row->countryName }}</option>
                        			    @endforeach
                        		    </select>
			                 </div>
			                 <div class="form-group">
			                       <label> City </label>
			                       <select name="arriaval_city1" id="airport_A_city" class="custom-select" required> <!--A is used for Arriavl-->
                                        <option value="">Select City</option>  
                        		    </select>
			                     </div>
			                     <div class="form-group">
			                       <label>Airport </label>
			                       <select name="arriaval_port1" id="airport_A_port" class="custom-select" required> <!--A is used for Arriavl-->
                                        <option value="">Select Port</option>  
                        		    </select>
			                     </div>
			               </div>
			            </div>
			            
			            
			            <!--End of air-->
			            
			            <!--For Sea-->
			            
			            
			            <div class="row condition2" style="display:none;">
			               <div class="col-md-4">
			                   <h4><i class="fa fa-map-marker"></i> Departure</h4>
			                   <div class="form-group">
			                       <label>Country</label>
			                       <select name="departure_country2" id="D_country" class="custom-select" required> <!--D is used for departure-->
			                       <option value="">Select Country</option>
                                        @foreach($countrys as $row)
                        				    <option value="{{ $row->Countries }}">{{ $row->Countries }}</option>
                        			    @endforeach
                        		    </select>
			                 </div>
			                 <div class="form-group">
			                       <label> City </label>
			                       <select name="departure_city2" id="D_city" class="custom-select" required> <!--D is used for departure-->
                                        <option value="">Select City</option>  
                        		    </select>
			                     </div>
			                     <div class="form-group">
			                       <label>Seaport </label>
			                       <select name="departure_port2" id="D_port" class="custom-select" required> <!--D is used for departure-->
                                        <option value="">Select Port</option>  
                        		    </select>
			                     </div>
			               </div>
			               <div class="col-md-4">
			                   <h4><i class="fa fa-exchange" aria-hidden="true"></i> Transport </h4>
			                   <div class="form-group">
			                       <label>Estimated Time ( Days )</label>
			                         <input type="number" name="estimate_time2" id="estimate_time3" class="form-control" placeholder="Enter Estimated Time" required>
			                   </div>
			                     <div class="form-group">
			                         <label>DOMESTIC CUSTOMS ( Optional )</label>
			                         <input type="text" class="form-control" name="transhipment_country2" >
                        		   
			                     </div>
			                     <div class="form-group">
			                         <label>DESTINATION CUSTOMS ( Optional )</label>
			                         <input type="text" class="form-control" name="transhipment_port2" >
			                        
			                     </div>
			                  </div>
			                <div class="col-md-4">
			                   <h4><i class="fa fa-map-marker"></i> Arrival</h4>
			                   <div class="form-group">
			                       <label>Country</label>
			                       <select name="arriaval_country2" id="A_country" class="custom-select" required> <!--A is used for Arriavl-->
			                       <option value="">Select Country</option>
                                       @foreach($countrys as $row)
                        				    <option value="{{ $row->Countries }}">{{ $row->Countries }}</option>
                        			    @endforeach
                        		    </select>
			                 </div>
			                 <div class="form-group">
			                       <label> City </label>
			                       <select name="arriaval_city2" id="A_city" class="custom-select" required> <!--A is used for Arriavl-->
                                        <option value="">Select City</option>  
                        		    </select>
			                     </div>
			                     <div class="form-group">
			                       <label> Seaport </label>
			                       <select name="arriaval_port2" id="A_port" class="custom-select" required> <!--A is used for Arriavl-->
                                        <option value="">Select Port</option>  
                        		    </select>
			                     </div>
			               </div>
			            </div>
			            
			            <!--End of sea-->
			            
			            <!--For Land-->
			            
			            
			            <div class="row condition3" style="display:none;">
			               <div class="col-md-4">
			                   <h4><i class="fa fa-map-marker"></i> Departure</h4>
			                   <div class="form-group">
			                       <label>Country</label>
			                       <select name="departure_country3" id="land_D_country" class="custom-select" required> <!--D is used for departure-->
			                       <option value="">Select Country</option>
                                        @foreach($land as $row)
                        				    <option value="{{ $row->name }}">{{ $row->name }}</option>
                        			    @endforeach
                        		    </select>
			                 </div>
			                 <div class="form-group">
			                       <label> City </label>
			                       <select name="departure_city3" id="land_D_city" class="custom-select" required> <!--D is used for departure-->
                                        <option value="">Select City</option>  
                        		    </select>
			                     </div>
			                     <div class="form-group">
			                       <label> Address </label>
			                       <input type="text" class="form-control" name="departure_port3" required>
			                       <!--<select name="departure_port3" id="" class="custom-select"> <!--D is used for departure-->
                          <!--              <option value="">Select Port</option>  -->
                        		<!--    </select>-->
			                     </div>
			               </div>
			               <div class="col-md-4">
			                   <h4><i class="fa fa-exchange" aria-hidden="true"></i> Transport </h4>
			                   <div class="form-group">
			                       <label>Estimated Time ( Days )</label>
			                         <input type="number" name="estimate_time3" id="estimate_time3" class="form-control" placeholder="Enter Estimated Time" required>
			                   </div>
			                     <div class="form-group">
			                         <label>DOMESTIC CUSTOMS ( Optional )</label>
			                         <input type="text" class="form-control" name="transhipment_country3" >
                        		   
			                     </div>
			                     <div class="form-group">
			                         <label>DESTINATION CUSTOMS( Optional )</label>
			                         <input type="text" class="form-control" name="transhipment_port3" >
			                         
			                     </div>
			                  </div>
			                <div class="col-md-4">
			                   <h4><i class="fa fa-map-marker"></i> Arrival</h4>
			                   <div class="form-group">
			                       <label>Country</label>
			                       <select name="arriaval_country3" id="land_A_country" class="custom-select" required> <!--A is used for departure-->
			                       <option value="">Select Country</option>
                                       @foreach($land as $row)
                        				    <option value="{{ $row->name }}">{{ $row->name }}</option>
                        			    @endforeach
                        		    </select>
			                 </div>
			                 <div class="form-group">
			                       <label> City </label>
			                       <select name="arriaval_city3" id="land_A_city" class="custom-select" required> <!--A is used for departure-->
                                      <option value="">Select City</option>    
                        		    </select>
			                     </div>
			                     <div class="form-group">
			                       <label> Address </label>
			                       <input type="text" class="form-control" name="arriaval_port3">
			                       <!--<select name="arriaval_port3" id="" class="custom-select"> <!--A is used for departure-->
                          <!--              <option value="">Select Port</option>  -->
                        		<!--    </select>-->
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
                                              
                                        		<input type="checkbox" name="client_type[]" value="Freelancer" checked>Freelancer
                                        </div>
                                        
                                         <div class="form-group col-md-6">
                                        		<input type="checkbox" name="client_type[]" value="Producers" checked>Producers
                                        </div>
                                        
                                         <div class="form-group col-md-6">
                                        		<input type="checkbox" name="client_type[]" value="Freight Forwards" checked>Freight Forwards
                                        </div>
                                        
                                         <div class="form-group col-md-6">
                                        		<input type="checkbox" name="client_type[]" value="Local Client" checked>Local Client
                                        </div>
                                        
                                         <div class="form-group col-md-6">
                                        		<input type="checkbox" name="client_type[]" value="Student" checked>Student
                                        </div>
                                        
                                         <div class="form-group col-md-6">
                                        		<input type="checkbox" name="client_type[]" value="Individual" checked>Individual
                                        </div>
                                </div>
                            </div>
			                <div class="col-md-4">
			                    <div class="form-group">
			                        <label>Location</label> <br>
			                        <input type="checkbox" name="location_type[]" value="Local Customer" checked>Local Customer
			                    </div>
			                    <div class="form-group">
			                        <input type="checkbox" name="location_type[]" value="Cross Border Customer" checked>Cross Border Customer
			                    </div>
			                </div>
			                <div class="col-md-4">
			                    <div class="form-group">
			                        <label>Validity Date</label>
			                        <input type="date" name="freightvalidity" class="form-control" id="" placeholder="Enter Validity Date" required>
			                    </div>
			                    <div class="form-group">
			                        <label>LINER AGENT / COLOADER ( Optional )</label>
			                        <input type="text" name="comment" class="form-control" placeholder="Enter Comment For Rate List">
			                    </div>
			                    
			                </div>
			            </div>
			            <hr>
			            
			            <!--for land-->
			            <div id="landpricelist" style="display: none;">
			                <div class="row">
			                <div class="col-md-12"> <b><p class="lead">Service Costs</p></b> </div>
			                
			                <div class="col">
			                    <div class="form-group">
			                        <label>Cost Type</label>
			                        <select name="cost_type_for_land[]" class="custom-select" required>
			                            <option value="">Select Cost Type</option>
			                            <option value="OCEAN FREIGHT">OCEAN FREIGHT</option>
			                            <option value="O-THC">O-THC</option>
			                            <option value="D-THC">D-THC</option>
			                            <option value="BILL OF LADING">BILL OF LADING</option>
			                            <option value="DELIEVER ORDER">DELIEVER ORDER</option>
			                            <option value="E.N.S">E.N.S</option>
			                            <option value="LCL SERVICE FEE">LCL SERVICE FEE</option>
                                        <option value="LOW SULPHURE SRC">LOW SULPHURE SRC</option>
                                        <option value="IMO 2020">IMO 2020</option>
                                        <option value="SEAL FEE">SEAL FEE </option>
                                        <option value="DOCUMENTATION FEE">DOCUMENTATION FEE</option>
                                        <option value="FREE ZONE EXTRA FEE">FREE ZONE EXTRA FEE </option>
                                        <option value="FREE ZONE EXTRA FEE ">FREE ZONE EXTRA FEE </option>
                                        <option value="BONDED TRUCK FEE">BONDED TRUCK FEE</option>
                                        <option value="WAREHOUSE FEE">WAREHOUSE FEE</option>
                                        <option value="STORAGE FEE">STORAGE FEE</option>
                                        <option value="HANDLING FEE">HANDLING FEE</option>
                                        <option value="STUFFING FEE">STUFFING FEE</option>
                                        <option value="CERTIFICATE FEE">CERTIFICATE FEE</option>
                                        <option value="LAND FREIGHT">LAND FREIGHT</option>
                                        <option value="C.M.R.">C.M.R.</option>
			                        </select>
			                    </div>
			                </div>
			                <div class="col" >
			                    <div class="form-group">
			                        <label>Calculation</label>
			                        <select name="calculaion_for_land[]" class="custom-select text-uppercase" required>
			                            <option value="">SELECT TRAILER</option>
			                            <option value="FLAT BED TRAILER">FLAT BED TRAILER</option>
                                        <option value="DRY VAN AND ENCLOSED TRAILERS">DRY VAN AND ENCLOSED TRAILERS</option>
                                        <option value="REFRIGERATED TRAILERS AND REEFERS">REFRIGERATED TRAILERS AND REEFERS</option>
                                        <option value="LOWBOY TRAILER">LOWBOY TRAILER</option>
                                        <option value="STEP DECK TRAILERS – SINGLE DROP TRAILERS">STEP DECK TRAILERS – SINGLE DROP TRAILERS</option>
                                        <option value="EXTENDABLE FLATBED STRETCH TRAILERS">EXTENDABLE FLATBED STRETCH TRAILERS</option>
                                        <option value="STRETCH SINGLE DROP DECK TRAILER">STRETCH SINGLE DROP DECK TRAILER</option>
                                        <option value="STRETCH DOUBLE DROP TRAILERS">STRETCH DOUBLE DROP TRAILERS</option>
                                        <option value="EXTENDABLE DOUBLE DROP TRAILERS">EXTENDABLE DOUBLE DROP TRAILERS</option>
                                        <option value="RGN OR REMOVABLE GOOSENECK TRAILERS">RGN OR REMOVABLE GOOSENECK TRAILERS</option>
                                        <option value="STRETCH RGN OR REMOVABLE GOOSENECK TRAILERS">STRETCH RGN OR REMOVABLE GOOSENECK TRAILERS</option>
                                        <option value="CONESTOGA TRAILERS">CONESTOGA TRAILERS</option>
                                        <option value="SIDE KIT TRAILERS">SIDE KIT TRAILERS</option>
                                        <option value="POWER ONLY">POWER ONLY</option>
                                        <option value="SPECIALIZED TRAILERS">SPECIALIZED TRAILERS</option>
                                        <option value="SEMI TRAILER">SEMI TRAILER</option>
                                        <option value="JUMBO- BOX TRAILER">JUMBO- BOX TRAILER</option>
                                        <option value="MEGA TRAILER">MEGA TRAILER</option>
                                        <option value="REEFER TRAILER">REEFER TRAILER</option>
                                        <option value="CURTAIN TRAILER">CURTAIN TRAILER</option>
                                        <option value="TARPAULIN TRAILER">TARPAULIN TRAILER</option>
                                        <option value="SET">SET</option>
			                        </select>
			                        
			                    </div>
			                </div>
			                <div class="col">
    			                    <div class="form-group">
    			                        <label>Currency</label>
    			                        <select name="currency_type_for_land[]" class="custom-select" required>
    			                            <option value="">Select Currency</option>
    			                            <option value="American Dollar">American Dollar</option>
    			                            <option value="Euro">Euro</option>
    			                            <option value="Great British Pound">Great British Pound</option>
    			                            <option value="Turish Lira">Turish Lira</option>
    			                            <option value="Australian Dollar">Australian Dollar</option>
    			                            <option value="Canadian Dollar">Canadian Dollar</option>
    			                        </select>
    			                    </div>
    			                </div>
    			            <div class="col">
    			                    <div class="form-group">
    			                        <label>Price</label>
    			                        <input type="number" name="price_for_land[]" class="form-control" placeholder="Enter Price">
    			                    </div>
    			                </div>
    			             <div class="col">
                        	    <span class="btn btn-success mt-4" id="AddFieldforland" >Add More</span>
                        	</div>
			             </div>
			             </div>
			             <!--end of land-->
			             
			             <!--for sea lcl-->
			             <div id="forsealcl" style="display: none;">
			              <div class="row">
			                <div class="col-md-12"> <b><p class="lead">Service Costs</p></b> </div>
			                <div class="col">
			                    <div class="form-group">
			                        <label>Cost Type</label>
			                        <select name="cost_type_for_sea_lcl[]" class="custom-select" required>
			                            <option value="">Select Cost Type</option>
			                            <option value="OCEAN FREIGHT">OCEAN FREIGHT</option>
			                            <option value="O-THC">O-THC</option>
			                            <option value="D-THC">D-THC</option>
			                            <option value="BILL OF LADING">BILL OF LADING</option>
			                            <option value="DELIEVER ORDER">DELIEVER ORDER</option>
			                            <option value="E.N.S">E.N.S</option>
			                            <option value="LCL SERVICE FEE">LCL SERVICE FEE</option>
                                        <option value="LOW SULPHURE SRC">LOW SULPHURE SRC</option>
                                        <option value="IMO 2020">IMO 2020</option>
                                        <option value="SEAL FEE">SEAL FEE </option>
                                        <option value="ISPS">ISPS </option>
                                        <option value="FREE IN">FREE IN</option>
                                        <option value="FREE OUT">FREE OUT</option>
                                        <option value="LINER IN">LINER IN</option>
                                        <option value="LINER OUT">LINER OUT</option>
                                        <option value="DOCUMENTATION FEE">DOCUMENTATION FEE</option>
                                        <option value="FREE ZONE EXTRA FEE">FREE ZONE EXTRA FEE </option>
                                        <option value="FREE ZONE EXTRA FEE ">FREE ZONE EXTRA FEE </option>
                                        <option value="BONDED TRUCK FEE">BONDED TRUCK FEE</option>
                                        <option value="WAREHOUSE FEE">WAREHOUSE FEE</option>
                                        <option value="STORAGE FEE">STORAGE FEE</option>
                                        <option value="HANDLING FEE">HANDLING FEE</option>
                                        <option value="STUFFING FEE">STUFFING FEE</option>
                                        <option value="CERTIFICATE FEE">CERTIFICATE FEE</option>
                                        <option value="SUEZ CANAL SRC">SUEZ CANAL SRC</option>
                                        <option value="B.O.F.">B.O.F.</option>
                                        <option value="B.A.F.">B.A.F.</option>
                                        <option value="C.A.F.">C.A.F.</option>
			                        </select>
			                    </div>
			                </div>
			                <div class="col">
			                    <div class="form-group">
			                        <label>Calculation</label>
			                        <select name="calculaion_for_sea_lcl[]" class="custom-select" required>
			                            <option value="">Select Calculation Type</option>
			                            <option value="Cubic Meter">Cubic Meter</option>
			                            <option value="SET">SET</option>
			                        </select>
			                    </div>
			                </div>
			                <div class="col">
    			                    <div class="form-group">
    			                        <label>Currency</label>
    			                        <select name="currency_type_for_sea_lcl[]" class="custom-select" required>
    			                            <option value="">Select Currency</option>
    			                            <option value="American Dollar">American Dollar</option>
    			                            <option value="Euro">Euro</option>
    			                            <option value="Great British Pound">Great British Pound</option>
    			                            <option value="Turish Lira">Turish Lira</option>
    			                            <option value="Australian Dollar">Australian Dollar</option>
    			                            <option value="Canadian Dollar">Canadian Dollar</option>
    			                            
    			                        </select>
    			                    </div>
    			                </div>
    			            <div class="col">
    			                    <div class="form-group">
    			                        <label>Price</label>
    			                        <input type="number" name="price_for_sea_lcl[]" class="form-control" placeholder="Enter Price" required>
    			                    </div>
    			                </div>
    			            <div class="col">
                        	    <span class="btn btn-success mt-4" id="AddFieldforlcl" >Add More</span>
                        	</div>
			              </div>
			              </div>
			              <!--end of sea lcl-->
			              
			              
			              <!--for sea fcl-->
			              <div id="forseafcl" style="display: none;">
			              <div class="row" >
			                   
			                <div class="col-md-12"> <b><p class="lead">Service Costs</p></b> </div>
			                <div class="col">
			                    <div class="form-group">
			                        <label>Cost Type</label>
			                        <select name="cost_type_for_sea_fcl[]" class="custom-select" required>
			                            <option value="">Select Cost Type</option>
			                            <option value="OCEAN FREIGHT">OCEAN FREIGHT</option>
			                            <option value="O-THC">O-THC</option>
			                            <option value="D-THC">D-THC</option>
			                            <option value="BILL OF LADING">BILL OF LADING</option>
			                            <option value="DELIEVER ORDER">DELIEVER ORDER</option>
			                            <option value="E.N.S">E.N.S</option>
			                            <option value="LCL SERVICE FEE">LCL SERVICE FEE</option>
                                        <option value="LOW SULPHURE SRC">LOW SULPHURE SRC</option>
                                        <option value="IMO 2020">IMO 2020</option>
                                        <option value="SEAL FEE">SEAL FEE </option>
                                        <option value="ISPS">ISPS </option>
                                        <option value="FREE IN">FREE IN</option>
                                        <option value="FREE OUT">FREE OUT</option>
                                        <option value="LINER IN">LINER IN</option>
                                        <option value="LINER OUT">LINER OUT</option>
                                        <option value="DOCUMENTATION FEE">DOCUMENTATION FEE</option>
                                        <option value="FREE ZONE EXTRA FEE">FREE ZONE EXTRA FEE </option>
                                        <option value="FREE ZONE EXTRA FEE ">FREE ZONE EXTRA FEE </option>
                                        <option value="BONDED TRUCK FEE">BONDED TRUCK FEE</option>
                                        <option value="WAREHOUSE FEE">WAREHOUSE FEE</option>
                                        <option value="STORAGE FEE">STORAGE FEE</option>
                                        <option value="HANDLING FEE">HANDLING FEE</option>
                                        <option value="STUFFING FEE">STUFFING FEE</option>
                                        <option value="CERTIFICATE FEE">CERTIFICATE FEE</option>
                                        <option value="SUEZ CANAL SRC">SUEZ CANAL SRC</option>
                                        <option value="B.O.F.">B.O.F.</option>
                                        <option value="B.A.F.">B.A.F.</option>
                                        <option value="C.A.F.">C.A.F.</option>
			                        </select>
			                    </div>
			                </div>
			                <div class="col">
			                    <div class="form-group">
			                        <label>CHARGE TYPES</label>
			                        <select name="calculaion_for_sea_fcl[]" class="custom-select text-uppercase" required>
			                            <option value="">SELECT CONTAINER TYPE</option>
			                            <option value="20 DV STANDART CNTR">20 DV STANDART CNTR</option>
                                        <option value="40’DV STANDART CNTR">40’DV STANDART CNTR</option>
                                        <option value="40’HC CNTR">40’HC CNTR</option>
                                        <option value="45’HC CNTR">45’HC CNTR</option>
                                        <option value="45’PW PALLET WIDE CNTR">45’PW PALLET WIDE CNTR</option>
                                        <option value="20’RF REEFER CNTR">20’RF REEFER CNTR</option>
                                        <option value="40’RF REEFER CNTR">40’RF REEFER CNTR</option>
                                        <option value="20’OT OPEN TOP CNTR">20’OT OPEN TOP CNTR</option>
                                        <option value="40’OT OPEN TOP CNTR">40’OT OPEN TOP CNTR</option>
                                        <option value="SET">SET</option>
			                        </select>
			                    </div>
			                </div>
			                <div class="col">
    			                    <div class="form-group">
    			                        <label>Currency</label>
    			                        <select name="currency_type_for_sea_fcl[]" class="custom-select" required>
    			                            <option value="">Select Currency</option>
    			                            <option value="American Dollar">American Dollar</option>
    			                            <option value="Euro">Euro</option>
    			                            <option value="Great British Pound">Great British Pound</option>
    			                            <option value="Turish Lira">Turish Lira</option>
    			                            <option value="Australian Dollar">Australian Dollar</option>
    			                            <option value="Canadian Dollar">Canadian Dollar</option>
    			                        </select>
    			                    </div>
    			                </div>
    			           <div class="col">
    			                    <div class="form-group">
    			                        <label>Price</label>
    			                        <input type="number" name="price_for_sea_fcl[]" class="form-control" placeholder="Enter Price" required>
    			                    </div>
    			                </div>
    			           <div class="col">
                        	    <span class="btn btn-success mt-4" id="AddFieldforfcl" >Add More</span>
                        	</div>
			             </div>
			             </div>
			             
			             <!--end of sea fcl-->
			             
			              <!--air 3rd step-->
			              
                        <div  id="forair" style="display: none;">
                            <div id="air_price_list" >
                            <div class="row">
                         	<div class="col-md-12"> <b><p class="lead">Service Costs</p></b> </div>
                         	 <div class="col">
			                    <div class="form-group">
			                        <label>Cost Type</label>
			                        <select name="air_cost_type[]" class="custom-select" required>
			                            <option value="">Select Cost Type</option>
			                            <option value="AIR FREIGHT">AIR FREIGHT</option>
                                        <option value="AIR WAY BILL">AIR WAY BILL</option>
                                        <option value="DELIVERY ORDER">DELIVERY ORDER</option>
                                        <option value="WEIGHT COST">WEIGHT COST</option>
                                        <option value="CUS">CUS</option>
                                        <option value="CAS">CAS</option>
                                        <option value="I.C.S">I.C.S</option>
                                        <option value="C.H.A">C.H.A</option>
                                        <option value="DEVAINING">DEVAINING</option>
                                        <option value="F.C.S">F.C.S</option>
                                        <option value="S.C.C">S.C.C </option>
                                        <option value="M.O.C">M.O.C</option>
			                        </select>
			                    </div>
			                </div>
                         	<div class="col">
                         		<div class="form-group">
                         			<label for="">Calculation</label>
                         			<select name="air_calculaion[]" id="" class="custom-select" required>
                         				<option value="">Select Calculation Type</option>
                         				<option value="KG">KG</option>
                         				<option value="SET">SET</option>
                         			</select>
                         		</div>
                         	</div>
                         	 <div class="col">
                         		<div class="form-group">
                         			<label for="">Quantity (Above )</label>
                         			<select name="airquantity[]" class="custom-select" required>
                         			    <option value="">Select Quantity</option>
                         			    <option value="45">45</option>
                                        <option value="100">100</option>
                                        <option value="300">300</option>
                                        <option value="500">500</option>
                                        <option value="1000">1000</option>
                                        <option value="SET">SET</option>
                         			</select>
                         			<!--<input type="text" name="airquantity[]" placeholder="Enter Quantity In KG For Example 40 kg" class="form-control" required>-->
                         		</div>
                         	</div>
                         	<div class="col">
                        		<div class="form-group">
                        			 <label>Currency</label>
                        			<select name="aircurrency_type[]" class="custom-select" required>
                        			    <option value="">Select Currency</option>
                        			    <option value="American Dollar">American Dollar</option>
                        			    <option value="Euro">Euro</option>
                        			    <option value="Great British Pound">Great British Pound</option>
                        			    <option value="Turish Lira">Turish Lira</option>
                        			    <option value="Australian Dollar">Australian Dollar</option>
                        			    <option value="Canadian Dollar">Canadian Dollar</option>
                        			</select>
                        	     </div>
                        	 </div>
                        	 <div class="col">
                        		<div class="form-group">
                        			<label>Price</label>
                        			 <input type="number" name="airprice[]" class="form-control" placeholder="Enter Price" required>
                        		    </div>
                        	</div>
                        	<div class="col">
                        	    <span class="btn btn-success mt-4" id="AddField" >Add More</span>
                        		
                        	</div>
                         </div>
                         </div>
                         </div>
                         
                         <!--end of air 3rd step-->
                         
			            <button class="btn btn-success float-right" type="submit">Submit</button>
			           
			            <a class="btn btn-secondary text-white goprevious">Previous</a>
			        </div>
			        
			        <!--end of step 3-->
			        
			         </div>
 				</form>
		        </div>
			   
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