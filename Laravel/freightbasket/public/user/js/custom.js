$(document).ready(function(){
      
    // dashboard
        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
          }
        }); 
        
    	$('#Ctype').change(function(){
			var ctype = $(this).val();
			if(ctype == "4") {
					$('#Cservice2').show();
				}
				else{
					$('#Cservice2').hide();
				}
		});
		
		   function openmodal(data){
        var result = confirm("Are you Sure you Want To delete This Image?");
            if (result) {
                $.ajax({
                    	url: '/deleteimage',
                    	type: 'get',
                    	data: {data: data},
                    	success: function(data){
                    	   if(data=='ok'){
                    	       alert('image deleted successfully');
                    	       window.location.reload();
                    	   }
                    	   else{
                    	       alert('can not delete image Please try again');
                    	   }
                    	}
                    });
                }
       
    }
		
    // end of dashboard
    
    
            // company profile
                 $('#checkemail').blur(function(){
            var data = $(this).val();
            $.ajax({
                	url:'/checkemail',
                	type: 'GET',
                	data: {data: data},
                	success:function(data){
                	   if(data == 'ok'){
                	       $('.error').html('** This Email Already Exists').css('color','red');
                	   }
                	   else{
                	        $('.error').html('');
                	   }
                	}
                });
        });
  
    
  
            // end of company profile
    
    
    // add freight
   
        
          
        //   $('.condition2').hide();
        //   $('.condition3').hide();
           $('.gonext').css('cursor','pointer');
           $('.goprevious').css('cursor','pointer');
           
           
           
           
        $('#service_category').change(function(){
          var data = $(this).val();
          if(data==""){
            $('#category_condition1').hide();
          }
          if(data == "sea"){
              $('#category_condition1').show(); 
              $('#category_condition1 select').html('<option value="">Select Service Type</option><option value="LCL">LCL</option><option value="FCL">FCL</option>');
               $('.condition2').show();
               $('.condition1').hide();
          }
          else{
              // $('#category_condition1').hide();
              // $('#category_condition1 select').html('<option value="">Select Service Type</option>');
               $('.condition2').hide();
          }
          if(data == "land"){
              $('#category_condition1').show();
              $('#category_condition1 select').html('<option value="">Select Service Type</option><option value="FTL" >FTL</option><option value="LTL" >LTL</option>');
              $('.condition1').hide();
              $('.condition3').show();
              $('#landcontent1').text('DOMESTIC CUSTOMS ( Optional )');
              $('#landcontent2').text('DESTINATION CUSTOMS ( Optional )');
              
          }
          else{
              // $('#category_condition1').hide();
              // $('#category_condition1 select').html('<option value="">Select Service Type</option>');
               $('#landcontent1').text('Transhipment Country ( Optional )');
              $('#landcontent2').text('Transhipment Port ( Optional )');
              $('.condition3').hide();
          }
          if(data == "air"){
               $('#category_condition1').hide();
               $('#category_condition1 select').html('');
              $('.condition1').show();
              $('#forfcl').hide();
              $('#forlandsea').hide();
              $('#forair').show();
          }
          else{
              
              $('.condition1').hide();
              $('#forlandsea').show();
              $('#forair').hide();
          }
        });
        
        // ajax functionality
        
        // For seaports
        
        
        $('#D_country').change(function(){
          var data = $(this).val();
          $('#D_port').html('<option value="">Select Port</option>');
          $('#D_city').html('<option value="">Select City</option>');
          
          $.ajax({
              url: path+'/getcity',
              type: 'GET',
              data: {data: data},
              success:function(data){
                  $('#D_city').append(data);
              
              }
          });
        
          $.ajax({
                url: path+'/getport',
                type: 'GET',
                data: {data: data},
                success:function(data){
                   $('#D_port').append(data);
                }
          });
        });
        
        $('#A_country').change(function(){
          var data = $(this).val();
          $('#A_port').html('<option value="">Select Port</option>');
          $('#A_city').html('<option value="">Select City</option>');
          $.ajax({
              url: path+'/getcity',
              type: 'GET',
              data: {data: data},
              success:function(data){
              $('#A_city').append(data);
              }
          });
        
          $.ajax({
                url: path+'/getport',
                type: 'GET',
                data: {data: data},
                success:function(data){
                   $('#A_port').append(data);
                }
          });
        });
        
        // end of seaport
        
        // airport
        
         $('#airport_D_country').change(function(){
            var data = $(this).val();
              $('#airport_D_port').html('<option value="">Select Port</option>');
              $('#airport_D_city').html('<option value="">Select City</option>');                   
          
          $.ajax({
              url: path+'/getcityforairport',
              type: 'GET',
              data: {data: data},
              success:function(data){
                  $('#airport_D_city').append(data);
              
              }
        });
        
        $.ajax({
              url: path+'/getportforairport',
              type: 'GET',
              data: {data: data},
              success:function(data){
                 $('#airport_D_port').append(data);
              }
        });
        });
        
        $('#airport_A_country').change(function(){
            var data = $(this).val();
             $('#airport_A_port').html('<option value="">Select Port</option>');
            $('#airport_A_city').html('<option value="">Select City</option>');
            
          $.ajax({
              url: path+'/getcityforairport',
              type: 'GET',
              data: {data: data},
              success:function(data){
              $('#airport_A_city').append(data);
              }
        });
        
        $.ajax({
              url: path+'/getportforairport',
              type: 'GET',
              data: {data: data},
              success:function(data){
                 $('#airport_A_port').append(data);
              }
        });
        });
        
        // end of Airports
        // for land
        
         $('#land_D_country').change(function(){
            var data = $(this).val();
              $('#land_D_city').html('<option value="">Select City</option>');
          
          $.ajax({
              url: path+'/getcity',
              type: 'GET',
              data: {data: data},
              success:function(data){
                  $('#land_D_city').append(data);
              
              }
        });
        
        });
        
        $('#land_A_country').change(function(){
            var data = $(this).val();
            $('#land_A_city').html('<option value="">Select City</option>');
            $.ajax({
                url: path+'/getcity',
                type: 'GET',
                data: {data: data},
                success:function(data){
                  $('#land_A_city').append(data);
                }
            });        
        });
        
        $('#service_type').change(function(){
           var data = $(this).val();
        //   alert(data);
           if(data=="FCL"){
              
               $('#forseafcl').show();
               $('#forsealcl').hide();
               $('#landpricelist').hide();
               
           }
           
              if(data=="FTL"){
                  $('#landpricelist').show();
                  $('#forsealcl').hide();
                  $('#forseafcl').hide();
               
           }
           if(data=="LTL"){
               $('#landpricelist').show();
              $('#forsealcl').hide();
                  $('#forseafcl').hide();
           }
              
           if(data=="LCL"){
               $('#landpricelist').hide();
               $('#forseafcl').hide();
               $('#forsealcl').show();
           }
        });
        
        // end for land
        // End of ajax
        
    
        
        $('#AddField').click(function(){
            $('#air_price_list').append(' <div class="row" id="new_price"><div class="col"><div class="form-group"><label>Cost Type</label><select name="air_cost_type[]" class="custom-select" required><option value="">Select Cost Type</option><option value="AIR WAY BILL">AIR WAY BILL</option><option value="DELIVERY ORDER">DELIVERY ORDER</option><option value="WEIGHT COST">WEIGHT COST</option><option value="CUS">CUS</option><option value="CAS">CAS</option><option value="I.C.S">I.C.S</option><option value="C.H.A">C.H.A.</option><option value="DEVAINING">DEVAINING</option><option value="F.C.S">F.C.S.</option><option value="S.C.C">S.C.C </option><option value="M.O.C">M.O.C</option></select></div></div><div class="col"><div class="form-group"><label for="">Calculation</label><select name="air_calculaion[]" id="" class="custom-select" required><option value="">Select Calculation Type</option><option value="KG">KG</option><option value="SET">SET</option></select></div></div><div class="col pl-0"><div class="form-group"><label for="">Quantity( Above )</label><select name="airquantity[]" class="custom-select" required><option value="">Select Quantity</option><option value="45">45</option><option value="100">100</option><option value="300">300</option><option value="500">500</option><option value="1000">1000</option><option value="SET">SET</option></select></div></div><div class="col"><div class="form-group"><label>Currency</label><select name="aircurrency_type[]" class="custom-select" required><option value="">Select Currency</option><option value="American Dollar">American Dollar</option><option value="Euro">Euro</option><option value="Great British Pound">Great British Pound</option><option value="Turish Lira">Turish Lira</option><option value="Australian Dollar">Australian Dollar</option><option value="Canadian Dollar">Canadian Dollar</option></select></div></div><div class="col"><div class="form-group"><label>Price</label><input type="number" name="airprice[]" class="form-control" required="" placeholder="Enter Price" ></div></div><div class="col"><p class="btn btn-secondary mt-4"  onclick="deleterow(this)" style="cursor:pointer;">Remove</p></div></div>');
        });
        
         $('#AddFieldforland').click(function(){
              $('#landpricelist').append('<div class="row"><div class="col"><div class="form-group"><label>Cost Type</label><select name="cost_type_for_land[]" class="custom-select"><option value="">Select Cost Type</option><option value="OCEAN FREIGHT">OCEAN FREIGHT</option><option value="O-THC">O-THC</option><option value="D-THC">D-THC</option><option value="BILL OF LADING">BILL OF LADING</option><option value="DELIEVER ORDER">DELIEVER ORDER</option><option value="E.N.S">E.N.S</option><option value="LCL SERVICE FEE">LCL SERVICE FEE</option><option value="LOW SULPHURE SRC">LOW SULPHURE SRC</option><option value="IMO 2020">IMO 2020</option><option value="SEAL FEE">SEAL FEE </option><option value="DOCUMENTATION FEE">DOCUMENTATION FEE</option><option value="FREE ZONE EXTRA FEE">FREE ZONE EXTRA FEE </option><option value="FREE ZONE EXTRA FEE ">FREE ZONE EXTRA FEE </option><option value="BONDED TRUCK FEE">BONDED TRUCK FEE</option><option value="WAREHOUSE FEE">WAREHOUSE FEE</option><option value="STORAGE FEE">STORAGE FEE</option><option value="HANDLING FEE">HANDLING FEE</option><option value="STUFFING FEE">STUFFING FEE</option><option value="CERTIFICATE FEE">CERTIFICATE FEE</option><option value="LAND FREIGHT">LAND FREIGHT</option><option value="C.M.R.">C.M.R.</option></select></div></div><div class="col" ><div class="form-group"><label>Calculation</label><select name="calculaion_for_land[]" class="custom-select text-uppercase"><option value="">SELECT TRAILER</option><option value="FLAT BED TRAILER">FLAT BED TRAILER</option><option value="SET">SET</option><option value="DRY VAN AND ENCLOSED TRAILERS">DRY VAN AND ENCLOSED TRAILERS</option><option value="REFRIGERATED TRAILERS AND REEFERS">REFRIGERATED TRAILERS AND REEFERS</option><option value="LOWBOY TRAILER">LOWBOY TRAILER</option><option value="STEP DECK TRAILERS – SINGLE DROP TRAILERS">STEP DECK TRAILERS – SINGLE DROP TRAILERS</option><option value="EXTENDABLE FLATBED STRETCH TRAILERS">EXTENDABLE FLATBED STRETCH TRAILERS</option><option value="STRETCH SINGLE DROP DECK TRAILER">STRETCH SINGLE DROP DECK TRAILER</option><option value="STRETCH DOUBLE DROP TRAILERS">STRETCH DOUBLE DROP TRAILERS</option><option value="EXTENDABLE DOUBLE DROP TRAILERS">EXTENDABLE DOUBLE DROP TRAILERS</option><option value="RGN OR REMOVABLE GOOSENECK TRAILERS">RGN OR REMOVABLE GOOSENECK TRAILERS</option><option value="STRETCH RGN OR REMOVABLE GOOSENECK TRAILERS">STRETCH RGN OR REMOVABLE GOOSENECK TRAILERS</option><option value="CONESTOGA TRAILERS">CONESTOGA TRAILERS</option><option value="SIDE KIT TRAILERS">SIDE KIT TRAILERS</option><option value="POWER ONLY">POWER ONLY</option><option value="SPECIALIZED TRAILERS">SPECIALIZED TRAILERS</option><option value="SEMI TRAILER">SEMI TRAILER</option><option value="JUMBO- BOX TRAILER">JUMBO- BOX TRAILER</option><option value="MEGA TRAILER">MEGA TRAILER</option><option value="REEFER TRAILER">REEFER TRAILER</option><option value="CURTAIN TRAILER">CURTAIN TRAILER</option><option value="TARPAULIN TRAILER">TARPAULIN TRAILER</option></select></div></div><div class="col"><div class="form-group"><label>Currency</label><select name="currency_type_for_land[]" class="custom-select"><option value="">Select Currency</option><option value="American Dollar">American Dollar</option><option value="Euro">Euro</option><option value="Great British Pound">Great British Pound</option><option value="Turish Lira">Turish Lira</option><option value="Australian Dollar">Australian Dollar</option><option value="Canadian Dollar">Canadian Dollar</option></select></div></div><div class="col"><div class="form-group"><label>Price</label><input type="number" name="price_for_land[]" class="form-control" placeholder="Enter Price"></div></div><div class="col"><p class="btn btn-secondary mt-4"  onclick="deleterow(this)" style="cursor:pointer;">Remove</p></div></div>');
         });
         
         $('#AddFieldforlcl').click(function(){
              $('#forsealcl').append('<div class="row"><div class="col"><div class="form-group"><label>Cost Type</label><select name="cost_type_for_sea_lcl[]" class="custom-select"><option value="">Select Cost Type</option><option value="OCEAN FREIGHT">OCEAN FREIGHT</option><option value="O-THC">O-THC</option><option value="D-THC">D-THC</option><option value="BILL OF LADING">BILL OF LADING</option><option value="DELIEVER ORDER">DELIEVER ORDER</option><option value="E.N.S">E.N.S</option><option value="LCL SERVICE FEE">LCL SERVICE FEE</option><option value="LOW SULPHURE SRC">LOW SULPHURE SRC</option><option value="IMO 2020">IMO 2020</option><option value="SEAL FEE">SEAL FEE </option><option value="ISPS">ISPS </option><option value="FREE IN">FREE IN</option><option value="FREE OUT">FREE OUT</option><option value="LINER IN">LINER IN</option><option value="LINER OUT">LINER OUT</option><option value="DOCUMENTATION FEE">DOCUMENTATION FEE</option><option value="FREE ZONE EXTRA FEE">FREE ZONE EXTRA FEE </option><option value="FREE ZONE EXTRA FEE ">FREE ZONE EXTRA FEE </option><option value="BONDED TRUCK FEE">BONDED TRUCK FEE</option><option value="WAREHOUSE FEE">WAREHOUSE FEE</option><option value="STORAGE FEE">STORAGE FEE</option><option value="HANDLING FEE">HANDLING FEE</option><option value="STUFFING FEE">STUFFING FEE</option><option value="CERTIFICATE FEE">CERTIFICATE FEE</option><option value="SUEZ CANAL SRC">SUEZ CANAL SRC</option><option value="B.O.F.">B.O.F.</option><option value="B.A.F.">B.A.F.</option><option value="C.A.F.">C.A.F.</option></select></div></div><div class="col"><div class="form-group"><label>Calculation</label><select name="calculaion_for_sea_lcl[]" class="custom-select"><option value="">Select Calculation Type</option><option value="Cubic Meter">Cubic Meter</option><option value="SET">SET</option></select></div></div><div class="col"><div class="form-group"><label>Currency</label><select name="currency_type_for_sea_lcl[]" class="custom-select"><option value="">Select Currency</option><option value="American Dollar">American Dollar</option><option value="Euro">Euro</option><option value="Great British Pound">Great British Pound</option><option value="Turish Lira">Turish Lira</option><option value="Australian Dollar">Australian Dollar</option><option value="Canadian Dollar">Canadian Dollar</option></select></div></div><div class="col"><div class="form-group"><label>Price</label><input type="number" name="price_for_sea_lcl[]" class="form-control" placeholder="Enter Price"></div></div><div class="col"><p class="btn btn-secondary mt-4"  onclick="deleterow(this)" style="cursor:pointer;">Remove</p></div></div>');
         });
         
         $('#AddFieldforfcl').click(function(){
              $('#forseafcl').append('<div class="row"><div class="col"><div class="form-group"><label>Cost Type</label><select name="cost_type_for_sea_fcl[]" class="custom-select"><option value="">Select Cost Type</option><option value="OCEAN FREIGHT">OCEAN FREIGHT</option><option value="O-THC">O-THC</option><option value="D-THC">D-THC</option><option value="BILL OF LADING">BILL OF LADING</option><option value="DELIEVER ORDER">DELIEVER ORDER</option><option value="E.N.S">E.N.S</option><option value="LCL SERVICE FEE">LCL SERVICE FEE</option><option value="LOW SULPHURE SRC">LOW SULPHURE SRC</option><option value="IMO 2020">IMO 2020</option><option value="SEAL FEE">SEAL FEE </option><option value="ISPS">ISPS </option><option value="FREE IN">FREE IN</option><option value="FREE OUT">FREE OUT</option><option value="LINER IN">LINER IN</option><option value="LINER OUT">LINER OUT</option><option value="DOCUMENTATION FEE">DOCUMENTATION FEE</option><option value="FREE ZONE EXTRA FEE">FREE ZONE EXTRA FEE </option><option value="FREE ZONE EXTRA FEE ">FREE ZONE EXTRA FEE </option><option value="BONDED TRUCK FEE">BONDED TRUCK FEE</option><option value="WAREHOUSE FEE">WAREHOUSE FEE</option><option value="STORAGE FEE">STORAGE FEE</option><option value="HANDLING FEE">HANDLING FEE</option><option value="STUFFING FEE">STUFFING FEE</option><option value="CERTIFICATE FEE">CERTIFICATE FEE</option><option value="SUEZ CANAL SRC">SUEZ CANAL SRC</option><option value="B.O.F.">B.O.F.</option><option value="B.A.F.">B.A.F.</option><option value="C.A.F.">C.A.F.</option></select></div></div><div class="col"><div class="form-group"><label>CHARGE TYPES</label><select name="calculaion_for_sea_fcl[]" class="custom-select text-uppercase"><option value="">SELECT CONTAINER TYPE</option><option value="20 DV STANDART CNTR">20 DV STANDART CNTR</option><option value="40’DV STANDART CNTR">40’DV STANDART CNTR</option><option value="40’HC CNTR">40’HC CNTR</option><option value="45’HC CNTR">45’HC CNTR</option><option value="45’PW PALLET WIDE CNTR">45’PW PALLET WIDE CNTR</option><option value="20’RF REEFER CNTR">20’RF REEFER CNTR</option><option value="40’RF REEFER CNTR">40’RF REEFER CNTR</option><option value="20’OT OPEN TOP CNTR">20’OT OPEN TOP CNTR</option><option value="40’OT OPEN TOP CNTR">40’OT OPEN TOP CNTR</option><option value="SET">SET</option></select></div></div><div class="col"><div class="form-group"><label>Currency</label><select name="currency_type_for_sea_fcl[]" class="custom-select"><option value="">Select Currency</option><option value="American Dollar">American Dollar</option><option value="Euro">Euro</option><option value="Great British Pound">Great British Pound</option><option value="Turish Lira">Turish Lira</option><option value="Australian Dollar">Australian Dollar</option><option value="Canadian Dollar">Canadian Dollar</option></select></div></div><div class="col"><div class="form-group"><label>Price</label><input type="number" name="price_for_sea_fcl[]" class="form-control" placeholder="Enter Price"></div></div><div class="col"><p class="btn btn-secondary mt-4"  onclick="deleterow(this)" style="cursor:pointer;">Remove</p></div></div>');
         });
         
         
          // end of add freight
          
          /*Shipment start here*/
          
          $('#addfieldgoodsdesc').click(function(){
              $('#more_goods_desc').append('<div class="row border-top py-3"><div class="col-md-12"><h4>Goods Description</h4></div><div class="col-md-4"><div class="form-group"><label for="">Goods Name</label><input type="text" name="goods_name[]" class="form-control"></div></div><div class="col-md-4"><div class="form-group"><label for="">Packing Types</label><input type="text" name="packing_types[]" class="form-control"></div></div><div class="col-md-4"><div class="form-group"><label for="">Number Of Package </label><input type="number" name="packege_quantity[]" class="form-control packege_quantity"></div></div><div class="col-md-4"><div class="form-group"><label for="">Gross Weight </label><input type="text" name="gross_weight[]" class="form-control "></div></div><div class="col-md-4"><div class="form-group"><label for="">Width (In CM )</label><input type="number" name="width[]" class="form-control goods_width"></div></div><div class="col-md-4"><div class="form-group"><label for="">Length (In CM )</label><input type="number" name="length[]" class="form-control"></div></div><div class="col-md-4"><div class="form-group"><label for="">Height (In CM )</label><input type="number" name="height[]" class="form-control"></div></div><div class="col-md-4"><div class="form-group"><label for="">Hs Code</label><input type="text" name="hs_code[]" class="form-control"></div></div><div class="form-group col-md-4"><label for="">Container Number</label><input type="text" name="container_number[]" class="form-control"></div><div class="form-group col-md-4"><label for="">Seal Number</label><input type="text" name="seal_no[]" class="form-control"></div><div class="form-group col-md-4"><label for="">Types Of Container</label><select name="type_opf_container[]" class="custom-select text-uppercase"><option value="">SELECT CONTAINER TYPE</option><option value="20 DV STANDART CNTR">20 DV STANDART CNTR</option><option value="40’DV STANDART CNTR">40’DV STANDART CNTR</option><option value="40’HC CNTR">40’HC CNTR</option><option value="45’HC CNTR">45’HC CNTR</option><option value="45’PW PALLET WIDE CNTR">45’PW PALLET WIDE CNTR</option><option value="20’RF REEFER CNTR">20’RF REEFER CNTR</option><option value="40’RF REEFER CNTR">40’RF REEFER CNTR</option><option value="20’OT OPEN TOP CNTR">20’OT OPEN TOP CNTR</option><option value="40’OT OPEN TOP CNTR">40’OT OPEN TOP CNTR</option></select></div><div class="col-md-4"><p class="btn btn-secondary mt-4"  onclick="deleterow(this)" style="cursor:pointer;">Remove</p></div></div>');
          }); 
          
          
          $('#cubic_calculator').click(function(){
          

            var w = 0;
            $('.goods_width').each(function (index, element) {
                w = w + parseFloat($(element).val());
            });
            
            var h = 0;
            $('.goods_height').each(function (index, element) {
                h = h + parseFloat($(element).val());
            });
            
            var l = 0;
            $('.goods_length').each(function (index, element) {
                l = l + parseFloat($(element).val());
            });
            
            var q = 0;
            $('.packege_quantity').each(function (index, element) {
                q = q + parseFloat($(element).val());
            });
           
           
            
                    var result =    (w*h*l*q)/1000000;
            if(isNaN(result)){
                    $('#cubic_calculation').html("Fill All Values").css('color','red');
            }
            else{
                $('#cubic_calculation').html(result + ' CBM');
            }
            
            
          });
          
          
           $('#feeder_vessel').change(function(){
            var data = $(this).val();
            $('#feeder_voyage').html('<option value="">Select City</option>');
            $.ajax({
                url: path+'/getvoyage',
                type: 'GET',
                data: {data: data},
                success:function(data){
                  $('#feeder_voyage').append(data);
                }
            });        
        });
        
        $('#main_vessel').change(function(){
            var data = $(this).val();
            $('#main_voyage').html('<option value="">Select City</option>');
            $.ajax({
                url: path+'/getvoyage',
                type: 'GET',
                data: {data: data},
                success:function(data){
                  $('#main_voyage').append(data);
                }
            });        
        });
        $('#port_of_discharge').change(function(){
            var destination_port = $(this).val();
            var origin_port = $('#origin_port').val();
            if(origin_port !=""){
                destination_port = destination_port.substring(2, 5);
                origin_port = origin_port.substring(2, 5);
                var d = new Date();
            var year = d.getFullYear().toString().substr(-2);
            
            var    data = Math.floor(Math.random() * (999999 - 222222) + 222222); 
            $('#shipping_Refrence').val(origin_port+year+destination_port+data);   
            }
        });
        /*end of shipment*/

        /*customer data*/
            $('#AddFieldforcustomer').click(function(){
                $('#multiple_name').append('<div class="row"><div class="col"><div class="form-group"><label for="">Name</label><input type="text" name="name[]" class="form-control" required></div></div><div class="col"><div class="form-group"><label for="">Email</label><input type="email" name="email[]" class="form-control" required></div></div><div class="col"><div class="form-group"><label for="">Occuption</label><input type="text" name="occuption[]" class="form-control" required></div></div><div class="col"><p class="btn btn-secondary mt-4"  onclick="deleterow(this)" style="cursor:pointer;">Remove</p></div></div>');
            });
        /*end of customer data*/
    
    
    
    /*Global Vessel start here*/
    $(document).on('click','.sendglobvesselemailModal',function(e) {
         $("#sendglobvesselemailModal .send_email_btn").prop('disabled', false);
        $("#sendglobvesselemailModal .emailsent_scus").hide();
        $("#sendglobvesselemailModal .form_inputs").show();
        $('#sendglobvesselemailModal .email').val(" ");
        $('#sendglobvesselemailModal #vessel_id').val($(this).data('id'));
    });
    
    $("#sendglobvesselemailModal form").submit(function(e) {
        $("#sendglobvesselemailModal .send_email_btn").prop('disabled', true);
        e.preventDefault();
        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
          }
        });     
        $.ajax({
            url: path+'/vsendmail',
            type: 'POST',
            data: $(this).serialize(),
            success:function(data){
                $("#sendglobvesselemailModal .form_inputs").hide();
                $("#sendglobvesselemailModal .emailsent_scus").show();
            }
        });
     });
    /*Global Vessel End here*/


     /*Vessel schedule Jquery and ajax start here*/
    $('#addvesselschedule #vessel_name').change(function(e){
      e.preventDefault();
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
          }
      });     
      jQuery.ajax({
        url: path+"/get_vessel_by",
        method: 'post',
        data: {id: $(this).val()},
        success: function(result){
          $('#addvesselschedule #imo_no').val(result[0].imo_no);
          $('#addvesselschedule #built_date').val(result[0].built_date);
          $('#addvesselschedule #flag').val(result[0].flag);
          $('#addvesselschedule #liner_agent').val(result[0].liner_agent);
        }});
    });

    $('#addvesselschedule #departure_country').change(function(e){
        e.preventDefault();
        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
          }
        });     
        $.ajax({
            url: path+'/getport',
            type: 'GET',
            data: {data: $(this).val()},
            success:function(data){
              $('#addvesselschedule #departure_port').append(data);
            }
        });
     });

    $('#addvesselschedule #arrival_country').change(function(e){
        e.preventDefault();
        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
          }
        });     
        $.ajax({
            url: path+'/getport',
            type: 'GET',
            data: {data: $(this).val()},
            success:function(data){
              $('#addvesselschedule #arrival_port').append(data);
            }
        });
     });
     
     
    $(document).on('click','.sendvesselemailModal',function(e) {
         $("#sendvesselemailModal .send_email_btn").prop('disabled', false);
        $("#sendvesselemailModal .emailsent_scus").hide();
        $("#sendvesselemailModal .form_inputs").show();
        $('#sendvesselemailModal .email').val(" ");
        $('#sendvesselemailModal #vessel_schedule_id').val($(this).data('id'));
    });
    
    $("#sendvesselemailModal form").submit(function(e) {
        $("#sendvesselemailModal .send_email_btn").prop('disabled', true);
        e.preventDefault();
        $.ajax({
            url: path+'/vssendmail',
            type: 'POST',
            data: $(this).serialize(),
            success:function(data){
                $("#sendvesselemailModal .form_inputs").hide();
                $("#sendvesselemailModal .emailsent_scus").show();
            }
        });
     });
    /*Vessel scedule Jquery and ajax end here*/
    
    
    /*Display menu dropdown start*/
    $(document).on( 'click', '.metismenu .nav-item', function () { 
        $('.metismenu .nav-item').removeClass("active");
        $('.metismenu .nav-item').removeClass("active_drop");
        if($(this).find('.dropdown-menu').length !== 0){
            $(this).addClass("active");
            $(this).addClass("active_drop");
        }
    });
    /*Display menu dropdown end*/
    
    
    
    /*--------------------------Offer Jquery start here-----------------------------------*/
    $(document).on( 'click', '#offerForm #AddMoreForOffer', function () { 
        $(this).closest(".service_cost_clone").clone().appendTo(".appendhere");
        $(this).closest(".service_cost_clone .append_btn_dlt").append('<span class="btn btn-success mt-4" id="remove" >Remove</span>');
        $(this).closest(".service_cost_clone #AddMoreForOffer").remove();
    }); 
    $(document).on( 'click', '#offerForm #remove', function () { 
        $(this).closest(".service_cost_clone").remove();
    }); 
    $(document).on( 'change', '#offerForm #freight_id', function () { 
        $(".se-pre-con").show();
        var data_value = $(this).children("option:selected").attr("data-val");
        var data_type = $(this).children("option:selected").attr("data-type");
        $(".cal_selc").hide();
        $(".select_for_air select").removeAttr("required");
        
        if(data_value == "air"){
            $(".select_for_air").show();
            $(".select_for_air select").attr("required", "true");
        } else if(data_value == "land"){
            $(".cal_land").show();
        } else if(data_value == "sea"){
            if(data_type == "lcl"){
               $(".cal_sea_lcl").show(); 
            }else{
               $(".cal_sea_fcl").show(); 
            }
        }
        
        $.ajax({
            url: path+'/offer/get_cost_type',
            type: 'POST',
            data: {data: data_value},
            success:function(data){
                  $("#offerForm .cost_type_select").empty();
                jQuery.each(data, function(index, itemData) {
                  $("#offerForm .cost_type_select").append("<option "+itemData.cost_type+">"+itemData.cost_type+"</option>");
                });
                $(".se-pre-con").hide();
            }
        });
    });
    /*--------------------------Offer Jquery end here-----------------------------------*/
    /*-------------------------------------------------------------*/
    
    /*Freight Type Change Empty Fields Start Here*/
    $(document).on( 'change', '#saerchofferForm .freight_type_name', function () { 
        $(this).closest('form').find("input[type=text]").val("");
    });
    /*Freight Type Change Empty Fields End Here*/
    
    /*Filter offer data submit Start Here*/
    $(document).on( 'submit', '#saerchofferForm', function (e) { 
        $(".se-pre-con").show();
        e.preventDefault();
        $.ajax({
            url: path+'/get_freights_by',
            type: 'POST',
            dataType: "json",
            data: $(this).serialize(),
            success:function(data){
              $("#freight_id").empty();
              $("#freight_id").append('<option value="">Select freight</option>');
              $.map(data, function (item) {
                $("#freight_id").append('<option value="'+item.id+'" data-val="'+item.service_category+'" data-type="'+item.service_type+'">'+item.service_category+' - '+item.departure_country+' - '+item.arriaval_country+' - '+item.arriaval_port+'</option>');
              });
              $(".se-pre-con").hide();
            }
        });
        
    });
    /*Filter offer data submit Start Here*/
    
    /* Get Country By words Start here*/
    $('#dep_country_name, #arv_country_name').typeahead({
        source: function (query, result) {
            $.ajax({
                url: path+'/getcountry',
				        data: 'query=' + query+'&type='+$(".freight_type_name").children("option:selected").val(),            
                dataType: "json",
                type: "POST",
                success: function (data) {
        					result($.map(data, function (item) {
        						return item.countryName;
                  }));
                }
            });
        }
    });
    /* Get Country By words end here*/
    
    /* Get ports By words start here*/
    $('#arv_ports_name').typeahead({
        source: function (query, result) {
            var values = $('#saerchofferForm').serialize();
            $.ajax({
                url: path+'/getportsbyword',
				        data: values,            
                dataType: "json",
                type: "POST",
                success: function (data) {
        					result($.map(data, function (item) {
        						return item.ports;
                  }));
                }
            });
        }
    });
    /* Get ports By words End here*/
    
    /*-------------------------------------------------------------*/
    /*-------------------------------------------------------------*/
    /*-------------------------------------------------------------*/
    /*-------------------------------------------------------------*/
    $("body").delegate(".prc_transf","blur",function(event){
        event.preventDefault();
        var val = $(this).val();
   
        if(val != ''){
            var y = val.replace(',','');
           var  x = Math.ceil(y);
                
 
           
            const formatter = new Intl.NumberFormat('en-US', {
            
            currency: 'INR',
             minimumFractionDigits: 2
            });

        var val2 = formatter.format(x);
            $(this).val(val2);
        }

    });

    $(document).on( 'click', '.edit_product_image .remove-img', function (e) { 
      e.preventDefault();
      $.ajax({
          url: path+'/user/products/removeimage',
          data: 'product_id=' + $(this).data("val")+'&image_name='+$(this).data("id"),            
          dataType: "json",
          type: "POST",
          success: function (data) {
            location.reload();
          }
      });
    });

    $(document).on( 'click', '.edit_timeline_image .remove-img', function (e) { 
      e.preventDefault();
      $.ajax({
          url: path+'/timeline/removeimage',
          data: 'product_id=' + $(this).data("val")+'&image_name='+$(this).data("id"),            
          dataType: "json",
          type: "POST",
          success: function (data) {
            location.reload();
          }
      });
    });

    $(document).on( 'click', '.edit_otherservice_image .remove-img', function (e) { 
      e.preventDefault();
      $.ajax({
          url: path+'/'+$(this).data("valnew")+'/removeimage',
          data: 'product_id=' + $(this).data("val")+'&image_name='+$(this).data("id"),            
          dataType: "json",
          type: "POST",
          success: function (data) {
            location.reload();
          }
      });
    });

    
}); 
function deleterow(e){
    e.parentNode.parentNode.parentNode.removeChild(e.parentNode.parentNode);
}


            
function send_friend_request(id){

  $(".send_friend_request .send span").empty();
  $(".send_friend_request .send span").text("sending..!!");
  var data = new FormData();
  data.append('id', id);

  $.ajax({
      url: BASE_URL + '/friend/sendrequest',
      type: "POST",
      timeout: 5000,
      data: data,
      contentType: false,
      cache: false,
      processData: false,
      headers: {'X-CSRF-TOKEN': CSRF},
      success: function (response) {
          if (response.code == 200) {
              $(".send_friend_request .send span").text("Cancel Request..!!");
          } else if (response.code == "delete") {
              $(".send_friend_request .send span").text("Send Friend Request..!!");
          } else {
              $(". .send span").append("Error..");
          }
      },
      error: function () {
          $(".send_friend_request .send span").append("Error..Refresh the page.");
      }
  });
}
            
function accept_friend_request(id){

  $(".send_friend_request .send span").empty();
  $(".send_friend_request .send span").text("sending..!!");
  var data = new FormData();
  data.append('id', id);

  $.ajax({
      url: BASE_URL + '/friend/acceptrequest',
      type: "POST",
      timeout: 5000,
      data: data,
      contentType: false,
      cache: false,
      processData: false,
      headers: {'X-CSRF-TOKEN': CSRF},
      success: function (response) {
          if (response.code == 200) {
              $(".send_friend_request .send span").text("Request Accepted..!!");
          } else {
              $(".send_friend_request .send span").append("Error..");
          }
      },
      error: function () {
          $(".send_friend_request .send span").append("Error..Refresh the page.");
      }
  });
}
           
        
       