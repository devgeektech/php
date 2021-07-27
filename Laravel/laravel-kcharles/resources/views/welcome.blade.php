<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>K Charles Haulage - World Freight and Logistics</title>

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
<link href="{{ URL::asset("/css/custom.css") }}" rel="stylesheet">
<link href="{{ URL::asset("/css/bootstrap.min.css") }}" rel="stylesheet">
<link href="{{ URL::asset("/css/owl.carousel.min.css") }}" rel="stylesheet">
<link href="{{ URL::asset("/css/jquery.fancybox.min.css") }}" rel="stylesheet">
<link href="{{ URL::asset("/css/font-awesome.min.css") }}" rel="stylesheet">
<script src="{{ URL::asset("/js/jquery-3.4.1.min.js") }}"></script>
<script src="{{ URL::asset("/js/bootstrap.min.js") }}"></script>
<script src="{{ URL::asset("/js/owl.carousel.min.js") }}"></script>
<script src="{{ URL::asset("/js/jquery.fancybox.min.js") }}"></script>
<script src="{{ URL::asset("/js/custom.js") }}"></script>

<!-- Styles -->

</head>

<body>
<div class="flex-center position-ref full-height">
  <div class="content">
    <header class="headerSiderSection">
      <section class="HeaderTopSection">
        <div class="container">
          <div class="row">
            <div class="col-lg-6 col-12">
              <ul class="ContactInfo">
                <li> <a href="mailto:transport@kcharles.co.uk"><span class="Icon"><i class="fa fa-envelope-open" aria-hidden="true"></i></span><span class="Text">transport@kcharles.co.uk</span></a> </li>
                <li> <a href="tel:+447389759874"><span class="Icon"><i class="fa fa-phone" aria-hidden="true"></i></span><span class="Text">+44 738 975 9874</span></a> </li>
              </ul>
              <ul class="HeaderSocialIcons">
                <li><a href="https://www.facebook.com/kcharles.haulage"><i class="fa fa-facebook facebook" aria-hidden="true">&nbsp;</i></a></li>
              </ul>
            </div>
            <div class="col-lg-6 col-12">
              <ul class="SelectLanguageSec">
                <li class="LabelText">Select Language :</li>
                <li><a href="javascript:void(0);"><img src="{{ URL::to('/') }}/images/german.jpg" class="germanflag"></a></li>
                <li><a href="javascript:void(0);"><img src="{{ URL::to('/') }}/images/english.jpg" class="germanflag"></a></li>
                <li><a href="javascript:void(0);"><img src="{{ URL::to('/') }}/images/polish.png" class="germanflag"></a></li>
                <li><a href="javascript:void(0);"><img src="{{ URL::to('/') }}/images/france.jpg" class="germanflag"></a></li>
                <li><a href="javascript:void(0);"><img src="{{ URL::to('/') }}/images/spain.jpg" class="germanflag"></a></li>
              </ul>
            </div>
          </div>
        </div>
      </section>
      <section class="HeaderMenuSection">
        <div class="container">
          <div class="row">
            <div class="col-lg-3">
              <div class="logo"> <a href="javascript:void(0);"> <img src="{{ URL::to('/') }}/images/logowhite.png" alt="K Charles Haulage" class="logo-1"> <img src="{{ URL::to('/') }}/images/logoblack.png" alt="K Charles Haulage" class="logo-2"></a> <a href="javascript:void(0);" id="MobileToggleMenu"><i class="fa fa-bars" aria-hidden="true"></i></a></div>
            </div>
            <div class="col-lg-7">
              <div class="mainmenu">
                <ul id="HeaderMenu" class="mainmenuInner">
                  <li class="menu-item"><a href="#section-about-us">About Us</a></li>
                  <li class="menu-item"><a href="#section-services">Services</a></li>
                  <li class="menu-item"><a href="#section-contact">Contact</a></li>
                </ul>
              </div>
            </div>
            <div class="col-lg-2">
              <!--<div class="search text-right">
                <form role="search" method="get" class="search-form" action="">
                  <input type="search" class="search-field" id="search" placeholder="Search…" value="" name="s" title="Search for:">
                  <input type="submit" class="search-submit" value="Search">
                </form>
              </div>-->
            </div>
          </div>
        </div>
      </section>
      <section>
      <div class="owl-carousel SliderHomeBanner ForDesktopCarousel">
          <div class="item" style="width:100%"><video src="{{ URL::to('/') }}/videos/roadvideo.mp4" width="100%" autoplay loop muted></video></div>
          <div class="item "> <img src='{{ URL::to('/') }}/images/latest1920_2-2.jpg'> </div>
        </div>
        <!--<div class="owl-carousel SliderHomeBanner ForMobCarousel">
          <div class="item" style="width:100%"><video src="{{ URL::to('/') }}/videos/roadvideo.mp4" width="100%" autoplay loop muted></video></div>
          <div class="item "> <img src='{{ URL::to('/') }}/images/demomap3_mob.jpg'> </div>
        </div>-->
      </section>
    </header>

    <section class="AboutUsSec EqualSecPadTop eachsection" id="section-about-us">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <div class="AboutUsText EqualStyleBothSec">
              <h1>ABOUT US</h1>
              <div class="DividerAboutSec"><span></span></div>
              <p>If you looking a logistics company serving United Kingdom and Europe – now you found it! KCharles Haulage it is a haulage company with a total commitment to professionalism, development and customer satisfaction. Providing of high quality transport service is at the forefront of our company, with a qualified team of employees who are involved in providing a reliable, safely and timely services to our customers.</p>
              <p>KCharles Haulage has been in the market provides a full range of transportation services across United Kingdom and Europe. It has undertaken more than 10,000 transports. Our company also specializes in combined transport in cooperate reliable business partners.
                <button class="ShowMoreAboutBtn">Show more</button>
              </p>
              <div id="ShowMoreAboutContent" style="display: none;">
                <p>We offer a full range of transport services including general haulage, refrigerated/chilled, container movements, bulk freight transport and other trucks and semi-trailers with different volumes.</p>
                <p>KCharles ensures the timely transport of all goods, regardless of distance. Our company has a flexible pricing policy and maintains a stable high level of service due to excellent cooperation with international transport companies.</p>
                <button class="ShowLessAboutBtn">Show less</button>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="OurTeamText EqualStyleBothSec">
              <h1>OUR TEAM</h1>
              <div class="DividerAboutSec"><span></span></div>
              <p>We continually seek to build long-term and mutually beneficial relationships with our customers and suppliers based on communication, trust and respect.</p>
              <p>Our highly-skilled, experienced and proven leadership team come from a wide and varied range of industries and disciplines. Together, they provide the perfect combination of expertise and capability to realise our business’ short- and long-term goals.</p>
              <p>We owe our success to a great team; their passion, skills and dedication drive us to deliver the highest quality of service to our customers.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="ServicesSec EqualSecPadTop eachsection" id="section-services">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="EqualSecHeading">
              <h2>OUR SERVICES</h2>
              <div class="EqualSecDivider"><span></span></div>
            </div>
          </div>
        </div>
      </div>
      <div class="container-fluid ServicesSec2" style="background-image: url('{{ URL::to('/') }}/images/kcharles-thuck-img.jpg');">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <div class="ServicesSectionTextDetails">
                <p>No two companies are the same; and neither are their supply chains. Their suppliers are located in different parts of the world. Their customers have different needs. And their commodities have their own special transport requirements.</p>
                <p>K Charles Team has been providing transport solutions since 2009 and the success of our core business values has allowed us to expand our offering into a number of complementary industries available throughout the UK and UE. We specialize in tauatliners and temperature controlled trailers.
                  <button class="ShowMoreServicesBtn">Show more</button>
                </p>
                <div id="ShowMoreServicesContent" style="display: none;">
                  <p>We work with over 100 subcontradors. Our consistently reliable service proves that we take haulage seriously. We are dynamic and focused on providing outstanding services. Backbone of our transportation department is communication. We provide 24/7 tracking with live location of your shipment by GPS.</p>
                  <button class="ShowLessServicesBtn">Show less</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="EqualSecPadTop ForDesktopPartners">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="EqualSecHeading">
              <h2>WHY SHOULD YOU PARTNER WITH US</h2>
              <div class="EqualSecDivider"><span></span></div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-3 col-6">
            <div class="WhyShouldSecmain">
              <div class="TopiconSec"><img class="TruckImg" src="{{ URL::to('/') }}/images/tr1.png"></div>
              <div class="BottomtextSec">
                <h3>OUR SERVICE</h3>
                <p>We’ve been solving logistics problems since 2009.<br>
                  We specialize in tauatliners and temperature controlled trailers.</p>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="WhyShouldSecmain">
              <div class="TopiconSec"><img class="usericon" src="{{ URL::to('/') }}/images/icon.png"></div>
              <div class="BottomtextSec">
                <h3>PROFESSIONAL</h3>
                <p>We carefully scrutinise your particular requirements and use all our experience, skills, systems, diverse capabilities and resources are brought together to provide exactly the right answer at exactly the right price. </p>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="WhyShouldSecmain">
              <div class="TopiconSec"><img class="checkmark" src="{{ URL::to('/') }}/images/reliability-150x150.png"></div>
              <div class="BottomtextSec">
                <h3>RELIABLE</h3>
                <p>We thrive on challenges and we put our heart and soul into everything we do </p>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="WhyShouldSecmain">
              <div class="TopiconSec"><img class="badgeicon" src="{{ URL::to('/') }}/images/delivery-time-150x150.png"></div>
              <div class="BottomtextSec">
                <h3>ON TIME</h3>
                <p>Underlined by our values, enterprising spirit and dedication to providing great customer service, we provide fast and reliable services that exceed customer expectations. </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>


    <section class="EqualSecPadTop ForMobilePartners WhyShouldSecmainMob">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="EqualSecHeading">
              <h2>WHY SHOULD YOU PARTNER WITH US</h2>
              <div class="EqualSecDivider"><span></span></div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-3 col-6">
            <div class="WhyShouldSecmain">
              <div class="TopiconSec"><img class="TruckImg" src="{{ URL::to('/') }}/images/tr1.png"></div>
              <div class="BottomtextSec">
                <h3>OUR SERVICE</h3>
                <p class="WhyShouldDescription1">We’ve been solving logistics problems since 2009.<br>
                  We specialize in <button class="ShowMoreWhyShouldBtn1">Show more</button><span class="ShowMoreWhyShouldContent1" style="display: none;">tauatliners and temperature controlled trailers.<button class="ShowLessWhyShouldBtn1">Show less</button></span></p>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="WhyShouldSecmain">
              <div class="TopiconSec"><img class="usericon" src="{{ URL::to('/') }}/images/icon.png"></div>
              <div class="BottomtextSec">
                <h3>PROFESSIONAL</h3>
                <p class="WhyShouldDescription2">We carefully scrutinise your particular requirements and use all our experience, <button class="ShowMoreWhyShouldBtn2">Show more</button><span class="ShowMoreWhyShouldContent2" style="display: none;"> skills, systems, diverse capabilities and resources are brought together to provide exactly the right answer at exactly the right price.<button class="ShowLessWhyShouldBtn2">Show less</button></span></p>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="WhyShouldSecmain">
              <div class="TopiconSec"><img class="checkmark" src="{{ URL::to('/') }}/images/reliability-150x150.png"></div>
              <div class="BottomtextSec">
                <h3>RELIABLE</h3>
                <p>We thrive on challenges and we put our heart and soul into everything we do </p>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="WhyShouldSecmain">
              <div class="TopiconSec"><img class="badgeicon" src="{{ URL::to('/') }}/images/delivery-time-150x150.png"></div>
              <div class="BottomtextSec">
                <h3>ON TIME</h3>
                <p class="WhyShouldDescription4">Underlined by our values, enterprising spirit and dedication to providing <button class="ShowMoreWhyShouldBtn4">Show more</button><span class="ShowMoreWhyShouldContent4" style="display: none;">great customer service, we provide fast and reliable services that exceed customer expectations.<button class="ShowLessWhyShouldBtn4">Show less</button></span></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>



    <section class="EqualSecPadTop ContactBgBanner eachsection" style="background-image:url('{{ URL::to('/') }}/images/bg-5.jpg');" id="section-contact">
      <div class="container">
        <div class="row">
          <div class="col-lg-4 col-6">
            <div class="EqualcontactInfoSec"> <img class="userimg" src="{{ URL::to('/') }}/images/c-user-profile-blue.png">
              <h2>Peter</h2>
              <ul class="userinfodetails">
                <li><span class="icon"><img class="img-whatsapp" src="{{ URL::to('/') }}/images/c-whatsapcalling.png"></span><span class="text">+44 7803 493241</span></li>
                <li><span class="icon"><img class="img-email" src="{{ URL::to('/') }}/images/c-email.png"></span><span class="text">peter@kcharles.co.uk</span></li>
              </ul>
            </div>
          </div>
          <div class="col-lg-4 col-6">
            <div class="EqualcontactInfoSec"> <img class="userimg" src="{{ URL::to('/') }}/images/c-user-profile-blue.png">
              <h2>ANTHONY</h2>
              <ul class="userinfodetails">
                <li><span class="icon"><img class="img-whatsapp" src="{{ URL::to('/') }}/images/c-whatsapcalling.png"></span><span class="text">+ 44 738 975 9874</span></li>
                <li><span class="icon"><img class="img-email" src="{{ URL::to('/') }}/images/c-email.png"></span><span class="text">anthony@kcharles.co.uk</span></li>
              </ul>
            </div>
          </div>
          <div class="col-lg-4 col-6">
            <div class="EqualcontactInfoSec"> <img class="userimg" src="{{ URL::to('/') }}/images/c-user-profile-blue.png">
              <h2>Peter Z</h2>
              <ul class="userinfodetails">
                <li><span class="icon"><img class="img-whatsapp" src="{{ URL::to('/') }}/images/c-whatsapcalling.png"></span><span class="text">+ 44 738 975 9874</span></li>
                <li><span class="icon"><img class="img-email" src="{{ URL::to('/') }}/images/c-email.png"></span><span class="text">peterz@kcharles.co.uk</span></li>
              </ul>
            </div>
          </div>
          <div class="col-xl-2 col-lg-2 d-none d-xl-block d-lg-block"></div>
          <div class="col-lg-4 col-6">
            <div class="EqualcontactInfoSec"> <img class="userimg" src="{{ URL::to('/') }}/images/c-user-profile-blue.png">
              <h2>CLOE</h2>
              <ul class="userinfodetails">
                <li>
                  <p class="Title">Accounting</p>
                </li>
                <li><span class="icon"><img class="img-email" src="{{ URL::to('/') }}/images/c-email.png"></span><span class="text">info@kcharles.co.uk</span></li>
              </ul>
            </div>
          </div>
          <div class="col-lg-4 col-6">
            <div class="EqualcontactInfoSec">
              <div><img class="userimg" src="{{ URL::to('/') }}/images/c-user-profile-blue.png"></div>
              <h2>EVA</h2>
              <ul class="userinfodetails">
                <li>
                  <p class="Title">Administration</p>
                </li>
                <li><span class="icon"><img class="img-email" src="{{ URL::to('/') }}/images/c-email.png"></span><span class="text">acc@kcharles.co.uk</span></li>
              </ul>
            </div>
          </div>
          <div class="col-xl-2 col-lg-2 d-none d-xl-block d-lg-block"></div>
        </div>
      </div>
    </section>
    <footer class="FooterBgColor FooterSecMain">
      <div class="container">
        <div class="row">
          <div class="col-md-4 col-12">
            <div class="FooterColumnOne">
              <div class="FooterContactInfo">
                <div class="AreaLocation"><span class="lefttext">UNITED KINDOM</span><span class="RightImg"><img class="" src="{{ URL::to('/') }}/images/uk.png" style="width:32px; height:auto;"></span></div>
                <div class="SiteAddress">K Charles Haulage LTD.<br>
                  1000 Great West Rd.<br>
                  Brentford TW8 9HH, UK<br>
                  VAT: GB313177820</div>
                <ul class="FooterMobEmailText">
                  <li><span class="LeftImg"><img class="" src="{{ URL::to('/') }}/images/c-whatsapcalling.png"></span><span class="RightText">+44 738 975 9874</span></li>
                  <li><span class="LeftImg"><img class="" src="{{ URL::to('/') }}/images/c-email.png"></span><span class="RightText">transport@kcharles.co.uk</span></li>
                  <li><span class="LeftImg"><img class="" src="{{ URL::to('/') }}/images/c-email.png"></span><span class="RightText">info@kcharles.co.uk</span></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-4 col-12">
            <div class="FooterColumnTwo">
              <ul class="Footermenu">
                <li><a href="javascript:void(0);"><i class="fa fa-angle-right" aria-hidden="true"></i> About Us</a></li>
                <li><a href="javascript:void(0);"><i class="fa fa-angle-right" aria-hidden="true"></i> Contact</a></li>
                <li><a href="javascript:void(0);"><i class="fa fa-angle-right" aria-hidden="true"></i> Privacy and Cookie Policy</a></li>
              </ul>
            </div>
          </div>
          <div class="col-md-4 col-12">
            <ul class="FooterSocialIcon">
              <li><a href="https://www.facebook.com/kcharles.haulage"><i class="fa fa-facebook" aria-hidden="true">&nbsp;</i></a></li>
            </ul>
            <p class="CopyRightText">Copyright © 2011 K CHARLES HAULAGE LTD.<br>
              All rights reserved.</p>
          </div>
        </div>
      </div>
    </footer>
  </div>
</div>
<div id="GetAQuotePopUp" style="display: none;">
  <div class="row GetAQuotePopUpInner">
    <div class="col-md-5">
      <div class="LeftBannerimage" style="background-image: url('{{ URL::to('/') }}/images/TruckPopUp1.jpg');"></div>
    </div>
    <div class="col-md-7">
      <form class="RightFormText" id="popupform">
        <div class="row titlehide">
          <div class="col-md-12">
            <h2>Get an Instant Quote</h2>
          </div>
        </div>
        <div class="row fromtodiv">
          <div class="col-md-12">
            <div class="EqualFieldStyle">
              <label>From</label>
              <input type="text" placeholder="post code" name="from_zip" value="" id="from_zip">
            </div>
          </div>
          <div class="col-md-12">
            <div class="EqualFieldStyle">
              <label>To</label>
              <input type="text" placeholder="post code" name="to_zip" value="" id="to_zip">
            </div>
          </div>
        </div>
        <div class="row" id="showthisfromto" style="display:none;">
          <div class="col-md-12">
            <div class="EqualFieldStyle">
              <label>Name</label>
              <input type="text" placeholder="Name" name="custom_name" value="" id="custom_name">
            </div>
          </div>
          <div class="col-md-12">
            <div class="EqualFieldStyle">
              <label>Phone</label>
              <input type="number" placeholder="Phone" name="custom_phone" value="" id="custom_phone">
            </div>
          </div>
          <div class="col-md-12">
            <div class="EqualFieldStyle">
              <label>Email</label>
              <input type="mail" placeholder="E-mail" name="custom_email" value="" id="custom_email">
            </div>
          </div>
            <div class="col-md-12">
              <div class="EqualFieldStyle">
                <label>Notes</label>
                <textarea name="custom_notes" placeholder="Notes" id="custom_notes"></textarea>
              </div>
            </div>
          <div class="col-md-12">
            <div class="EqualFieldStyle SubmitBtn">
              <input id="submit_popupform" type="Submit" value="Submit">
              <i class="fa fa-spinner fa-spin fa-3x fa-fw" style="display:none;"></i>
              <!--span class="sr-only">Loading...</span-->
            </div>
          </div>
          <div class="row responsemsgdiv">
            <div class="col-md-12"> <span class="responsemsg"> </span> </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<a href="javascript:void(0);" class="FixedEnvelopeIcon"><i class="fa fa-envelope" aria-hidden="true"></i></a>
<a id="back-to-top" href="#" class="show"><i class="fa fa-angle-up" aria-hidden="true"></i></a>
<script type="text/javascript">
    function ValidateEmail(email) {
        var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        return expr.test(email);
    };

    jQuery(document).ready(function() {
        jQuery('.FixedEnvelopeIcon').on('click', function() {
            jQuery("#GetAQuotePopUp").fancybox().trigger('click');
        });
    });

    jQuery(window).on('load', function(){
		setTimeout(function(){
			jQuery("#GetAQuotePopUp").fancybox().trigger('click');
		}, 3000);



		$('#popupform #from_zip').on('keyup blur',function()
		{
			if( !$(this).val() ) {
				  $('#showthisfromto').css('display','none');
			}else{
				if($('#to_zip').val() != ''){
					$('#showthisfromto').css('display','block');
				}else{
					$('#showthisfromto').css('display','none');
				}
			}
		});
		$('#popupform #to_zip').on('keyup blur',function()
		{
			if( !$(this).val() ) {
				  $('#showthisfromto').css('display','none');
			}else{
				if($('#from_zip').val() != ''){
					$('#showthisfromto').css('display','block');
				}else{
					$('#showthisfromto').css('display','none');
				}
			}
		});

		var ajaxurl = "{{ url('send_quote_email') }}";
		$(document).on('click','#submit_popupform', function(e) {
			// var clickHandler = function(e){
			  $('#submit_popupform').attr('disabled', 'disabled');
			  $('.fa-spinner').css('display','block');
			  $("#submit_popupform").attr("disabled", true);
			  if($('#custom_name').val() == ''){
				  $('#submit_popupform').removeAttr("disabled");
				  $('.fa-spinner').css('display','none');
				  alert('Please enter name');
				  return false;
			  }
			  if($('#custom_email').val() == ''){
				  $('#submit_popupform').removeAttr("disabled");
				  $('.fa-spinner').css('display','none');
				  alert('Please enter email');
				  return false;
			  }else{
				  if (!ValidateEmail($("#custom_email").val())) {
					  $('#submit_popupform').removeAttr("disabled");
					  $('.fa-spinner').css('display','none');
					  alert("Invalid email address.");return false;
				  }
			  }
			  if($('#custom_phone').val() == ''){
				  $('#submit_popupform').removeAttr("disabled");
				  $('.fa-spinner').css('display','none');
				  alert('Please enter contact number');
				  return false;
			  }
			  if($('#custom_notes').val() == ''){
				  $('#submit_popupform').removeAttr("disabled");
				  $('.fa-spinner').css('display','none');
				  alert('Please enter notes');
				  return false;
			  }
			  var datat = {
				action: 'send_popup_email',
				from_zip: $('#from_zip').val(),
				to_zip: $('#to_zip').val(),
				custom_name: $('#custom_name').val(),
				custom_email: $('#custom_email').val(),
				custom_phone: $('#custom_phone').val(),
				custom_notes: $('#custom_notes').val(),
				_token: '{{csrf_token()}}'
			  };
			  $.ajax({
				url: ajaxurl,
				type: 'post',
				data: datat,
				async: true,
				dataType: 'json',
				success: function(json) {
					$('.fa-spinner').css('display','none');
					if(json['success'] == 'no'){
						$('#submit_popupform').removeAttr("disabled");
					}else{
						$('.titlehide').css('display','none');
						$('.fromtodiv').css('display','none');
						$('#showthisfromto .col-md-12').css('display','none');
						$('.responsemsgdiv .col-md-12').css('display','block');

						$('.responsemsgdiv .responsemsg').css('display','block');
						$('.responsemsgdiv .responsemsg').html(json['msg']);
						// setTimeout(function(){
							// $('.fancybox-close-small').trigger('click');
						// },3000);
					}
				}
			  });
		});
    });
</script>

</body>
</html>