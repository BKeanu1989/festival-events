<?php
/*
Plugin Name: Festival Events
Plugin URI: https://
Description: Festival Events via Woocommerce
Version: 0.1.0
Author: Kevin Fechner
Author URI: https://complete-webolutions.com
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
    add_action( 'wp_enqueue_scripts', 'frontend_scripts');
    add_action( 'admin_enqueue_scripts', 'fe_admin_scripts');
    
}

function fe_admin_scripts() {

    global $post;
    wp_enqueue_style('festival-events', plugins_url('dist/css/festival-events.css', __FILE__), [], false, 'all');
    // $localizedVars = $post->ID;
    wp_register_script('fe-admin-script', plugins_url('dist/admin/festival-events.js', __FILE__), [], false, true);
    $localizedVars = [];
    if (!empty($post)) {
        $localizedVars = ['postID' => $post->ID];
    }

    wp_localize_script('fe-admin-script', 'localizedVars', $localizedVars);
    wp_enqueue_script('fe-admin-script');
    // wp_enqueue_style('fe-bulma', 'https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.2/css/bulma.css');
}

function frontend_scripts() {
    // if (is_product()) {
        wp_enqueue_style('festival-events', plugins_url('dist/css/festival-events.css', __FILE__), [], false, 'all');
        // <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
        wp_enqueue_style('font-awesome', 'https://use.fontawesome.com/releases/v5.5.0/css/all.css');
        wp_register_script('festival-events-frontend', plugins_url('dist/festival-events-frontend.min.js', __FILE__), [], false, true);
        $currentLanguage = pll_current_language("slug");
        wp_localize_script('festival-events-frontend', 'currentLanguage', $currentLanguage);
        wp_enqueue_script('festival-events-frontend');
    // }

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



function fe_plugin_name_load_plugin_textdomain() {
	$domain = 'festival-events';
	$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
	// wp-content/languages/plugin-name/plugin-name-de_DE.mo
	load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
	// wp-content/plugins/plugin-name/languages/plugin-name-de_DE.mo
	load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}

add_action( 'init', 'fe_plugin_name_load_plugin_textdomain' );


// $attribute_taxonomies = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name != '' ORDER BY attribute_name ASC;" );
// set_transient( 'wc_attribute_taxonomies', $attribute_taxonomies );

function fe_plugin_activation() {
    // make sure wc attribute taxonomies are installed
    global $wpdb;
    // TODO: could be dangerous if database rows are switched
    $attributes = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}woocommerce_attribute_taxonomies WHERE attribute_name IN ('locker', 'period', 'location');" );
    list($lockerAttributes, $periodAttributes, $locationAttributes) = $attributes;
    // var_dump($wc_attribute_taxonomies);
}

register_activation_hook(__FILE__, 'fe_plugin_activation');



/**
 * Add Confirm Email Field
 * 
 */
add_filter( 'woocommerce_checkout_fields' , 'fe_add_email_verification_field_checkout' );
function fe_add_email_verification_field_checkout( $fields ) {
    $fields['billing']['billing_email']['class'] = array('form-row-first');
    $fields['billing']['billing_email_confirm'] = array(
        'label' => __('Bestätige deine Email Adresse', 'festival-events'),
        'required' => true,
        'class' => array('form-row-last'),
        'clear' => true,
        'priority' => 999,
        'type' => 'email'
    );
    return $fields;
}

add_action('woocommerce_checkout_process', 'fe_matching_email_addresses');
function fe_matching_email_addresses() { 
    $email1 = $_POST['billing_email'];
    $email2 = $_POST['billing_email_confirm'];
    if ( $email2 !== $email1 ) {
        wc_add_notice( __( 'Deine Email Adressen stimmen nicht überein.', 'festival-events' ), 'error' );
    }
}

/**
 * Add Bday field
 */
add_filter( 'woocommerce_checkout_fields', 'fe_add_birthday_field_checkout');
function fe_add_birthday_field_checkout( $fields ) {

    $fields['billing']['billing_birthday'] = array(
        'label' => __('Geburtstag', 'festival-events'),
        'required' => true,
        'class' => [],
        'clear' => true,
        'priority' => 25,
        'type' => 'date'
    );
    return $fields;
}


add_action('woocommerce_checkout_process', 'fe_validate_bday');
function fe_validate_bday() { 
    $bday = $_POST['billing_birthday'];
    if ( empty($bday) ) {
        wc_add_notice( __( 'Dein Geburtstag darf nicht leer sein.', 'festival-events' ), 'error' );
    }
}