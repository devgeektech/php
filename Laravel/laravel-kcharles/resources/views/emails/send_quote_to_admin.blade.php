<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
 <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
 <title>E-mail Template</title>
 <style type="text/css">
body { font-size : .80em; font-family: Arial, Helvetica, Verdana, sans-serif; margin: 0px; padding: 0px; color: #696969; }
.whitetext { color: #000000; }
table.innderTable tr td { border: 1px solid #3e3e3e; padding: 3px; }
ul li { list-style: none; display: inline-block; width: 100%; margin-bottom: 10px; }
ul li p { float: left; margin: 0; width: 80%; margin-left: 10px; }
ul li span { float: left; }
.space { display:inline-block; padding:0 5px;}
</style>

 </head>
 <body>
 <table width="600" border="0" align="center" cellpadding="0" cellspacing="0"  style="border:solid 1px #A46337;" class="bodbg">
   <tr>
	 <td align="center" valign="top" style="height: 64px"><img src="<?php echo env('BASE_URL_AJAX') ?>/images/logoblack.png"  height="110px" width="600px" alt="" /></td>
   </tr>
   <tr>
	 <td align="center" valign="top"  style="padding-bottom: 1px;"><table width="600" border="0" align="center" cellpadding="10" cellspacing="0" bgcolor="#FFFFFF">
		 <tr>
		  <td><table class="innderTable"  cellspacing="0"  width="100%" cellpadding="0" >
			  <tr>
				<td width="25%">Name:</td>
				<td width="75%"><?php echo $custom_name ?></td>
			  </tr>
			  <tr>
				<td>Email:</td>
				<td><?php echo $custom_email ?></td>
			  </tr>
			  <tr>
				<td>Contact Number: </td>
				<td><?php echo $custom_phone ?></td>
			  </tr>
			  <tr>
				<td>From Zip: </td>
				<td><?php echo $from_zip ?></td>
			  </tr>
			  <tr>
				<td>To Zip: </td>
				<td><?php echo $to_zip ?></td>
			  </tr>
			  <tr>
				<td>Notes: </td>
				<td><?php echo $custom_notes ?></td>
			  </tr>
			</table></td>
		</tr>
		 <tr>
		  <td>
			 <p style="font-size:10px;"><i>Powered by </i> K Charles</p></td>
		</tr>
	   </table></td>
   </tr>
 </table>
</body>
</html>