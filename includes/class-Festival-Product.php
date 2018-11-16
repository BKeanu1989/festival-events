<?php 

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

require ABSPATH. '/vendor/autoload.php';

use Automattic\WooCommerce\Client;


/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    // Put your plugin code here
    add_action( 'plugins_loaded', 'fe_register_festival_product_type' );
    // add_action( 'woocommerce_product_data_panels', 'fe_festival_product_options_product_tab_content');
    // add_action( 'woocommerce_process_product_meta', 'fe_save_festival_product_options_field' );
    add_action( 'woocommerce_single_product_summary', 'fe_festival_product_template', 60 );

    add_filter( 'product_type_selector', 'fe_add_festival_product_type' );
    add_filter( 'woocommerce_product_data_tabs', 'festival_product_tab' );

    fe_hook_general_tab_fields();
}

function fe_register_festival_product_type() {
    class WC_Product_Festival_Product extends WC_Product_Variation {
        public function __construct( $product ) {
            // $this->product_type = 'festival_product';
            parent::__construct( $product );
        }

        // schließfächer options
        // m, l, xl 
        // m - hv, l - hv, xl 
        
        // full festival
        // daily
        public function get_type() {
            return 'festival_product';
        }

        // copied from WC_Product_Variation
        public function get_catalog_visibility( $context = 'view' ) {
            // return apply_filters( $this->get_hook_prefix() . 'catalog_visibility', $this->parent_data['catalog_visibility'], $this );
            // 'my way'
            return apply_filters( $this->get_hook_prefix() . 'catalog_visibility', $this->data['catalog_visibility'], $this );
        }
        
        // :487
        public function is_purchasable() {
            $parent = get_parent_class($this);
            $baseParent = get_parent_class($parent);
            return apply_filters( 'woocommerce_variation_is_purchasable', $this->variation_is_visible() && $baseParent::is_purchasable() && ( 'publish' === $this->data['status'] || current_user_can( 'edit_post', $this->get_parent_id() ) ), $this );
        }

    }
}

function fe_add_festival_product_type ( $type ) {
    // Key should be exactly the same as in the class product_type
    $type[ 'festival_product' ] = __( 'Festival Produkt' );
    
    return $type;
}

function festival_product_tab( $tabs) {

    // write_log($tabs);
    
    $tabs['festival_product'] = array(
        'label'	 => __( 'Festival Produkt', 'wcpt' ),
        'target' => 'festival_product_options',
        'class'  => ('show_if_festival_product'),
    );
    
    array_push($tabs['variations']['class'], 'show_if_festival_product');
    // array_push($tabs['general']['class'], 'show_if_festival_product');
    return $tabs;
}

function hasProductAttributes() {
    // if not
    // prepopulate it

}

function fe_festival_product_template () {
    global $product;
    if ( 'festival_product' == $product->get_type() ) {
        $template_path = plugin_dir_path( __FILE__ ) . 'templates/';
        // Load the template
        wc_get_template( 'single-product/add-to-cart/festival_product.php',
            '',
            '',
            trailingslashit( $template_path ) );
    }
}

add_action( 'admin_footer', 'fe_festival_product_custom_js' );
function fe_festival_product_custom_js() {
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
        jQuery( '#general_product_data').addClass('show_if_festival_product').show();
        jQuery( '.general_options').addClass('show_if_festival_product').show();
        jQuery( '#general_product_data').css('cssText', 'display: block !important;');
        // jQuery( '#general_product_data').addClass('fk_this');
        jQuery( '.options_group.pricing' ).addClass( 'show_if_festival_product' );
        jQuery( '.options_group.pricing').css('cssText', 'display: block !important;');

        jQuery( '.options_group.pricing' ).removeClass( 'hidden' );
    }, 1000);
    </script><?php
}


// main product
// input DATE: StartDate
// input DATE: EndDate
// --> foreach day in between enumerate days as attribute
// select Lockers


// save these things to have them later on


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


// /Applications/MAMP/htdocs/safeboxen/wp-content/plugins/woocommerce/includes/wc-product-functions.php
// wc_get_product_visibility_options

function woo_display_festival_times() 
{
    global $woocommerce;

    echo '<div class="options_group">';
    
    woocommerce_wp_text_input(
        [
            'id' => '_festival_start',
            'label' => __('Festivalstart:', 'festival-events'),
            'placeholder' => '01.08.2019',
            'desc_tip' => 'true',
            'description' => __('Trage hier das Datum vom Start des Festivals ein.', 'festival-events'),
            'type' => 'date'
        ]
    );
    
    woocommerce_wp_text_input(
        [
            'id' => '_festival_end',
            'label' => __('Festivalende:', 'festival-events'),
            'placeholder' => '08.08.2019',
            'desc_tip' => 'true',
            'description' => __('Trage hier das Datum vom Ende des Festivals ein.', 'festival-events'),
            'type' => 'date'
        ]
    );
    
    echo '</div>';
}

function woo_save_festival_times( $post_id ) 
{
    // global $woocommerce;
    $festivalStart = $_POST['_festival_start'];
    $festivalEnd = $_POST['_festival_end'];
    // add validation
    // 
    if (!empty( $festivalStart )) {
        update_post_meta( $post_id , '_festival_start', esc_attr($festivalStart));
    }
    if (!empty( $festivalEnd )) {
        update_post_meta( $post_id , '_festival_end', esc_attr($festivalEnd));
    }
}

function woo_display_locations() 
{
	global $woocommerce, $thepostid, $post;

    $value = get_post_meta( $thepostid, '_festival_locations', true );
    $value_string = implode($value, ',');
    echo '<div class="options_group">';
    
    woocommerce_wp_text_input(
        [
            'id' => '_festival_locations',
            'label' => __('Standorte:', 'festival-events'),
            'placeholder' => 'Plaze, Center',
            'desc_tip' => 'true',
            'description' => __('Trage hier Kommasepariert die Standorte ein.', 'festival-events'),
            'type' => 'text',
            'value' => $value_string
        ]
    );
    
    echo '</div>';
}

function woo_save_locations($post_id) 
{
    // global $woocommerce;
    $festivalLocations = [];
    if (isset($_POST["_festival_locations"])) {
        $festivalLocations = setupLocations($_POST['_festival_locations']);
    }
    
    // add validation
    // 
    if (!count( $festivalLocations ) == 0) {
        update_post_meta( $post_id , '_festival_locations', $festivalLocations);
    }
}

$lockerDescription = ["", "M", "M HV", "L", "L HV", "XL", "XL HV"];

function woo_display_lockers() 
{
    
    // foreach location create checkbox ...
    global $woocommerce, $thepostid, $post, $lockerDescription;

    $locations = get_post_meta( $thepostid, '_festival_locations', true );
    $lockers = get_post_meta( $thepostid, '_lockers', true );

    if (count($lockers) == 0) {
        $lockers = setupLockers($locations);
    }

    echo '<div class="options_group">';
    echo '<h2>Schließfächer</h2>';
        foreach($lockers AS $location => $lockersForLocation) { 
            echo "<h3>{$location}</h3>";

            $iteratorLockerValue = 0;
            foreach($lockersForLocation AS $key => $value) {
                $lockerValue = $iteratorLockerValue + 1;
                woocommerce_wp_checkbox(array(
                    'id' => "_lockers[{$location}][{$lockerValue}]",
                    'label' => __($lockerDescription[$key], 'festival-events'),
                    'description' => __('vorhanden?', 'festival-events'),
                    'value' => 'yes',
                    'cbvalue' => $value
                ));
                $iteratorLockerValue++;
            }
        }
    echo '</div>';
}

function woo_save_lockers($post_id) 
{
    if (isset($_POST['_lockers'])) {
        $festivalLocations = setupLocations($_POST['_festival_locations']);
    
        $defaultLockers = setupLockers($festivalLocations);
        $arrayToSave = $defaultLockers;

        $postedLockers = $_POST['_lockers'];
        foreach($postedLockers AS $key => $value) {
            foreach($value AS $key2 => $value2) {
                $arrayToSave[$key][$key2] = "yes";
            }
        }
    
        if (!count( $arrayToSave ) == 0) {
            update_post_meta( $post_id , '_lockers', $arrayToSave);
        }
    }
}

function setupLocations($festivalLocations) 
{
    $festivalLocations_array = preg_split("/[,]+/", esc_attr($festivalLocations));
    $festivalLocations_array = array_map(function($x) {
        return trim($x);
    }, $festivalLocations_array);
    return $festivalLocations_array;
}

function setupLockers($locations) 
{
    $lockers = [];
    $lockerOptions = ["1" => 'no',"2" => 'no', "3" => 'no', "4" => 'no', "5" => 'no', "6" => 'no'];
    for ($iterator = 0; $iterator < count($locations); $iterator++) {
        $lockers[$locations[$iterator]] = $lockerOptions;
    }
    return $lockers;
}

function woo_display_populate() 
{
    echo '<div class="options_group">';
    
    woocommerce_wp_checkbox(array(
        'id' => "_populate_attributes",
        'label' => __('Varianten erstellen?', 'festival-events'),
        'description' => __('Willst du die verschiedenen Varianten erstellen?', 'festival-events'),
    ));
    
    echo '</div>';
}

function woo_callback_populate($post_id)
{
    try {
        // https://www.skyverge.com/blog/using-woocommerce-rest-api-introduction/
        // https://docs.woocommerce.com/wc-apidocs/class-WC_REST_Authentication.html
        // [woocommerce_rest_authentication_missing_parameter]
        global $consumer_key, $consumer_secret, $woocommerce;
        error_log("consumer key: {$consumer_key}");
        error_log("consumer secret:{$consumer_secret}");
        if (isset($_POST['_populate_attributes'])) {
    
    
            $woocommerce = new Client(
                'https://safeboxen.dev:8890',
                $consumer_key,
                $consumer_secret,
                [
                    'wp_api' => true,
                    'version' => 'wc/v2',
                    'verify_ssl' => false
                ]
            );
            // error_log(print_r($woocommerce->get('products', 1)));
            error_log(print_r($woocommerce->get('products', 1)));
    
        }
    } catch(Exception $e) {
        echo 'Exception abgefangen: ',  $e->getMessage(), "\n";
    }
}

function fe_hook_general_tab_fields() {

    // festival times
    add_action( 'woocommerce_product_options_general_product_data', 'woo_display_festival_times' );
    add_action( 'woocommerce_process_product_meta', 'woo_save_festival_times' );

    // location
    add_action( 'woocommerce_product_options_general_product_data', 'woo_display_locations' );
    add_action( 'woocommerce_process_product_meta', 'woo_save_locations' );

    // lockers
    add_action( 'woocommerce_product_options_general_product_data', 'woo_display_lockers' );
    add_action( 'woocommerce_process_product_meta', 'woo_save_lockers' );

    // populate attributes
    add_action('woocommerce_product_options_general_product_data', 'woo_display_populate');
    add_action('woocommerce_process_product_meta', 'woo_callback_populate');
}