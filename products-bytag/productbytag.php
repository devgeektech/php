<?php
/**
 * Plugin Name: WooCommerce - List Products by Tags
 * Plugin URI: 
 * Description: List products by tags using a shortcode
 * Version: 1.0
 * Author: Geek Tech
 * Author URI: 
 * Requires at least: 3.5
 * Tested up to: 3.5
 *
 * Text Domain: -
 * Domain Path: -
 *
 */
/*
 * List WooCommerce Products by tags
 *
 * ex: [woo_products_by_tags tags="shoes,socks"]
 */
function woo_products_by_tags_shortcode( $atts = '', $content = null) {
	$price_symbol = get_woocommerce_currency_symbol();
	
	$state = "";

	if(isset($_GET['state']) && $_GET['state'] != ""){
		$state = $_GET['state'];
	}

	extract(shortcode_atts(array(
		"tags" => ''
	), $atts));
	
	ob_start();

	$outclass1 = '';
	$outclass2 = '';

	//==============================

	$args = array(
      'post_type' => 'product', 
      'posts_per_page' => -1,
      'product_tag' => $tags,
    );  
	$meta_query = array();

	if (!empty($_REQUEST['state'])) {
      $meta_query[] = array(
        'key' => 'state',
        'value' => $state,
        'compare' => '=', 
      );              
    }
	$product_count = $loop->post_count;

	if( $product_count > 0 ){
		?>
		<div class="tagsproducts">
			<div class="row">
		<?
			// Start the loop
			while ( $loop->have_posts() ) : $loop->the_post(); global $product;
				global $post;
				$url = get_permalink($post->ID);
				$state = (!empty(get_field('state',$post_id))) ? get_field('state',$post->ID) : 'N/A';
				$country = (!empty(get_field('country',$post_id))) ? get_field('country',$post->ID) : 'N/A';
				$parcel_size = (!empty(get_field('parcel_size',$post_id))) ? get_field('parcel_size',$post->ID) : 'N/A';
				$property_id = (!empty(get_field('property_id',$post_id))) ? get_field('property_id',$post_id) : 'N/A';
				$product = new WC_Product($post->ID); 
				$price = wc_price($product->get_price());
				$property_actual_price = (!empty(get_field('property_actual_price',$post_id))) ? get_field('property_actual_price',$post_id) : '0';
				$product_tags = wp_get_object_terms($post->ID, 'product_tag', '', ',' );	
				?>												
				<div class="col-xs-12 col-sm-6 col-md-4 product-tag-wrapper proptype-<? echo $tags;?> <? echo $outclass1;?>">
					<a href="<? echo $url;?>">
						<div class="image-wrapper-customs propimagetype-<? echo $tags;?> <? echo $outclass2;?>" style="background-image:url(<? echo get_the_post_thumbnail_url($post->ID);?>)">
							<?	               			 		               		
	               			 	if(!empty($tags) && $tags !="Available"){    
                    		?>  <div class="custom-collection-empty-wrapper"></div>                  			
                    			<h4 class="product-tagon-image"> <? echo $tags;?></h4>
                    		<? } ?>
                    		<? if($tags == 'out'){ ?>
                    			<div class="custom-collection-empty-wrapper"></div> 
                    			<h4 class="product-tagon-image">Sold</h4>

                    		 <? } ?>

						</div>
						<div class="produ-title mt-2 mb-1">
							<h4 class="prod-title-main"><? echo $thePostID = $post->post_title;?></h4>
						</div>
					
						<div class="produ-metabox mt-2 mb-1">
							<ul><li class="mbox-btns"><? echo $state;?></li> <li class="mbox-btns"><? echo $country;?></li><br>
							<li class="mbox-btns"><? echo $price_symbol . number_format($property_actual_price , 2);?></li> <li class="mbox-btns"><? echo $parcel_size;?></li></ul>
							<ul><li class="propid"><p class="prod-property_id-main"><? echo $property_id;?></p></li></ul>
						</div>										
					</a>					
					</div>					
				<?
				endwhile;
				?>
			</div>
		</div>
		<?
	}
	else{
		_e('No product matching your criteria.');
	}
	return ob_get_clean();
}
add_shortcode("woo_products_by_tags", "woo_products_by_tags_shortcode");
   
function apkl_out_of_stock_products_shortcode() {
 
   $args = array(
      'post_type' => 'product',
      'posts_per_page' => -1,
      'post_status' => 'publish',
      'meta_query' => array(
         array(
            'key' => '_stock_status',
            'value' => 'outofstock',
         )
      ),
      'fields' => 'ids',
   );
    
   $product_ids = get_posts( $args ); 
   $product_ids = implode( ",", $product_ids );
    
   return do_shortcode("[[products ids='$product_ids']]");
}

add_shortcode( 'out_of_stock_products', 'apkl_out_of_stock_products_shortcode' );