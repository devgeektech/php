@extends('layouts.app')
<!-- Jass -->
@section('content')
   <div class="banner-carousel-section">
<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
    <li data-target="#carouselExampleIndicators" data-slide-to="0" class=""></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
    
  </ol>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img class="img-fluid"src="{{ asset('images/banner-1-min.png') }}" class="d-block w-100" alt="...">
	  <div class="carousel-caption  d-md-block">
	  <div class="rgt-btn">
		<h2>MEET WITH SELLER AND BUYER </h2>
		<p>Everything about your global shipping and trading. All in one platform</p>
		
			<a href="">Register Now</a>
		</div >
	  </div>
    </div>
    <div class="carousel-item">
      <img class="img-fluid"src="{{ asset('images/Slider-2-min.jpg') }}" class="d-block w-100" alt="...">
	  	  <div class="carousel-caption  d-md-block">
	  <div class="rgt-btn">
		<h2>Get The Lowest Rates </h2>
		<p>Everything about your global shipping and trading. All in one platform</p>
		
			<a href="">Register Now</a>
		</div >
	  </div>
    </div>
    
    
    <div class="carousel-item">
      <img class="img-fluid"src="{{ asset('images/Slider-3-min.jpg') }}" class="d-block w-100" alt="...">
	  	  <div class="carousel-caption  d-md-block">
	  <div class="rgt-btn">
		<h2>Contact With Logistics Co. </h2>
		<p>Everything about your global shipping and trading. All in one platform</p>
		
			<a href="">Register Now</a>
		</div >
	  </div>
    </div>
    
  <!--  <div class="carousel-item">-->
  <!--    <img class="img-fluid"src="{{ asset('images/Slider-4-V1-min.jpg') }}" class="d-block w-100" alt="...">-->
	 <!-- 	  <div class="carousel-caption  d-md-block">-->
	 <!-- <div class="rgt-btn">-->
		<!--<h2>Global Logistics Network</h2>-->
		<!--<p>Everything about your global shipping and trading. All in one platform</p>-->
		
		<!--	<a href="">Register Now</a>-->
		<!--</div >-->
	 <!-- </div>-->
  <!--  </div>-->
    
        <div class="carousel-item">
      <img class="img-fluid"src="{{ asset('images/Slider-5-min.jpg') }}" class="d-block w-100" alt="...">
	  	  <div class="carousel-caption  d-md-block">
	  <div class="rgt-btn">
		<h2>Find The Best Freights</h2>
		<p>Everything about your global shipping and trading. All in one platform</p>
		
			<a href="">Register Now</a>
		</div >
	  </div>
    </div>
    
            <div class="carousel-item">
      <img class="img-fluid"src="images/Slider-7-min.jpg" class="d-block w-100" alt="...">
	  	  <div class="carousel-caption  d-md-block">
	  <div class="rgt-btn">
		<h2>Create Your Own Network</h2>
		<p>Everything about your global shipping and trading. All in one platform</p>
		
			<a href="">Register Now</a>
		</div >
	  </div>
    </div>
    
    
    
    
  </div>
  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
</div>


<!------------FREIGHT-BASKET-SECTION-START-HERE------------------>

<div class="freight-basket-section">
	<div class="container">
		<div class="row">
				<div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-5 ">
					<div class="main-basket-left">
						<div class="basket-option">
							<h3>Freight <span>Basket</span></h3>
							<div class="line">
							</div>
							<p> Everything about your global shipping and trading. <br>All in one platform. ALL TOGETHER ON BOARD TO <br>MAKE BUSINESS LIFE EASIER.</p>
							<h6>TRANSPORTATION BY</h6>
							<div class="icon-list">
								<ul>
									<li class="adjust-line"><img src="images/icon-1.png"></li>
									<li><img src="images/icon-2.png"></li>
									<li><img src="images/icon-3.png"></li>
									<li class="register-button"><a href="#">Register Now</a></li>
									
								</ul>
							</div>
							<div class="icon-list-2">
								<ul>
									<li class="adjust-line"><h5>SEA</h6></li>
									<li><h5>AIR</h6></li>
									<li><h5>LAND</h6></li>
									<li class="register-button"><a href="#"></a></li>
									
								</ul>
							</div>
							
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-7">
					
				</div>
		
		</div>
	</div>
</div>

<!------------FREIGHT-BASKET-SECTION-END-HERE------------------>
<!-----------Latest News-SECTION-START-HERE------------------>

<div class="freight-basket-latestnews">
	<div class="container">
		<div class="row">
			<div class="col-12 col-sm-12 col-md-12">
				<div class="main-basket-left">
					<div class="basket-option">
						<h3>Latest <span>News</span></h3>
						<div class="line">
						</div>

						<div class="row">
						@if(count($topnewslist) > 0)
					        @foreach($topnewslist as $key => $topnews)

				                <div class="col-lg-4">
					                <div class="card">
					                    <a href="{{ route('page.news',$topnews->slug) }}">
					                        <img src="{{ asset('images/'.$topnews->image) }}" alt="{{ $topnews->title }}" class="card-img-top">
					                    </a>
					                    
					                    <div class="card-body">
					                    <h3>
					                        <a href="{{ route('page.news',$topnews->slug) }}">{{ $topnews->title }}</a>
					                    </h3>
					        
					                    @if($key == 0)
					                        <p>{{ $topnews->details }}</p>
					                    @endif
					        
					                    <ul>
					                        <li><i class="far fa-folder"></i> <a href="{{ route('page.category',$topnews->category->slug) }}">{{ $topnews->category->name }}</a></li>
					                        <li><i class="far fa-clock"></i> {{ $topnews->created_at->diffForHumans() }}</li>
					                    </ul>
					                    </div>
					                </div>
				                </div>
				            @endforeach
			            @else
			            	No News Found
			            @endif
			            </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!------------Latest News-SECTION-END-HERE------------------>

<!------------LAST-FREIGHT-RATES-SECTION-START-HERE------------------>
<div class="last-freight-rates-section">
	<div class="container">
		<div class="row">
			<div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4  last-freight-rates-col"> 
				<!--------col-4---------->
				<div class="last-freight-heading">
			 		<h3>Latest Freight Rates</h3>
				</div>
				
				<div class="last-freight-bottom">
				    <div id="demo" class="carousel slide" data-ride="carousel">

						<!-- Indicators -->
						<ul class="carousel-indicators mb-0 pb-0">
							<li data-target="#demo" data-slide-to="0" class="active"></li>
							<li data-target="#demo" data-slide-to="1"></li>
						</ul>

						<!-- The slideshow -->
						<div class="carousel-inner no-padding">
							@if(!empty($topfreightlist))
								@php
								$topfreight_i = 0;
								@endphp
								@foreach($topfreightlist as $topfreight)
									<div class="carousel-item {{ $loop->first ? 'active' : '' }}">
									  <div class="sea-freight-area">
										<div class="sea-freight-sea">
											@if($topfreight->service_category == "sea")
									  			<img class="" src="images/ship.png">
									  		@elseif($topfreight->service_category == "land")
									  			<img class="" src="images/truck.png">
									  		@else
									  			<img class="" src="images/air.png">
									  		@endif
										</div>
										
										<div class="sea-freight-text">
											<h5>{{$topfreight->service_category}} Freight - {{$topfreight->service_type}}</h5>
										</div>
										
										<div class="sea-freight-country-name">
											<ul>
												@php    		
												$dep_country = App\Country::find(1)->where('name', $topfreight->departure_country)->first();
												@endphp
												<li><img src="images/flags/{{strtolower($dep_country->iso2)}}.png"></li>
												<li><h5>{{$topfreight->departure_country}}</h5></li>
												<li><img src="images/destination.png"></li>
												<li><h5>{{$topfreight->arriaval_country}}</h5></li>
												@php    		
												$arv_country = App\Country::find(1)->where('name', $topfreight->arriaval_country)->first();
												@endphp
												<li><img src="images/flags/{{strtolower($arv_country->iso2)}}.png"></li>
											</ul>
										</div>
										
										<div class="sea-freight-text">
											<ul>
												<li><h5>{{$topfreight->departure_city}} - {{$topfreight->arriaval_city}}</h5></li>
												@php 
						                     	$price_list = $topfreight->airport_price;
					                            $data = Unserialize($price_list);
					                            $count = count($data);
					                            @endphp
					                            @for($i=0; $i<$count;$i++)
					                                <li><span>{{ $data[$i]['calculation'] }} - {{ $data[$i]['price'] }} {{ $data[$i]['currency_type'] }}</span></li>
					                            @endfor
											</ul>
										</div>

									  	<div class="sea-freight-booking-button">
									 		<a href="#">Booking</a>
										</div>

									  </div>
									</div>
								@php
								$topfreight_i++;
								@endphp
								@endforeach
							@endif
						</div>

					  	<!-- Left and right controls -->
					  	<a class="carousel-control-prev" href="#demo" data-slide="prev">
					 		<span class="carousel-control-prev-icon sp"></span>
						</a>
					  	<a class="carousel-control-next" href="#demo" data-slide="next">
							<span class="carousel-control-next-icon sp"></span>
						</a>
					</div>
				</div>
			</div><!--------col-4-end---------->
			
			<div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 last-freight-rates-col"> <!--------col-4---------->
			
			
				<div class="last-freight-heading">
				 <h3>Latest Rate Requests</h3>
				</div>
				
				
				<div class="last-freight-bottom">
				    <div id="demo2" class="carousel slide" data-ride="carousel">

				  <!-- Indicators -->
				  <ul class="carousel-indicators mb-0 pb-0">
					<li data-target="#demo2" data-slide-to="0" class="active"></li>
					<li data-target="#demo2" data-slide-to="1"></li>
					
				  </ul>

				  	<!-- The slideshow -->
						<div class="carousel-inner no-padding">
							@if(!empty($topfreightlist))
								@php
								$topfreight_i = 0;
								@endphp
								@foreach($topfreightlist as $topfreight)
									<div class="carousel-item {{ $loop->first ? 'active' : '' }}">
									  <div class="sea-freight-area">
										<div class="sea-freight-sea">
											@if($topfreight->service_category == "sea")
									  			<img class="" src="images/ship.png">
									  		@elseif($topfreight->service_category == "land")
									  			<img class="" src="images/truck.png">
									  		@else
									  			<img class="" src="images/air.png">
									  		@endif
										</div>
										
										<div class="sea-freight-text">
											<h5>{{$topfreight->service_category}} Freight - {{$topfreight->service_type}}</h5>
										</div>
										
										<div class="sea-freight-country-name">
											<ul>
												@php    		
												$dep_country = App\Country::find(1)->where('name', $topfreight->departure_country)->first();
												@endphp
												<li><img src="images/flags/{{strtolower($dep_country->iso2)}}.png"></li>
												<li><h5>{{$topfreight->departure_country}}</h5></li>
												<li><img src="images/destination.png"></li>
												<li><h5>{{$topfreight->arriaval_country}}</h5></li>
												@php    		
												$arv_country = App\Country::find(1)->where('name', $topfreight->arriaval_country)->first();
												@endphp
												<li><img src="images/flags/{{strtolower($arv_country->iso2)}}.png"></li>
											</ul>
										</div>
										
										<div class="sea-freight-text">
											<ul>
												<li><h5>{{$topfreight->departure_city}} - {{$topfreight->arriaval_city}}</h5></li>
												@php 
						                     	$price_list = $topfreight->airport_price;
					                            $data = Unserialize($price_list);
					                            $count = count($data);
					                            @endphp
					                            @for($i=0; $i<$count;$i++)
					                                <li><span>{{ $data[$i]['calculation'] }} - {{ $data[$i]['price'] }} {{ $data[$i]['currency_type'] }}</span></li>
					                            @endfor
											</ul>
										</div>

									  	<div class="sea-freight-booking-button">
									 		<a href="#">Booking</a>
										</div>

									  </div>
									</div>
								@php
								$topfreight_i++;
								@endphp
								@endforeach
							@endif
						</div>

				  <!-- Left and right controls -->
				  <a class="carousel-control-prev" href="#demo2" data-slide="prev">
					 <span class="carousel-control-prev-icon sp"></span>
								</a>
				  <a class="carousel-control-next" href="#demo2" data-slide="next">
									<span class="carousel-control-next-icon sp"></span>
								</a>
				</div>
									  

				</div>
				
				
			</div><!--------col-4-end---------->
			
			<div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 last-freight-rates-col"> <!--------col-4---------->
			
			
				<div class="last-freight-heading">
				 <h3>Vessel Schedule</h3>
				</div>
				
				
				<div class="last-freight-bottom">
				    <div id="demo3" class="carousel slide" data-ride="carousel">

					  <!-- Indicators -->
					  <ul class="carousel-indicators mb-0 pb-0">
						<li data-target="#demo3" data-slide-to="0" class="active"></li>
						<li data-target="#demo3" data-slide-to="1"></li>
						
					  </ul>

					  <!-- The slideshow -->
						<div class="carousel-inner no-padding">
							@if(!empty($vesselSchedule))
								@php
								$topfreight_i = 0;
								@endphp
								@foreach($vesselSchedule as $vessel)
									<div class="carousel-item {{ $loop->first ? 'active' : '' }}">
									  <div class="sea-freight-area">
										<div class="sea-freight-sea">
											<img class="" src="images/ship.png">
										</div>
										
										<div class="sea-freight-text">
											<h5>Voyage: {{$vessel->voyage_no}}</h5>
										</div>
										
										<div class="sea-freight-country-name">
											<ul>
												@php    		
												$dep_country = App\Country::find(1)->where('name', $vessel->departure_country)->first();
												@endphp
												<li><img src="images/flags/{{strtolower($dep_country->iso2)}}.png"></li>
												<li><h5>{{$vessel->departure_country}}</h5></li>
												<li><img src="images/destination.png"></li>
												<li><h5>{{$vessel->arrival_country}}</h5></li>
												@php    		
												$arv_country = App\Country::find(1)->where('name', $vessel->arrival_country)->first();
												@endphp
												@if(!empty($arv_country))
												<li><img src="images/flags/{{strtolower($arv_country->iso2)}}.png"></li>
												@endif
											</ul>
										</div>
										
										<div class="sea-freight-text">
											<ul>
												<li><h5>{{$vessel->departure_port}} - {{$vessel->arrival_port}}</h5></li>
												<li><span>Terminal - {{$vessel->terminal}}</span></li>
												<li><span>Booking Ref No - {{$vessel->booking_ref_no}}</span></li>
												<li><span>Decl Surrender Off - {{$vessel->decl_surrender_office}}</span></li>
												<li><span>Warehouse Stuffing Att - {{$vessel->warehouse_stuffing_att}}</span></li>
											</ul>
										</div>

									  	<div class="sea-freight-booking-button">
									 		<a href="#">Booking</a>
										</div>

									  </div>
									</div>
								@php
								$topfreight_i++;
								@endphp
								@endforeach
							@endif
						</div>

					  <!-- Left and right controls -->
					  <a class="carousel-control-prev" href="#demo3" data-slide="prev">
						 <span class="carousel-control-prev-icon sp"></span>
									</a>
					  <a class="carousel-control-next" href="#demo3" data-slide="next">
										<span class="carousel-control-next-icon sp"></span>
									</a>
					</div>
				</div>
			</div><!--------col-4-end---------->
		</div>
	</div>
</div>

<!------------LAST-FREIGHT-RATES-SECTION-END-HERE------------------>



<!------------OUR-LOGISTICS-PARTNERS-SECTION-START-HERE------------------>
<div class="our-logistics-partner-section">
    <div class="container">
              <div class="row">
				<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 ">
				  <div class="logistics-heading">
				   <h2>our logistics partners</h2>
				   <div class="logistics-border-line"></div>
				   <p>FreightBasket enable you to reach the widest audience of supply chain professionals involved in international Trade and Logistics industry truly and globally</p>
				  </div>
				</div>
              </div>
			  
			  
			  <div class="row">
			      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
			  <div class="our-logistics-partner-carousel">
	              <div class="test-carousel">
	<div class="container">

<section id= "slider">
  <!-- Start Slider Checked -->
  <input type= "radio" name="slider" id= "slide-1-radio" checked />
  <input type= "radio" name="slider" id= "slide-2-radio" />
  <input type= "radio" name="slider" id= "slide-3-radio" />
  <!-- End Slider Checked -->

  <!-- Start Slides -->
  <div class= "slides">
    <div class= "slide">
      <div class="card mb-2 size-adjust custom ">
              <div class="card-image-bg">
                <img  class="card-img-top img-set img-fluid" src="images/spica-logistics-logo-min.png" alt="Card image cap">
                </div>
                
                <div class="card-body">
                  <h4 class="card-title">SPICIA LOGISTICS</h4>
                  <div class="line-1">
                      </div>
                  <p class="card-text">We provide reliable and result-oriented transportation solutions through agreements we have made with the world's</p>
                    <div class="sub-conutry ">
                        <h4 class="card-title">LATEST UPDATES</h4>
                  <div class="line-1"></div>
                        <h6 class="sub-title">LAND FREIGHT</h6>
                      <div class="sea-freight-country-name cuntry-chnage">
							<ul>
							<li><img src="images/flag.png"></li>
							<li><h5>CHINA</h5></li>
							<li><img class="add-color" src="images/left-right-arrow-2.png"></li>
							<li><h5>Turkey</h5></li>
							<li><img src="images/flag-2.png"></li>
							</ul>
							<h6 class="stu-class">stuttgart - Stuttgart</h6>
							</div>
							<div class="footer-total">
							    <h6>TOTAL</h6>
							    <p>1 Piece - 350 kgs - 1.8 CBM</p>
							</div>
							
							<a class="btn btn-primary btn-view">View More</a>
                    </div>
                </div>
              </div>
    </div>
    <div class= "slide">
      <div class="card mb-2 size-adjust custom">
             <div class="card-image-bg">
                <img class="card-img-top img-set img-fluid" src="images/taksim-logo-min.png" alt="Card image cap">
                </div>
                <div class="card-body">
                  <h4 class="card-title">TEKSIM</h4>
                  <div class="line-1">
                      </div>
                  <p class="card-text">We provide reliable, fast and timely solutions for your need for any kind of sea freight between Turkey.</p>
                  <div class="sub-conutry custom">
                        <h4 class="card-title">LATEST UPDATES</h4>
                  <div class="line-1"></div>
                        <h6 class="sub-title">LAND FREIGHT</h6>
                      <div class="sea-freight-country-name cuntry-chnage">
							<ul>
							<li><img src="images/flag.png"></li>
							<li><h5>CHINA</h5></li>
							<li><img class="add-color" src="images/left-right-arrow-2.png"></li>
							<li><h5>Turkey</h5></li>
							<li><img src="images/flag-2.png"></li>
							</ul>
							<h6 class="stu-class">stuttgart - Stuttgart</h6>
							</div>
							<div class="footer-total">
							    <h6>TOTAL</h6>
							    <p>1 Piece - 350 kgs - 1.8 CBM</p>
							</div>
							
							<a class="btn btn-primary btn-view">View More</a>
                    </div>
                  
                </div>
              </div>
    </div>
    <div class= "slide">
      <div class="card mb-2 size-adjust custom">
           <div class="card-image-bg">
                <img class="card-img-top img-set img-fluid" src="images/ave-logo-min.png" alt="Card image cap">
                </div>
                <div class="card-body">
                  <h4 class="card-title">AVE LOGISTICS</h4>
                  <div class="line-1">
                      </div>
                  <p class="card-text">Our land freight service, which we provide to all routes including the Middle East, the Balkans and Europe.</p>
                    <div class="sub-conutry ">
                        <h4 class="card-title">LATEST UPDATES</h4>
                  <div class="line-1"></div>
                        <h6 class="sub-title">LAND FREIGHT</h6>
                      <div class="sea-freight-country-name cuntry-chnage">
							<ul>
							<li><img src="images/flag.png"></li>
							<li><h5>CHINA</h5></li>
							<li><img class="add-color" src="images/left-right-arrow-2.png"></li>
							<li><h5>Turkey</h5></li>
							<li><img src="images/flag-2.png"></li>
							</ul>
							<h6 class="stu-class">stuttgart - Stuttgart</h6>
							</div>
							<div class="footer-total">
							    <h6>TOTAL</h6>
							    <p>1 Piece - 350 kgs - 1.8 CBM</p>
							</div>
							
							<a class="btn btn-primary btn-view">View More</a>
                    </div>
                </div>
              </div>
    </div>
  </div>
  <!-- End Slides -->

  <!-- Start Slider Control -->

  <!-- Start Prevese Arrow -->
  <div class="prev-arrow arrow">
    <label for= "slide-1-radio" id= "prev-1-arrow">
      <i class= "fa fa-arrow-left"></i>
    
    </label>
    <label for= "slide-2-radio" id= "prev-2-arrow">
      <i class= "fa fa-arrow-left"></i>
      
    </label>
    <label for= "slide-3-radio" id= "prev-3-arrow">
      <i class= "fa fa-arrow-left"></i>
     
    </label>
  </div>
  <!-- Start Prevese Arrow -->

  <!-- Start next Arrow -->
  <div class="next-arrow arrow">
    <label for= "slide-1-radio" id= "next-1-arrow">
      <i class= "fa fa-arrow-right"></i>
     
    </label>
    <label for= "slide-2-radio" id= "next-2-arrow">
      <i class= "fa fa-arrow-right"></i>
      
    </label>
    <label for= "slide-3-radio" id= "next-3-arrow">
      <i class= "fa fa-arrow-right"></i>

    </label>
  </div>
  <!-- Start next Arrow -->
  <!-- End Slider Control -->
</section>
<!-- End Slider -->




</div>







</div>
			  </div>
			  </div>
			  
			  
  </div>
</div>
</div>
<!------------OUR-LOGISTICS-PARTNERS-SECTION-END-HERE------------------>




<!------------OUR-PACKAGES-SECTION-START-HERE------------------>
<div class="our-packages-section">
    <div class="container">
              <div class="row">
				<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
				  <div class="packages-heading">
				   <h2>Our Packages</h2>
				   <div class="packages-border-line"></div>
				   
				  </div>
				</div>
              </div>
			  
			  
			   <div class="row">
			     <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 our-packages-col">
				  <div class="freight-booking-area">
				     <div class="freight-booking-left">
					   <div class="freight-booking-left-inner">
					     <h4>50 FREIGHT & 50 BOOKINGS</h4>
					   </div>
					   <div class="freight-booking-left-inner-bottom">
					     <h4>$50,00 <span>USD</span></h4>
						 <p>per months</p>
						 <div class="land-air">
						  <ul>
						  <li>Land</li>
						  <li>|</li>
						  <li>Air</li>
						  <li>|</li>
						  <li>sea</li>
						  </ul>
						 </div>
						 
						 <div class="land-book-now-button">
						 <a href="#">Book Now</a>
						 </div>
						 
						 
					   </div>
					   
					 </div>
				  </div>
				 </div>
				 <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 our-packages-col">
				  <div class="freight-booking-area">
				     <div class="freight-booking-right">
					   <div class="freight-booking-left-inner">
					     <h4>100 FREIGHT & 100 BOOKINGS</h4>
					   </div>
					   <div class="freight-booking-left-inner-bottom">
					     <h4>$100,00 <span>USD</span></h4>
						 <p>per months</p>
						 <div class="land-air">
						  <ul>
						  <li>Land</li>
						  <li>|</li>
						  <li>Air</li>
						  <li>|</li>
						  <li>sea</li>
						  </ul>
						 </div>
						 
						 <div class="land-book-now-button">
						 <a href="#">Book Now</a>
						 </div>
						 
						 
					   </div>
					   
					 </div>
				  </div>
				 </div>
				 
			   </div>
			  
			  
			  
			  
  </div>
</div>
<!------------OUR-PACKAGES-SECTION-END-HERE------------------>







<!------------ABOUT-FREIGHT-BASKET-SECTION-START-HERE------------------>
<div class="about-freight-basket-section">
    <div class="container">
              <div class="row">
			  <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
			    <div class="eroplane-image-area">
				<iframe width="100%" height="375" src="https://www.youtube.com/embed/Tx7nhkWINvA" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
				</div>
			   
			  </div>
			  
				<div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
				  <div class="about-freight-basket-heading">
				   <h2>About Freight Basket</h2>
				   <div class="about-freight-border-line"></div>
				    <div class="about-freight-paragraph">
					   <p>Exporters, Importers, Traders, Freight Forwarders, Customs Agents, Lashing And Securing Companies, Inland Haulage and Warehousing Companies. FreightBasket is the first platform to combine all the services for sellers and buyers in one platform</p>
					   <p>FreightBasket enable you to reach the widest audience of supply chain professionals involved in international Trade and Logistics industry truly and globally</p>
				   </div>
				       	 <div class="about-freight-basket-button">
						   <a href="#">Book Now</a>
						 </div>
				   
				  </div>
				</div>
              </div>
     </div>
  </div>
<!------------ABOUT-FREIGHT-BASKET-SECTION-END-HERE------------------>



<!------------PLATFORM-FEATURES-SECTION-START-HERE------------------>
<div class="platform-features-section">
    <div class="container">
              <div class="row">
				<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 ">
				  <div class="platform-features-heading">
				   <h2>platform features</h2>
				   <div class="platform-features-border-line"></div>
				   <p>Do Not Mind Where You Are Book Your Cargo And Trace, Pay, CHat With Your Logistics Provider</p>
				  </div>
				</div>
              </div>
			  
		 <div class="row">
			  <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 platform-features-col">
				  <div class="platform-features-area">
				  <div class="platform-features-area-image">
				     <img src="images/bulb.png">
					</div>
				   <h4>WE ARE CREATIVE</h4>
				   <p>Innovation Always!</p>
				   <p class="we-search">We search, listen, meet people, discuss and find the best solutions to up-date our systems.</p>
				  </div>
				</div>
				
				 <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 platform-features-col">
				  <div class="platform-features-area">
				  <div class="platform-features-area-image">
				     <img src="images/magic.png">
					</div>
				   <h4>WE ARE AWESOME</h4>
				   <p>Innovation Always!</p>
				   <p class="we-search">We search, listen, meet people, discuss and find the best solutions to up-date our systems.</p>
				  </div>
				</div>
				
				 <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 platform-features-col">
				  <div class="platform-features-area">
				  <div class="platform-features-area-image">
				     <img src="images/eroplane-icon.png">
					</div>
				   <h4>WE ARE TALENTED</h4>
				   <p>Innovation Always!</p>
				   <p class="we-search">We search, listen, meet people, discuss and find the best solutions to up-date our systems.</p>
				  </div>
				</div>
				
				
              </div>
			  
			  
			   <div class="row">
				<div class="col-md-12 col-sm-12">
				 <div class="platform-features-button">
						   <a href="#">Register Now</a>
						 </div>
				</div>
              </div>
			  
			  
			  
			  
			  
    </div>
     </div>			  
<!------------PLATFORM-FEATURES-SECTION-END-HERE------------------>









@endsection 