
    	$(document).ready(function(){

        // register multistepform

			$(".next").click(function(){
				var form = $("#myform");
				form.validate({
					errorElement: 'span',
					errorClass: 'help-block',
					highlight: function(element, errorClass, validClass) {
						$(element).closest('.form-group').addClass("has-error");
					},
					unhighlight: function(element, errorClass, validClass) {
						$(element).closest('.form-group').removeClass("has-error");
					},
					rules: {
						companytype: {
							required: true,
						},
						companyservice : {
							srequired: true,
						},
						companyname :{
							required: true,
						},
						countryname :{
							required: true,
						},
						companycity :{
							required: true,
						},
						companytax :{
							required: true,
							number: true,
						},
						companyemail :{
							required: true,
						},
						companyphone :{
							required: true,
							number: true,
						},
						companyaddress :{
							required: true,
						},
						companydocuments :{
							required: true,
						},


						
					},
					messages: {
						companytype: {
							required: "Company Type Is Required",
						},
						companyservice : {
							required: "Service Type Is Required",
						},
						companyname : {
							required: "Company Name Type Is Required",
						},
						countryname : {
							required: "Country Is Required",
						},
						companycity : {
							required: "City Is Required",
						},
						companytax : {
							required: "Tax Is Required",
								number: "Tax Shuold Be Of nNumber Type",
						},
						companyemail : {
							required: "Email  Is Required",
						},
						companyphone : {
							required: "Company Phone Is Required",
							number: "Phone No. Shuold Be Of Number Type",
						},
						companyaddress : {
							required: "Company Address Is Required",
						},
						companydocuments : {
							required: "Company Documents Is Required",
						},

					}
				});
				if (form.valid() === true){
					if ($('#account_information').is(":visible")){
						current_fs = $('#account_information');
						next_fs = $('#company_information');
					}else if($('#company_information').is(":visible")){
						current_fs = $('#company_information');
						next_fs = $('#personal_information');
					}
					
					next_fs.show(); 
					current_fs.hide();
				}
			});

			$('#previous').click(function(){
				if($('#company_information').is(":visible")){
					current_fs = $('#company_information');
					next_fs = $('#account_information');
				}
				if ($('#personal_information').is(":visible")){
					current_fs = $('#personal_information');
					next_fs = $('#company_information');
				}
				next_fs.show(); 
				current_fs.hide();
			});
			
            // end of register multistepform
            
            
            // addfreight multistepform
            	$(".gonext").click(function(){
            	       
            	    
            	    var form2 = $("#addfreight");
            	    console.log(form2.valid());
				form2.validate({
					errorElement: 'span',
					errorClass: 'help-block',
					highlight: function(element, errorClass, validClass) {
						$(element).closest('.form-group').addClass("has-error");
					},
					unhighlight: function(element, errorClass, validClass) {
						$(element).closest('.form-group').removeClass("has-error");
					},
					rules: {
							service_category: {
					required: true,
					},
					service_type: {
					required: true,
					},
					departure_country1: {
					required: true,
					},
					departure_country2: {
					required: true,
					},
					departure_country3: {
					required: true,
					},
					departure_port1: {
					required: true,
					},
					departure_port2: {
					required: true,
					},
					departure_port3: {
					required: true,
					},
					estimate_time1: {
					required: true,
					},
					estimate_time2: {
					required: true,
					},
					estimate_time3: {
					required: true,
					},
					arriaval_country1: {
					required: true,
					},
					arriaval_country2: {
					required: true,
					},
					arriaval_country3: {
					required: true,
					},
					"air_cost_type[]": "required",
					"air_calculaion[]": "required",
					"airquantity[]": "required",
					"aircurrency_type[]": "required",
					"airprice[]": "required",
					"currency_type_for_land[]": "required",
					"price_for_land[]": "required",
					"cost_type_for_land[]": "required",
					"calculaion_for_land[]": "required",
					"currency_type_for_sea_fcl[]": "required",
					"price_for_sea_fcl[]": "required",
					"cost_type_for_sea_fcl[]": "required",
					"calculaion_for_sea_fcl[]": "required",
					"currency_type_for_sea_lcl[]": "required",
					"price_for_sea_lcl[]": "required",
					"cost_type_for_sea_lcl[]": "required",
					"calculaion_for_sea_lcl[]": "required",
					
					arriaval_port1: {
					required: true,
					},
					arriaval_port2: {
					required: true,
					},
					arriaval_port3: {
					required: true,
					},
					client_type: {
					required: true,
					},
					location_type: {
					required: true,
					},
					freightvalidity: {
					required: true,
					},
					cost_type: {
					required: true,
					},
					cost_type1: {
					required: true,
					},
					cost_type2: {
					required: true,
					},
					calculaion1: {
					required: true,
					},
					calculaion2: {
					required: true,
					},
					currency_type: {
					required: true,
					},
					price: {
					required: true,
					},
					    
					},
					messages: {
						
							service_category: {
						required: "This is required",
						},
						service_type: {
						required: "This is required",
						},
						departure_country1: {
						required: "This is required",
						},
						departure_country2: {
						required: "This is required",
						},
						departure_country3: {
						required: "This is required",
						},
						departure_port1: {
						required: "This is required",
						},
						departure_port2: {
						required: "This is required",
						},
						departure_port3: {
						required: "This is required",
						},
						estimate_time1: {
						required: "This is required",
						},
						estimate_time2: {
						required: "This is required",
						},
						estimate_time3: {
						required: "This is required",
						},
						arriaval_country1: {
						required: "This is required",
						},
						arriaval_country2: {
						required: "This is required",
						},
						arriaval_country3: {
						required: "This is required",
						},
						arriaval_port1: {
						required: "This is required",
						},
						arriaval_port2: {
						required: "This is required",
						},
						arriaval_port3: {
						required: "This is required",
						},
						client_type: {
						required: "This is required",
						},
						location_type: {
						required: "This is required",
						},
						freightvalidity: {
						required: "This is required",
						},
						cost_type: {
						required: "This is required",
						},
						cost_type1: {
						required: "This is required",
						},
						cost_type2: {
						required: "This is required",
						},
						calculaion1: {
						required: "This is required",
						},
						calculaion2: {
						required: "This is required",
						},
						currency_type: {
						required: "This is required",
						},
						price: {
						required: "This is required",
						},
						"air_cost_type[]": "This is required",
						"air_calculaion[]": "This is required",
						"airquantity[]": "This is required",
						"aircurrency_type[]": "This is required",
						"airprice[]": "This is required",
						"currency_type_for_land[]": "This is required",
						"price_for_land[]": "This is required",
						"cost_type_for_land[]": "This is required",
						"calculaion_for_land[]": "This is required",
						"currency_type_for_sea_fcl[]": "This is required",
						"price_for_sea_fcl[]": "This is required",
						"cost_type_for_sea_fcl[]": "This is required",
						"calculaion_for_sea_fcl[]": "This is required",
						"currency_type_for_sea_lcl[]": "This is required",
						"price_for_sea_lcl[]": "This is required",
						"cost_type_for_sea_lcl[]": "This is required",
						"calculaion_for_sea_lcl[]": "This is required",		
				
				

					}
				});
			
			if (form2.valid() === true){
					if ($('#freightstep1').is(":visible")){
						current_fs = $('#freightstep1');
						next_fs = $('#freightstep2');
					}
					 if($('#freightstep2').is(":visible")){
						current_fs = $('#freightstep2');
						next_fs = $('#freightstep3');
					}
					if($('#freightstep3').is(":visible")){
						current_fs = $('#freightstep3');
						next_fs = $('#freightstep4');
					}
					
					current_fs.hide();
					next_fs.show(); 
			}
			
			});
			

			$('.goprevious').click(function(){
				if($('#freightstep2').is(":visible")){
					current_fs = $('#freightstep2');
					next_fs = $('#freightstep1');
				}
				if ($('#freightstep3').is(":visible")){
					current_fs = $('#freightstep3');
					next_fs = $('#freightstep2');
				}
			if ($('#freightstep4').is(":visible")){
					current_fs = $('#freightstep4');
					next_fs = $('#freightstep3');
				}
				
				next_fs.show(); 
				current_fs.hide();
			});
			
			
			
			$("#submit_goods_desc").click(function(){
            	       
            	    
            	    var form2 = $("#goods_desc");
				form2.validate({
					errorElement: 'span',
					errorClass: 'help-block',
					highlight: function(element, errorClass, validClass) {
						$(element).closest('.form-group').addClass("has-error");
					},
					unhighlight: function(element, errorClass, validClass) {
						$(element).closest('.form-group').removeClass("has-error");
					},
					rules: {
							goods_name_title: {
		required: true,
		},
		
		commercial_invoice_no: {
		required: true,
		},
		commercial_invoice_date: {
		required: true,
		},
		"air_cost_type[]": "required",
	    "goods_name[]": "required",
		"packing_types[]": "required",
		"packege_quantity[]": "required",
		"gross_weight[]": "required",
		"width[]": "required",
		"length[]": "required",
		"height[]": "required",
		"hs_code[]": "required",
		"container_number[]": "required",
		"type_opf_container[]": "required",
		"seal_no[]": "required",		    
					},
					messages: {
						
							goods_name_title: {
		required: "This is required",
		},
		commercial_invoice_no: {
		required: "This is required",
		},
		commercial_invoice_date: {
		required: "This is required",
		},
		
		"goods_name[]": "This is required",
		"packing_types[]": "This is required",
		"packege_quantity[]": "This is required",
		"gross_weight[]": "This is required",
		"width[]": "This is required",
		"length[]": "This is required",
		"height[]": "This is required",
		"hs_code[]": "This is required",
		"container_number[]": "This is required",
		"type_opf_container[]": "This is required",
		"seal_no[]":"This is required",
				}
				});
			
			if (form2.valid() === true){
					return true;
			}
			else{
			    return false;
			}
			
			});
			
				$("#submit_customer_data").click(function(){
            	       
            	    
            	    var form2 = $("#customer_data");
				form2.validate({
					errorElement: 'span',
					errorClass: 'help-block',
					highlight: function(element, errorClass, validClass) {
						$(element).closest('.form-group').addClass("has-error");
					},
					unhighlight: function(element, errorClass, validClass) {
						$(element).closest('.form-group').removeClass("has-error");
					},
					rules: {
							fullname: {
		required: true,
		},
		
		company_address: {
		required: true,
		},
		city: {
		required: true,
		},
		tel: {
		required: true,
		},
		fax: {
		required: true,
		},
		vat: {
		required: true,
		},
		tax_no: {
		required: true,
		},
		mesis_no: {
		required: true,
		},
		person_incharge: {
		required: true,
		},
		
		
		"name[]": "required",
		"occuption[]": "required",
		"email[]": "required",		    
					},
					messages: {
		
		"name[]": "This is required",
		"occuption[]": "This is required",
		"email[]": "This is required",
		
			fullname: {
			required: "This is required",
		},
		
		company_address: {
			required: "This is required",
		},
		city: {
			required: "This is required",
		},
		tel: {
			required: "This is required",
		},
		fax: {
			required: "This is required",
		},
		vat: {
			required: "This is required",
		},
		tax_no: {
			required: "This is required",
		},
		mesis_no: {
			required: "This is required",
		},
		person_incharge: {
			required: "This is required",
		},
		
				}
				});
			
			if (form2.valid() === true){
					return true;
			}
			else{
			    return false;
			}
			
			});
            
            
            // end of add freight multistep form
 });
