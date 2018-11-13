<?php
/*
Plugin Name: Festival Events
Plugin URI: https://
Description: Festival Events via Woocommerce
Version: 0.1.0
Author: Kevin Fechner
Author URI: http://health-check-team.example.com
Text Domain: festival-events
Domain Path: /languages
*/


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

define('FESTIVAL_EVENTS_PLUGIN_PATH', WP_PLUGIN_DIR . '/festival-events/');

require_once(FESTIVAL_EVENTS_PLUGIN_PATH . 'fe-core-functions.php');
require_once(FESTIVAL_EVENTS_PLUGIN_PATH . 'includes/class-Festival-Product.php');


/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    // Put your plugin code here
    add_action( 'plugins_loaded', 'wcpt_register_gift_card_type' );
    add_action( 'woocommerce_product_data_panels', 'wcpt_gift_card_options_product_tab_content' );
    add_action( 'woocommerce_process_product_meta', 'save_gift_card_options_field' );
    add_action( 'woocommerce_single_product_summary', 'gift_card_template', 60 );

    add_filter( 'product_type_selector', 'wcpt_add_gift_card_type' );
    add_filter( 'woocommerce_product_data_tabs', 'gift_card_tab' );

}

// create product type - Festival

// Name
// Logo
// Generelle Infos
// Hinweise
// 
// date range

function wcpt_register_gift_card_type () {
	// declare the product class
    class WC_Product_Gift_Card extends WC_Product {
        public function __construct( $product ) {
           $this->product_type = 'gift_card';
           parent::__construct( $product );
           // add additional functions here
        }
    }
}

function wcpt_add_gift_card_type ( $type ) {
	// Key should be exactly the same as in the class product_type
	$type[ 'gift_card' ] = __( 'Gift Card' );
	
	return $type;
}

function gift_card_tab( $tabs) {

    // write_log($tabs);
	
	$tabs['gift_card'] = array(
		'label'	 => __( 'Gift Card', 'wcpt' ),
		'target' => 'gift_card_options',
		'class'  => ('show_if_gift_card'),
    );
    
    // array_push($tabs['general']['class'], 'show_if_gift_card');
	return $tabs;
}

function wcpt_gift_card_options_product_tab_content() {
	// Dont forget to change the id in the div with your target of your product tab
	?><div id='gift_card_options' class='panel woocommerce_options_panel'><?php
		?><div class='options_group'><?php
			woocommerce_wp_checkbox( array(
				'id' 	=> '_enable_gift_card',
				'label' => __( 'Enable Gift Card Product', 'wcpt' ),
			) );
			woocommerce_wp_text_input( array(
				'id'          => '_gift_card_price',
				'label'       => __( 'Price', 'wcpt' ),
		       		'placeholder' => '',
		       		'desc_tip'    => 'true',
		       		'description' => __( 'Enter Gift Card Price.', 'wcpt' ),
		       ));
		?></div>
	</div><?php
}

function save_gift_card_options_field( $post_id ) {
	$enable_gift_card = isset( $_POST['_enable_gift_card'] ) ? 'yes' : 'no';
	update_post_meta( $post_id, '_enable_gift_card', $enable_gift_card );
	if ( isset( $_POST['_gift_card_price'] ) ) :
		update_post_meta( $post_id, '_gift_card_price', sanitize_text_field( $_POST['_gift_card_price'] ) );
	endif;
}

function gift_card_template () {
	global $product;
	if ( 'gift_card' == $product->get_type() ) {
		$template_path = plugin_dir_path( __FILE__ ) . 'templates/';
		// Load the template
		wc_get_template( 'single-product/add-to-cart/gift_card.php',
			'',
			'',
			trailingslashit( $template_path ) );
	}
}

/**
 * Show pricing fields for gift_coupon product.
 */
// add_action( 'admin_header', 'gift_coupon_custom_js' );
add_action( 'admin_footer', 'gift_coupon_custom_js' );
function gift_coupon_custom_js() {
    if ( 'product' != get_post_type() ) :
        return;
    endif;
    ?><script type='text/javascript'>
    // let timer;
    // // 
    // _pricingOption = document.querySelector('.options_group.pricing');
    // timer = setInterval(function() {
    //     if (_pricingOption) {
    //         if (!_pricingOption.style.display === 'block') {
    //             jQuery( '#general_product_data').addClass('show_if_gift_coupon').show();
    //             jQuery( '.general_options').addClass('show_if_gift_coupon').show();
    //             jQuery( '#general_product_data').css('cssText', 'display: block !important;');
    //             // jQuery( '#general_product_data').addClass('fk_this');
    //             jQuery( '.options_group.pricing' ).addClass( 'show_if_gift_coupon' );
    //             jQuery( '.options_group.pricing').css('cssText', 'display: block !important;');

    //             jQuery( '.options_group.pricing' ).removeClass( 'hidden' );
    //         } else {
    //             clearTimer();
    //         }
    //     }
    // }, 500);

    // function clearTimer() {
    //     clearInterval(timer);
    // }

    setTimeout(() => {
        jQuery( '#general_product_data').addClass('show_if_gift_coupon').show();
        jQuery( '.general_options').addClass('show_if_gift_coupon').show();
        jQuery( '#general_product_data').css('cssText', 'display: block !important;');
        // jQuery( '#general_product_data').addClass('fk_this');
        jQuery( '.options_group.pricing' ).addClass( 'show_if_gift_coupon' );
        jQuery( '.options_group.pricing').css('cssText', 'display: block !important;');

        jQuery( '.options_group.pricing' ).removeClass( 'hidden' );
    }, 1000);
    </script><?php
}



// if ( is_admin() ) {
//     // we are in admin mode
//     require_once( dirname( __FILE__ ) . '/admin/plugin-name-admin.php' );
// }



//   // Add Variation Settings
// add_action( 'woocommerce_product_after_variable_attributes', 'variation_settings_fields', 10, 3 );
// // Save Variation Settings
// add_action( 'woocommerce_save_product_variation', 'save_variation_settings_fields', 10, 2 );



// if ( is_admin() ) {
//     // we are in admin mode
//     require_once( dirname( __FILE__ ) . '/admin/plugin-name-admin.php' );
// }


// main product
// input DATE: StartDate
// input DATE: EndDate
// --> foreach day in between enumerate days as attribute
// select Lockers


// save these things to have them later on
// require ABSPATH. '/vendor/autoload.php';

// use Automattic\WooCommerce\Client;

// $woocommerce = new Client(
//     'http://localhost:8888/safeboxen',
//     $consumer_key,
//     $consumer_secret,
//     [
//         'wp_api' => true,
// 		'version' => 'wc/v2',
// 		'verify_ssl' => false
//     ]
// );
// print_r($woocommerce->get('products'));

// $prod_data = [
// 	'name'          => 'A great product',
// 	'type'          => 'simple',
// 	'regular_price' => '15.00',
// 	'description'   => 'A very meaningful product description',
// 	'images'        => [

// 	],
// 	'categories'    => [
// 		[
// 			'id' => 1,
// 		],
// 	],
// ];

// Create Variable Product
    // BASE:
    // Name == Festival Name
    // FestivalStart
    // FestivalEnd
    // What lockers are Available?
    // Locations
    // einzelene Tage buchbar?

    // On Save
        // Create Variations


// all available woocommerce_ form fields can be found here: /woocommerce/includes/admin/wc-meta-box-functions.php
// add_action( 'woocommerce_product_options_general_product_data', 'woo_add_festival_start' );
// add_action( 'woocommerce_process_product_meta', 'woo_save_festival_start' );

// function woo_add_festival_start() 
// {
//     global $woocommerce;

//     echo '<div class="options_group">';
    
//     woocommerce_wp_text_input(
//         [
//             'id' => '_festival_start',
//             'label' => __('Festivalstart:', 'festival-events'),
//             'placeholder' => '01.08.2019',
//             'desc_tip' => 'true',
//             'description' => __('Trage hier das Datum vom Start des Festivals ein.', 'festival-events'),
//             'type' => 'date'
//         ]
//     );
    
//     echo '</div>';
// }

// function woo_save_festival_start( $post_id ) 
// {
//     $festivalStart = $_POST['_festival_start'];
//     // add validation
//     // 
//     if (!empty( $festivalStart )) {
//         update__post_meta( $post_id , '_festival_start', esc_attr($festivalStart));
//     }
// }