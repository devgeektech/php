	if (typeof jQuery == 'undefined') { //console.log('here');
		document.write('<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"><\/script>');        
	} 
	setTimeout(function(){ 
	//var currentScript = $('script').last();
	var currentScript = document.scripts.namedItem('grabscript');
	
	var baseurlSplit = currentScript.src.split('catalog');
	//console.log(baseurlSplit[0]);
	var modulename = '';
	var modulecode = '';
	var moduleview = '';
	
	
		//console.log(value);
	  var urlsplit = currentScript.src.split('?');
	  if(urlsplit[1]) {
		  if(urlsplit[1].indexOf('&') !== -1)
		  {
			  var getParams = urlsplit[1].split('&');
			  $.each(getParams, function( index, paramval ) {
				 var getParamVal = paramval.split('=');
				 if(getParamVal[0] == 'mdName') {
					 modulename = getParamVal[1];
				 } else if(getParamVal[0] == 'mdCode') {
					 modulecode = getParamVal[1];
				 } else if(getParamVal[0] == 'mdViewid') {
					 moduleview = getParamVal[1];
				 }
			  });
			  
		  } else {
			  var getParam = urlsplit[1].split('=');
			  if(getParam[0] == 'mdName') {
				modulename = getParam[1];
			  }
		  }
		//console.log(modulename + ">>" + modulecode + ">>" + moduleview);  
		
		$.ajax({
			url: baseurlSplit[0] + 'index.php?route=ajaxmodule/ajaxmodule',
			type: 'get',
			data: 'module=' + modulename + '&code=' + modulecode + '&view=' + moduleview,
			dataType: 'json',
			beforeSend: function() {
				//$("#itemname_outer_html_wrapper").html('<i class="fa fa-cog fa-spin"></i>');
			},
			complete: function() {
				
			},
			success: function(jsonObj) {
				if (jsonObj) {
					$.each(jsonObj, function(key, result) {
						//console.log(key + ">>" + result['mdata']);
						if(result['mview']) { 
							$('#itemname_outer_html_wrapper_' + result['mview'] ).html(result['mdata']);
						} else {
							$('#itemname_outer_html_wrapper').html(result['mdata']);
						}
					});
					
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
		
	  }
	 }, 2000);
	
