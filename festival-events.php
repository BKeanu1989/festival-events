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

$consumer_key = 'ck_e2224eac17ff2404aa824fbfa2b0891af4258b32'; // Add your own Consumer Key here
$consumer_secret = 'cs_dd6596af0493fbec1e30cf08c102989f2b886152'; // Add your own Consumer Secret here
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



