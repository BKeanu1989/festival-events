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
    // add_action( 'plugins_loaded', 'wcpt_register_gift_card_type' );
    // add_action( 'woocommerce_product_data_panels', 'wcpt_gift_card_options_product_tab_content' );
    // add_action( 'woocommerce_process_product_meta', 'save_gift_card_options_field' );
    // add_action( 'woocommerce_single_product_summary', 'gift_card_template', 60 );

    // add_filter( 'product_type_selector', 'wcpt_add_gift_card_type' );
    // add_filter( 'woocommerce_product_data_tabs', 'gift_card_tab' );

}

// create product type - Festival

// Name
// Logo
// Generelle Infos
// Hinweise
// 
// date range

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