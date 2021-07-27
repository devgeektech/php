<!DOCTYPE html>
<html lang="en">
<head>

    <title>{{ setting('site.title') }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" >
    <link href="https://fonts.googleapis.com/css?family=Lato|Poppins&display=swap" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-primary py-4">
<div class="container">
  <a class="navbar-brand" href="{{ route('gotohome') }}"><img class="img-fluid" src="{{ asset('images/logo.png')}}"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ml-auto nev-clr">
      <li class="nav-item active">
        <a class="nav-link " href="{{ route('gotohome') }}">Home<span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link " href="#">Service</a>
      </li> <li class="nav-item">
        <a class="nav-link " href="#">Resources</a>
      </li> <li class="nav-item">
        <a class="nav-link " href="#">Customer</a>
      </li>
       <li class="nav-item">
        <a class="nav-link " href="#">Company</a>
      </li>
      @if (Auth::guest())
             <li class="nav-item"><a href="{{ url('/login') }}" class="nav-link ">Login</a></li>
             <li class="nav-item"><a href="{{ url('/register') }}" class="nav-link ">Register</a></li>
            @else
             <li class="dropdown nav-item">
              <a class="nav-link " href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                {{ Auth::user()->name }} <span class="caret"></span>
                </a>
          <ul class="dropdown-menu" role="menu">
            <li class="nav-item"><a class="nav-link " href="{{ url('/logout') }}" style="color:#000 !important;"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
            </ul>
        </li>
    @endif

    </ul>
    
  </div>
  </div>
</nav>
    

    @yield('content')


<!------------FOOTER-SECTION-START-HERE------------------>
<div class="footer-section">
    <div class="container">
    
       <div class="row">
          <div class="col-md-12 col-sm-12">
          
             <div class="our-newsletter-area">
               <img class="img-fluid" src="images/mail.png">
             </div>
             
              <div class="our-newsletter-form-area">
                
                    <div class="content">
                        <h2>Subscribe To Our Newsletter</h2>
                    <div class="input-group">
                         <input type="email" class="form-control" placeholder="Enter your email address">
                         <span class="input-group-btn">
                         <button class="btn" type="submit">Subscribe</button>
                         </span>
                          </div>
                    </div>
                
             </div>
             
          </div>
       </div>
    
    <div class="row">
       <div class="col-md-12 col-sm-12">
         <div class="blank-space"></div>
       </div>
    </div>
    
    
        <div class="row">
           <div class="col-md-3 col-sm-12">
                    <div class="footer-section-inner">
                    <a href="#"><img src="images/footer-logo.png"></a>
                        
                        <p>FreightBasket is the first platform to combine all the services and sellers and buyers on one platform</p>
                    </div>
                    
                </div>
                <div class="col-md-3 col-sm-12">
                <div class="footer-section-inner quick-nav-top">
                <h5>Quick Nav</h5>
                    <ul>
                            
                            <li><a href="#">Home</a></li>
                            <li><a href="#">Service</a></li>
                            <li><a href="#">Resources</a></li>
                            <li><a href="#">Customer</a></li>
                            <li><a href="#">Company</a></li>
                        </ul>
                        </div>
                </div>
                <div class="col-md-3 col-sm-12">
                <div class="footer-section-inner">
                <h5>Contact Us</h5>
                    <ul>
                            
                            <li><a href="#">Hasanpasa Kadikoy Istanbul <br>Uludag V.D 3910254428</a></li>
                            <li><a href="mailto:support@freightbasket.com">support@freightbasket.com</a></li>
                            <li><a href="tel:0850 255 0556">0850 255 0556</a></li>
                        </ul>
                        </div>
                </div>
                <div class="col-md-3 col-sm-12">
                
                <div class=" footer-section-inner social-icon">
                <h5>Follow us</h5>
                    <ul>
                            
                        </ul>
                        <div class="social-section">
                        <ul>
                        <li class="adjust-line"><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                        <li><a><i class="fa fa-twitter" aria-hidden="true"></i> </a></li>
                        <li><a><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                        </ul>
                        </div>
                </div>
                </div>
        </div>
    </div>
</div>
<!------------FOOTER-SECTION-END-HERE------------------>



<!------------COPY-RIGHT-SECTION-START-HERE------------------>
<div class="copy-right-section">
    <div class="container">
     <div class="row">
          <div class="col-md-12 col-sm-12">
           <div class="copy-right-section-inner">
             <p>Copyright © 2020 Freight Basket. All rights reserved.</p>
           </div>

           </div>
                </div>
        </div>
    </div>

    <!-- JavaScripts -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" ></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" ></script>
<link rel="stylesheet" href="https://www.jqueryscript.net/demo/country-picker-flags/build/css/countrySelect.css">
<script src="https://www.jqueryscript.net/demo/country-picker-flags/build/js/countrySelect.js"></script>
<script>$("#country_selector").countrySelect();
</script>
</body>
</html>