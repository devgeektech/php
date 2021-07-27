<?php

define('SHOPIFY_APP_SECRET', '');
define('VIMEO_API_KEY', '');

//init
 ini_set("log_errors", 1);
 ini_set('error_reporting', E_ALL);
 ini_set("error_log", "error.log");

function verify_webhook($data, $hmac_header){
  $calculated_hmac = base64_encode(hash_hmac('sha256', $data, SHOPIFY_APP_SECRET, true));
  return hash_equals($hmac_header, $calculated_hmac);
}

$hmac_header = $_SERVER['X_SHOPIFY_HMAC_SHA256'];
$data = file_get_contents('php://input');
$orderdata = json_decode($data);

if($orderdata){
	$user_email = !empty($orderdata->email) ? $orderdata->email : '';
	$product_id = !empty($orderdata->line_items[0]->product_id) ? $orderdata->line_items[0]->product_id : '';
	$user_first_name = !empty($orderdata->billing_address->first_name) ? $orderdata->billing_address->first_name : '';
	$user_last_name = !empty($orderdata->billing_address->last_name) ? $orderdata->billing_address->last_name : '';
    $order_id = !empty($orderdata->id) ? $orderdata->id : '';

	//Customer Data for Vimeo
	$api_data = array(
		'product_id' => $product_id,
		'user_email' => $user_email,
		'user_first_name' => $user_first_name,
		'user_last_name' => $user_last_name
	);
    
    $product_ids = array("1205869406748837","56556143575687333","655656567045595301","29555273679069349","955273685983397","7846694044008613","556738782159013","8956841995821221","285846892764168357","85526904512151717");
    
    foreach ($orderdata->line_items as $order_product_id){
        
        if (in_array($order_product_id->product_id, $product_ids)){
            
            $product_id = $order_product_id->product_id;
            
        	switch ($product_id) {
        	  	case "5869406748837":
        	    	$vimeo_product_id = "84153";
        	    break;
        		case "6143575687333":
        			$vimeo_product_id = "84153";
        	    break;
        	    case "6567045595301":
        			$vimeo_product_id = "97961";
        	    break;
        	    case "5273679069349":
        			$vimeo_product_id = "68377";
        	    break;
        	    case "5273685983397":
        			$vimeo_product_id = "68377";
        	    break;
        	    case "6738782159013":
        			$vimeo_product_id = "69472";
        	    break;
        	    case "6841995821221":
        			$vimeo_product_id = "108585";
        	    break;
                case "6892764168357":
        			$vimeo_product_id = "110161";
        	    break;
        	     case "6904512151717":
        			$vimeo_product_id = "108585";
        	    break;
        	    default:
        	    	$vimeo_product_id = "";
        	}
            
            // If bundle subscription of specific product
            if($product_id == "6694044008613"){
    
        		$curl = curl_init();
        		curl_setopt_array($curl, array(
        		  CURLOPT_URL => 'https://api.vhx.tv/customers',
        		  CURLOPT_RETURNTRANSFER => true,
        		  CURLOPT_ENCODING => '',
        		  CURLOPT_MAXREDIRS => 10,
        		  CURLOPT_TIMEOUT => 0,
        		  CURLOPT_FOLLOWLOCATION => true,
        		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        		  CURLOPT_CUSTOMREQUEST => 'POST',
        		  CURLOPT_POSTFIELDS => array(
        		  		'name' => $user_first_name.' '.$user_last_name,
        		  		'email' => $user_email,
        		  	 	'product' => 'https://api.vhx.tv/products/97961',
        		  	  	'plan' => 'standard',
        		  	  	'send_email' => 1,
        			),
        
        		  CURLOPT_USERPWD => VIMEO_API_KEY
        		));
        
        		$response = curl_exec($curl);
        	 	curl_close($curl);
        
        	 	//another Vimeo bundle Product
    
        		$curl = curl_init();
        		curl_setopt_array($curl, array(
        		  CURLOPT_URL => 'https://api.vhx.tv/customers',
        		  CURLOPT_RETURNTRANSFER => true,
        		  CURLOPT_ENCODING => '',
        		  CURLOPT_MAXREDIRS => 10,
        		  CURLOPT_TIMEOUT => 0,
        		  CURLOPT_FOLLOWLOCATION => true,
        		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        		  CURLOPT_CUSTOMREQUEST => 'POST',
        		  CURLOPT_POSTFIELDS => array(
        		  		'name' => $user_first_name.' '.$user_last_name,
        		  		'email' => $user_email,
        		  	 	'product' => 'https://api.vhx.tv/products/84153',
        		  	  	'plan' => 'standard',
        		  	  	'send_email' => 1,
        			),
        
        		  CURLOPT_USERPWD => VIMEO_API_KEY
        		));
        
        		$response = curl_exec($curl);
        	 	curl_close($curl);
            }
            else{
    
            	//Vimeo API
            	$curl = curl_init();
            	curl_setopt_array($curl, array(
            	  CURLOPT_URL => 'https://api.vhx.tv/customers',
            	  CURLOPT_RETURNTRANSFER => true,
            	  CURLOPT_ENCODING => '',
            	  CURLOPT_MAXREDIRS => 10,
            	  CURLOPT_TIMEOUT => 0,
            	  CURLOPT_FOLLOWLOCATION => true,
            	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            	  CURLOPT_CUSTOMREQUEST => 'POST',
            	  CURLOPT_POSTFIELDS => array(
            	  		'name' => $user_first_name.' '.$user_last_name,
            	  		'email' => $user_email,
            	  	 	'product' => 'https://api.vhx.tv/products/'.$vimeo_product_id,
            	  	  	'plan' => 'standard',
            	  	  	'send_email' => 1,
            		),
            
            	  CURLOPT_USERPWD => VIMEO_API_KEY
            	));
            
            	$response = curl_exec($curl);
             	curl_close($curl);
         	
            }
        
        //Fulfillment of order
    }
    }
}
else{
	exit();
}

error_log('Order  '.print_r($response , TRUE));