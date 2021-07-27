<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  
  <style>
body {
    padding: 0;
    margin: 0;
}
td.iage_footer {
    /*background-image: linear-gradient(to right, #932773, #d2425f);*/
	background-color: #000 !important;
}
a.verify-btn {
    background-image: linear-gradient(to right, #932773, #d2425f);
    padding: 10px 20px;
    margin-top: 13px !important;
    display: block;
    width: 18%;
    margin: 0 auto;
    color: #fff;
    text-decoration: none;
}
.footer-link {
    color: #fff;
    text-decoration: none;
}
html { -webkit-text-size-adjust:none; -ms-text-size-adjust: none;}
@media only screen and (max-device-width: 680px), only screen and (max-width: 680px) { 
    *[class="table_width_100"] {
		width: 96% !important;
	}
	*[class="border-right_mob"] {
		border-right: 1px solid #dddddd;
	}
	*[class="mob_100"] {
		width: 100% !important;
	}
	*[class="mob_center"] {
		text-align: center !important;
	}
	*[class="mob_center_bl"] {
		float: none !important;
		display: block !important;
		margin: 0px auto;
	}	
	.iage_footer a {
		text-decoration: none;
		color: #929ca8;
	}
	img.mob_display_none {
		width: 0px !important;
		height: 0px !important;
		display: none !important;
	}
	img.mob_width_50 {
		width: 40% !important;
		height: auto !important;
	}
}
.table_width_100 {
	width: 680px;
}
</style>
</head>
<body>
  
<div id="mailsub" class="notification" align="center">

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="min-width: 320px;"><tr><td align="center" bgcolor="#eff3f8">


<!--[if gte mso 10]>
<table width="680" border="0" cellspacing="0" cellpadding="0">
<tr><td>
<![endif]-->

<table border="0" cellspacing="0" cellpadding="0" class="table_width_100" width="100%" style="max-width: 680px; min-width: 300px;">
    <tr><td>
	<!-- padding --><div style="height: 80px; line-height: 80px; font-size: 10px;"> </div>
	</td></tr>
	<!--header -->
	<tr><td bgcolor="#000">
		<!-- padding --><div style="height: 30px; line-height: 30px; font-size: 10px;"> </div>
		<table width="90%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			    <td align="center" colspan="2"><!-- 

				Item --><div class="mob_center_bl" style="display: block; width: 115px;">
					<table class="mob_center" width="115" border="0" cellspacing="0" cellpadding="0" align="left" style="border-collapse: collapse;">
						<tr><td  valign="middle">
							<!-- padding --><div style="height: 20px; line-height: 20px; font-size: 10px;"> </div>
							<table width="115" border="0" cellspacing="0" cellpadding="0"  >
								<tr>
								    <td align="middle" valign="top" class="mob_center">
									<a href="http://freightbasket.us/" target="_blank" style="color: #596167; font-family: Arial, Helvetica, sans-serif; font-size: 13px;">
									    <font face="Arial, Helvetica, sans-seri; font-size: 13px;" size="3" color="#596167">
									<img src="{{ asset('images/logo.png') }}" width="200" height="50" alt="Freighbasket" border="0" style="display: block;padding-left:1rem" /></font></a>
								</td>
								</tr>
							</table>						
						</td></tr>
					</table></div><!-- Item END--><!--[if gte mso 10]>
				<!-- Item END--></td>
			</tr>
		</table>
		<!-- padding --><div style="height: 50px; line-height: 50px; font-size: 10px;"> </div>
	</td></tr>
	<!--header END-->

	<!--content 1 -->
	<tr>
	    <td align="center" bgcolor="#fbfcfd">
		<table width="90%" border="0" cellspacing="0" cellpadding="0">
			<tr><td align="center">
				<!-- padding -->
				<div style="height: 60px; line-height: 60px; font-size: 10px;"> </div>
				<div style="line-height: 44px;">
					<font face="Arial, Helvetica, sans-serif" size="5" color="#57697e" style="font-size: 34px;">
					<span style="font-family: Arial, Helvetica, sans-serif; font-size: 34px; color: #57697e;">
						Welcome to the site {{$user['name']}}
					</span></font>
				</div>
				<!-- padding --><div style="height: 40px; line-height: 40px; font-size: 10px;"> </div>
			</td></tr>			
			<tr>
			    <td>
				<div style="line-height: 24px; text-align: center;">
					
						<font face="Arial, Helvetica, sans-seri; font-size: 13px;" size="3" color="#596167">
						    Your registered email-id is {{$user['email']}} , Please click on the below link to verify your email account<br/><a class="verify-btn" href="{{url('user/verify', $user->verifyUser->token)}}">Verify Email</a>
						</font>
				
				</div>
			<div style="height: 60px; line-height: 60px; font-size: 10px;"> </div>
			</td>
			</tr>
		</table>		
	</td></tr>
	<!--content 1 END-->
	<!--footer -->
	<tr><td class="iage_footer" align="center" bgcolor="#ffffff">
		<!-- padding --><div style="height: 40px; line-height: 40px; font-size: 10px;"> </div>	
		
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr><td align="center">
				<font face="Arial, Helvetica, sans-serif" size="3" color="#96a5b5" style="font-size: 13px;">
				<span style="font-family: Arial, Helvetica, sans-serif; font-size: 13px; color: #ffffff;">
					2020 © <a class="footer-link" href="http://freightbasket.us/">Freighbasket </a>. ALL Rights Reserved.
				</span></font>				
			</td></tr>			
		</table>
		
		<!-- padding --><div style="height: 30px; line-height: 30px; font-size: 10px;"> </div>	
	</td></tr>
	<!--footer END-->
	<tr><td>
	<!-- padding --><div style="height: 80px; line-height: 80px; font-size: 10px;"> </div>
	</td></tr>
</table>
<!--[if gte mso 10]>
</td></tr>
</table>
<![endif]-->
 
</td></tr>
</table>
			
</div> 

         
</body>
</html>