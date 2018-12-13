<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

require_once(FESTIVAL_EVENTS_PLUGIN_PATH . 'includes/fe-template-functions.php');
require_once(FESTIVAL_EVENTS_PLUGIN_PATH . 'includes/set-data-checkout.php');
require_once(FESTIVAL_EVENTS_PLUGIN_PATH . 'includes/ajax-functions.php');

/**
 * Check if WooCommerce is active
 **/
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    // Put your plugin code here
    add_action('woocommerce_product_data_panels', 'displayAllDataInTab');
    // add_action( 'woocommerce_process_product_meta', 'fe_save_festival_product_options_field' );

    add_filter('woocommerce_product_data_tabs', 'festival_product_tab');
    // TODO: front end only (and woocommerce page)
    
    // install ajax
    add_action('wp_ajax_fe_set_prices', 'fe_set_prices');

    add_action('wp_ajax_fe_set_product_atts', 'fe_set_product_atts');
    fe_hook_save_custom_fields();
}

function festival_product_tab($tabs)
{

    $tabs['festival_product'] = array(
        'label' => __('Festival Produkt', 'wcpt'),
        'target' => 'festival_product_options',
        'class' => array(),
    );

    array_push($tabs['festival_product']['class'], 'show_if_variable');
    return $tabs;
}

function hasProductAttributes()
{
    // if not
    // prepopulate it

}

// parent product: 57
//  needs
// _product_attributes
// a:1:{s:3:"hmm";a:6:{s:4:"name";s:3:"hmm";s:5:"value";s:9:"aaa | bbb";s:8:"position";i:1;s:10:"is_visible";i:1;s:12:"is_variation";i:1;s:11:"is_taxonomy";i:0;}}

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
/**
 * displays Festival Times (Start & End)
 * @return void
 */
function woo_display_festival_times()
{

    global $woocommerce, $thepostid;
    $enumerateDays = get_post_meta($thepostid, '_enumerate_days', true);
    echo '<div class="options_group">';

    woocommerce_wp_text_input(
        [
            'id' => '_festival_start',
            'label' => __('Festivalstart:', 'festival-events'),
            'placeholder' => '01.08.2019',
            'desc_tip' => 'true',
            'description' => __('Trage hier das Datum vom Start des Festivals ein.', 'festival-events'),
            'type' => 'date',
        ]
    );

    woocommerce_wp_text_input(
        [
            'id' => '_festival_end',
            'label' => __('Festivalende:', 'festival-events'),
            'placeholder' => '08.08.2019',
            'desc_tip' => 'true',
            'description' => __('Trage hier das Datum vom Ende des Festivals ein.', 'festival-events'),
            'type' => 'date',
        ]
    );


    woocommerce_wp_checkbox(array(
        'id' => "_enumerate_days",
        'label' => __('Einzelne Tage:', 'festival-events'),
        'description' => __('Willst du neben dem Full Festival Zeitraum auch die einzelnen Tage auflisten?', 'festival-events'),
        'value' => (!empty($enumerateDays)) ? $enumerateDays : ''
    ));

    echo '</div>';
}

function woo_save_festival_times($post_id)
{
    // global $woocommerce;
    $festivalStart = $_POST['_festival_start'];
    $festivalEnd = $_POST['_festival_end'];

    $enumerateDays = $_POST['_enumerate_days'];
    // add validation
    //
    if (!empty($festivalStart)) {
        update_post_meta($post_id, '_festival_start', esc_attr($festivalStart));
    }
    if (!empty($festivalEnd)) {
        update_post_meta($post_id, '_festival_end', esc_attr($festivalEnd));
    }

    if (!empty($enumerateDays)) {
        update_post_meta($post_id, '_enumerate_days', esc_attr($enumerateDays));
    }

    if ('yes' === $enumerateDays) {
        $days = enumerateDaysBetween($festivalStart, $festivalEnd, true);
        foreach ($days as $key => $day) {
            if (!term_exists($day, 'pa_period')) {
                wp_insert_term($day, 'pa_period');
            }
        }
    }
}

function woo_display_locations()
{
    global $woocommerce, $thepostid, $post;
    $value_string = '';

    $value = get_post_meta($thepostid, '_festival_locations', true);
    if (!empty($value)) {
        $value_string = implode($value, ',');
    }

    echo '<div class="options_group">';
    woocommerce_wp_text_input(
        [
            'id' => '_festival_locations',
            'label' => __('Standorte:', 'festival-events'),
            'placeholder' => 'Plaza, Center',
            'desc_tip' => 'true',
            'description' => __('Trage hier Kommasepariert die Standorte ein.', 'festival-events'),
            'type' => 'text',
            'value' => $value_string,
        ]
    );
    echo '</div>';
}

function woo_display_banner_text() {
    global $thepostid;

    echo '<div class="options_group">';

    woocommerce_wp_textarea_input(
        [
            'id'    => '_banner_text',
            'label' => __('Banner Text:', 'festival-events'),
            'name'  => '_banner_text'

        ]
    );
    echo '</div>';
}

function woo_save_banner_text($post_id) {
    $bannerText= $_POST['_banner_text'];
    if (!empty($bannerText)) {
        update_post_meta($post_id, '_banner_text', $bannerText);
    }
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
    if (!count($festivalLocations) == 0) {
        update_post_meta($post_id, '_festival_locations', $festivalLocations);
        foreach ($festivalLocations as $key => $location) {
            $term_exists = term_exists($location, 'pa_location');
            if ($term_exists === 0 || $term_exists === null) {
                wp_insert_term($location, 'pa_location');
            }
        }
    }
}

$lockerDescription = ["", "M", "M High-Voltage", "L", "L High-Voltage", "XL", "XL High-Voltage"];

// TODO: add oeffnungszeiten foreach locations

// --- do it as a 
function woo_display_opening_times()
{
    global $woocommerce, $post, $thepostid;
    $locations = get_post_meta($thepostid, '_festival_locations', true);
    $savedOpeningTimes = get_post_meta($thepostid, '_opening_times', true);
    // $locations = 
    if (!empty($locations)) {
        echo '<div class="options_group">';
        echo '<h2>' . __('Öffnungszeiten') . '</h2>';
        foreach($locations AS $key => $value) {
            // 
            woocommerce_wp_textarea_input(
                [
                    'id'    => '_opening_times['.$value.']',
                    'label' => __('Öffnungszeiten für '.$value.': ', 'festival-events'),
                    'name'  => '_opening_times['.$value.']',
                    'value' => (!empty($savedOpeningTimes)) ? $savedOpeningTimes[$value] : ''
    
                ]
            );
        }
        echo '</div>';
    }

}

function woo_save_opening_times($post_id) 
{
    if (isset($_POST['_opening_times'])) {
        $postedOpeningTimes = $_POST['_opening_times'];

        update_post_meta($post_id, '_opening_times', $postedOpeningTimes);
    }
}

function woo_display_lockers()
{
    // TODO: only show lockers if location is populated
    // foreach location create checkbox ...
    global $woocommerce, $thepostid, $post, $lockerDescription;

    $locations = get_post_meta($thepostid, '_festival_locations', true);
    $lockers = get_post_meta($thepostid, '_lockers', true);
    $lockersFormated = reformat_lockers($lockers);

    // if (count($lockers) == 0 || gettype($lockers) === 'string') {
    //     $lockers = setupLockers($locations);
    // }
    if (!empty($locations)) {
        echo '<div class="options_group">';
        echo '<h2>Schließfächer</h2>';
        // foreach ($lockers as $location => $lockersForLocation) {
        //     echo "<h3>{$location}</h3>";
    
        $iteratorLockerValue = 0;
        foreach ($lockersFormated as $key => $value) {
            $lockerValue = $iteratorLockerValue + 1;
            woocommerce_wp_checkbox(array(
                'id' => "_lockers[{$lockerValue}]",
                'label' => __($lockerDescription[$key], 'festival-events'),
                'description' => __('vorhanden?', 'festival-events'),
                'custom_attributes' => ['data-lockertype' => $lockerDescription[$key]],
                'value' => 'yes',
                'cbvalue' => $value,
            ));
            $iteratorLockerValue++;
        }

        echo '</div>';
    }
}

function woo_save_lockers($post_id)
{
    if (isset($_POST['_lockers'])) {
        $postedLockers = $_POST['_lockers'];

        $arrayToSave = reformat_lockers($postedLockers, $festivalLocations);

        if (!count($arrayToSave) == 0) {
            update_post_meta($post_id, '_lockers', $arrayToSave);
        }
    }
}

function setupLocations($festivalLocations)
{
    $festivalLocations_array = preg_split("/[,]+/", esc_attr($festivalLocations));
    $festivalLocations_array = array_map(function ($x) {
        return trim($x);
    }, $festivalLocations_array);
    return $festivalLocations_array;
}

function setupLockers()
{
    $lockers = [];
    $lockerOptions = ["1" => 'no', "2" => 'no', "3" => 'no', "4" => 'no', "5" => 'no', "6" => 'no'];
    // for ($iterator = 1; $iterator <= count($locations); $iterator++) {
    // for ($iterator = 0; $iterator < count($locations); $iterator++) {
    //     $lockers[$locations[$iterator]] = $lockerOptions;
    // }
    $lockers = $lockerOptions;
    return $lockers;
}

function _woo_display_populate_wrapper_begin()
{
    echo '<div class="options_group">';
}

function woo_display_populate()
{
    global $post_id, $thepostid;
    $savedLocations = get_post_meta($post_id, '_festival_locations', true);

    if (!empty($savedLocations)) {

        // echo '<div class="options_group">';

        // woocommerce_wp_checkbox(array(
        //     'id' => "_populate_attributes",
        //     'label' => __('Varianten erstellen?', 'festival-events'),
        //     'description' => __('Willst du die verschiedenen Varianten erstellen?', 'festival-events'),
        // ));

        echo '<button id="trigger_add_variations" type="button" class="button is-primary">'. __('Varianten erstellen', 'festival-events') .'</button>';


        // echo '</div>';
    } else {
        // echo '<div class="options_group">';
            echo '<p>Um Varianten erstellen zu lassen, gib mindestens einen Standort ein.</p>';
        // echo '</div>';
    }

}


/**
 * 
 * Displays input fields foreach unique locker if variations are present
 * 
 * 
 * Notes:
 *  - period__attributes: Full Festival at least. If array is bigger > 1 -> turn it into daily
 * @source: of truth:
 *  - variations
 * - 'Full Festival'
 * 
 */
function woo_display_populate_price()
{
    global $post_id, $thepostid, $wpdb;

    $productIsParent = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}posts WHERE post_parent = {$thepostid}");
    $lockers = get_post_meta($thepostid, '_lockers', true);
    
    $product = new WC_Product_Variable($thepostid);
    $locker__attributes = array_map('trim', explode(',',$product->get_attribute('pa_locker')));
    $period__attributes = array_unique(array_map(function($x) {
        if ($x === 'Full Festival') {
            return $x;
        } else {
            return 'Daily';
        }
    }, array_map('trim', explode(',',$product->get_attribute('pa_period')))));

    

    if (!empty($productIsParent)) {
        foreach($period__attributes AS $key2 => $period) {
            echo "<h3>$period</h3>";
            foreach($locker__attributes AS $key => $variation) {
                woocommerce_wp_text_input([
                    'id' => '_schließfaecher_' . strtolower(str_replace(' ', '',$variation)),
                    'label' => __('Preis für ', 'festival-events') . $variation,
                    'data_type' => 'decimal',
                    'custom_attributes' => ['data-lockertype' => $variation, 'step' => '0.01', 'min' => '0.01', 'data-period' => $period],
                    'type' => 'number',
                    'value' => '0.00'
                ]);
            }
        }
        echo '<button id="trigger_populate_prices" type="button" class="button is-primary">Füge Preise Hinzu</button>';
    } 
}

function _woo_display_populate_wrapper_end()
{
    echo '</div>';
}

function woo_callback_populate($post_id)
{
    try {
        if (isset($_POST['_populate_attributes'])) {
            if (!isset($_POST['_lockers']) && !isset($_POST['_festival_locations']))  {
                return;
                // TODO:
                // print error notice
            }
            
            $attributes = [];

            // saveProductAttributes($post_id);
            // setProductAttributes($post_id, $attributes);
            $parent_id = $post_id;
            // $posted_lockers = $_POST['_lockers'];
            // $festivalLocations = setupLocations($_POST['_festival_locations']);

            $variation_data =  array(
                'attributes' => array(
                    'lockers'  => 'M',
                    'timeframes' => 'Full Festival',
                    'locations' => 'Center'
                ),
                'sku'           => '',
                'regular_price' => '22.00',
                'sale_price'    => '',
                'stock_qty'     => 10,
            );
            // working now with saveProductAttributes (in combination)
            // create_product_variation($parent_id, $variation_data);
            // fe_auto_add_product_attributes($post_id);
        }
    } catch (Exception $e) {
        error_log(print_r($e->getMessage()));
    }
}

function fe_hook_save_custom_fields()
{

    // WORKING
    // festival times
    add_action('woocommerce_process_product_meta', 'woo_save_festival_times');

    // location
    add_action('woocommerce_process_product_meta', 'woo_save_locations');

    // lockers
    add_action('woocommerce_process_product_meta', 'woo_save_lockers');

    // populate attributes
    add_action('woocommerce_process_product_meta', 'woo_callback_populate');

    add_action('woocommerce_process_product_meta', 'woo_save_opening_times');
    add_action('woocommerce_process_product_meta', 'woo_save_banner_text');
}

/**
 * Create a product variation for a defined variable product ID.
 *
 * @since 3.0.0
 * @param int   $product_id | Post ID of the product parent variable product.
 * @param array $variation_data | The data to insert in the product.
 */

function create_product_variation($product_id, $variation_data)
{
    // Get the Variable product object (parent)
    $product = wc_get_product($product_id);

    $variation_post = array(
        'post_title' => $product->get_title(),
        'post_name' => 'product-' . $product_id . '-variation',
        'post_status' => 'publish',
        'post_parent' => $product_id,
        'post_type' => 'product_variation',
        'guid' => $product->get_permalink(),
    );

    // Creating the product variation
    $variation_id = wp_insert_post($variation_post);

    // Get an instance of the WC_Product_Variation object
    $variation = new WC_Product_Variation($variation_id);

    // Iterating through the variations attributes
    foreach ($variation_data['attributes'] as $attribute => $term_name) {

        $taxonomy = 'pa_' . $attribute; // The attribute taxonomy
        // $taxonomy = $attribute; // The attribute taxonomy

        // If taxonomy doesn't exists we create it (Thanks to Carl F. Corneil)
        if (!taxonomy_exists($taxonomy)) {
            register_taxonomy(
                $taxonomy,
                'product_variation',
                array(
                    'hierarchical' => false,
                    'label' => ucfirst($taxonomy),
                    'query_var' => true,

                    'rewrite' => array('slug' => $taxonomy), // The base slug
                )
            );
        }

        // Check if the Term name exist and if not we create it.
        if (!term_exists($term_name, $taxonomy)) {
            wp_insert_term($term_name, $taxonomy);
        }
        // Create the term

        $term_slug = get_term_by('name', $term_name, $taxonomy)->slug; // Get the term slug

        // Get the post Terms names from the parent variable product.
        $post_term_names = wp_get_post_terms($product_id, $taxonomy, array('fields' => 'names'));

        // Check if the post term exist and if not we set it in the parent variable product.
        if (!in_array($term_name, $post_term_names)) {
            wp_set_post_terms($product_id, $term_name, $taxonomy, true);
        }


        //
        $post_term_names_new = wp_get_post_terms($product_id, $taxonomy, array('fields' => 'names'));

        // if attribute is prepended with pa
        update_post_meta($variation_id, 'attribute_' . $taxonomy, $term_slug);
        // Set/save the attribute data in the product variation
        // update_post_meta($variation_id, 'attribute_' . $taxonomy, $term_slug);
        // update_post_meta($variation_id, 'attribute_' . $taxonomy, $term_name);
    }

    ## Set/save all other data

    // SKU
    if (!empty($variation_data['sku'])) {
        $variation->set_sku($variation_data['sku']);
    }

    // Prices
    if (empty($variation_data['sale_price'])) {
        $variation->set_price($variation_data['regular_price']);
    } else {
        $variation->set_price($variation_data['sale_price']);
        $variation->set_sale_price($variation_data['sale_price']);
    }
    $variation->set_regular_price($variation_data['regular_price']);

    // Stock
    if (!empty($variation_data['stock_qty'])) {
        $variation->set_stock_quantity($variation_data['stock_qty']);
        $variation->set_manage_stock(true);
        $variation->set_stock_status('');
    } else {
        $variation->set_manage_stock(false);
    }

    $variation->set_weight(''); // weight (resetting)

    $variation->save(); // Save the data
}

/**
 *  Reformat data, so it can be saved as an array
 *
 * @param   array $lockers | posted data from _lockers
 * @param   array $festivalLocations | setup lockers so a complete array can be build
 * @return  array - reformatted array
 */
function reformat_lockers($postedLockers)
{

    $defaultLockers = setupLockers();
    $arrayToSave = $defaultLockers;

    foreach ($postedLockers as $key => $value) {
        $arrayToSave[$key] = $value;
    }
    return $arrayToSave;
}

/**
 * Sets product attributes according to given array
 * 
 * @param integer   | product_id to save _product_attributes for 
 * @param array     | 
 * 
 */

function saveProductAttributes($post_id) {
    add_action( 'woocommerce_process_product_meta', 'setProductAttributes',999 );
}
function setProductAttributes($post_id) 
{
    $arrays = [];

    $lockers = $_POST['_lockers'];

    $reformatedLockers = reformat_lockers($_POST['_lockers'], setupLocations($_POST['_festival_locations']));
    $test = populateValueStringByKey($lockers);

    $locations = array_keys($reformatedLockers);
    $lockerValues = pullOutLockerDescription($reformatedLockers);
    $uniqueLockers = array_unique($lockerValues);
    $arrays["lockers"] = [];

    // $arrays["lockers"]["name"] = "Schließfächer";
    $arrays["lockers"]["name"] = "Lockers";
    $arrays["lockers"]["value"] = implode('|', $uniqueLockers);
    $arrays["lockers"]["position"] = 2;
    $arrays["lockers"]["is_visible"] = 1;
    $arrays["lockers"]["is_variation"] = 1;

    $arrays["lockers"]["is_taxonomy"] = 0;


    $arrays["timeframes"] = [];
    $arrays["timeframes"]["name"] = "Timeframes";
    // $arrays["timeframes"]["name"] = "Dauer";
    $arrays["timeframes"]["value"] = "Full Festival";
    $arrays["timeframes"]["position"] = 1;
    $arrays["timeframes"]["is_visible"] = 1;
    $arrays["timeframes"]["is_variation"] = 1;
    $arrays["timeframes"]["is_taxonomy"] = 0;

    $arrays["locations"] = [];
    // $arrays["locations"]["name"] = "Standort";
    $arrays["locations"]["name"] = "Locations";
    $arrays["locations"]["value"] = implode('|',$locations);
    $arrays["locations"]["position"] = 0;
    $arrays["locations"]["is_visible"] = 1;
    $arrays["locations"]["is_variation"] = 1;
    $arrays["locations"]["is_taxonomy"] = 0;


    $inserted_id = add_post_meta($post_id, '_product_attributes', $arrays, true);
    if (! $inserted_id) {
        update_post_meta($post_id, '_product_attributes', $arrays);
    }    
}

function displayAllDataInTab()
{
    ?>
        <div id="festival_product_options" class="panel woocommerce_options_panel">
    <?php
    woo_display_banner_text();
    woo_display_locations();
    woo_display_festival_times();
    woo_display_opening_times();
    woo_display_lockers();
    _woo_display_populate_wrapper_begin();
    woo_display_populate();
    woo_display_populate_price();
    _woo_display_populate_wrapper_end();

    ?>
        </div>
    <?php
}

/**
 * Takes values as an array and returns string with '|' separator
 * @param   array           | values
 * @param   string/null     | if key get value by key
 * @return  string          | e.g. foo | bar | baz
 */
function populateValueStringByKey($values, $key = null) {
    if (gettype($values) === 'array') {
        $valuesAsArray = array_map(function($element) {
            if (isset($key) && !empty($key)) {
                return $element[$key];
            }
            return $element;
        }, $values);
        return join("|", $valuesAsArray);
    }
}


function pullOutLockerDescription($lockers)
{
    global $lockerDescription;
    $array = [];

    foreach ($lockers as $location => $lockersForLocation) {
        foreach ($lockersForLocation as $key => $value) {
            if ($value === 'yes') {
                $array[] = $lockerDescription[$key];
            }
        }
    }

    return $array;
}

// TODO: test 'simple' variable product with only one attribute (test = foo | bar)


add_action('wp_head', 'fe_rebuild_woocommerce');

function fe_rebuild_woocommerce() {

    remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );

    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
    add_action( 'woocommerce_single_product_summary', 'fe_template_single_title', 5 );
    add_action ('woocommerce_single_product_summary', 'fe_opening_items_and_locations_html', 70);

    // woocommerce_after_shop_loop_item
    // add_action('woocommerce_after_shop_loop_item', 'fe_after_shop_loop_wrapper_close', 0);
    // remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
    // might still not work
    remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
    add_action('woocommerce_before_single_product_summary', 'fe_show_product_image', 20);

    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs' , 10);
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display' , 15);
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products' , 20);
    // * @hooked woocommerce_output_product_data_tabs - 10
    // * @hooked woocommerce_upsell_display - 15
    // * @hooked woocommerce_output_related_products - 20

    // add_action ('woocommerce_after_single_product', 'fe_opening_items_and_locations_html', 20);
    add_action ('woocommerce_after_single_product', 'fe_locker_info_html', 30);

    // include user infos template
    // do that in custom theme child template
    // add_action('woocommerce_checkout_shipping', 'fe_checkout_template_per_product', 10);
    add_action('include_custom_user_infos', 'fe_checkout_template_per_product', 10);
}


// TODO: add option lockerdescription


// $variation_data =  array(
//     'attributes' => array(
//         'size'  => 'M',
//         'color' => 'Green',
//     ),
//     'sku'           => '',
//     'regular_price' => '22.00',
//     'sale_price'    => '',
//     'stock_qty'     => 10,
// );


// wp_safeboxen_term_taxonomy
// wp insert new term
// _pa_...


// add new attribute taxonomy
// setting transient is necessary to update product attributes
// $attribute_taxonomies = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name != '' ORDER BY attribute_name ASC;" );
// set_transient( 'wc_attribute_taxonomies', $attribute_taxonomies );

// $attribute_taxonomies = array_filter( $attribute_taxonomies  ) ;


// not working?!?!??!
// function fe_auto_add_product_attributes( $post_id ) {
//     // if (isset($_POST['_populate_attributes'])) {

//         ## --- Checking --- ##
    
//         // if ( $post->post_type != 'product') return; // Only products
    
//         // // Exit if it's an autosave
//         // if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
//         //     return $post_id;
    
//         // // Exit if it's an update
//         // // if( $update )
//         // //     return $post_id;
    
//         // // Exit if user is not allowed
//         // if ( ! current_user_can( 'edit_product', $post_id ) )
//         //     return $post_id;
    
//         ## --- The Settings for your product attributes --- ##
    
//         $visible   = '1'; // can be: '' or '1'
//         $variation = '1'; // can be: '' or '1'
    
//         ## --- The code --- ##
    
//         // Get all existing product attributes
//         global $wpdb;
//         $attributes = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}woocommerce_attribute_taxonomies" );
    
//         $position   = 0;  // Auto incremented position value starting at '0'
//         $data       = array(); // initialising (empty array)
    
//         // Loop through each exiting product attribute
//         foreach( $attributes as $attribute ){
//             // Get the correct taxonomy for product attributes
//             $taxonomy = 'pa_'.$attribute->attribute_name;
//             // $taxonomy = $attribute->attribute_name;
//             $attribute_id = $attribute->attribute_id;
            
//             // Get all term Ids values for the current product attribute (array)
//             $term_ids = get_terms(array('taxonomy' => $taxonomy, 'fields' => 'ids', 'hide_empty' => false));
    
//             // Get an empty instance of the WC_Product_Attribute object
//             $product_attribute = new WC_Product_Attribute();
    
//             // Set the related data in the WC_Product_Attribute object
//             $product_attribute->set_id( $attribute_id );
//             $product_attribute->set_name( $taxonomy );
//             $product_attribute->set_options( $term_ids );
//             $product_attribute->set_position( $position );
//             $product_attribute->set_visible( $visible );
//             $product_attribute->set_variation( $variation );
    
//             // Add the product WC_Product_Attribute object in the data array
//             $data[$taxonomy] = $product_attribute;
    
//             $position++; // Incrementing position
//         }
//         // Get an instance of the WC_Product object
//         $product = wc_get_product( $post_id );
    
//         // Set the array of WC_Product_Attribute objects in the product
//         $product->set_attributes( $data );
    
//         $product->save(); // Save the product
//     // }
// }

// add_action( 'save_post', 'fe_auto_add_product_attributes_save_post', 50, 3 );
// function fe_auto_add_product_attributes_save_post( $post_id, $post, $update ) {
//     // if (isset($_POST['_populate_attributes'])) {

//         ## --- Checking --- ##
    
//         if ( $post->post_type != 'product') return; // Only products
    
//         // Exit if it's an autosave
//         if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
//             return $post_id;
    
//         // Exit if it's an update
//         // if( $update )
//         //     return $post_id;
    
//         // Exit if user is not allowed
//         if ( ! current_user_can( 'edit_product', $post_id ) )
//             return $post_id;
    
//         ## --- The Settings for your product attributes --- ##
    
//         $visible   = '1'; // can be: '' or '1'
//         $variation = '1'; // can be: '' or '1'
    
//         ## --- The code --- ##
    
//         // Get all existing product attributes
//         global $wpdb;
//         $attributes = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}woocommerce_attribute_taxonomies" );
    
//         $position   = 0;  // Auto incremented position value starting at '0'
//         $data       = array(); // initialising (empty array)
    
//         // Loop through each exiting product attribute
//         foreach( $attributes as $attribute ){
//             // Get the correct taxonomy for product attributes
//             $taxonomy = 'pa_'.$attribute->attribute_name;
//             // $taxonomy = $attribute->attribute_name;
//             $attribute_id = $attribute->attribute_id;
            
//             // Get all term Ids values for the current product attribute (array)
//             $term_ids = get_terms(array('taxonomy' => $taxonomy, 'fields' => 'ids', 'hide_empty' => false));
    
//             // Get an empty instance of the WC_Product_Attribute object
//             $product_attribute = new WC_Product_Attribute();
    
//             // Set the related data in the WC_Product_Attribute object
//             $product_attribute->set_id( $attribute_id );
//             $product_attribute->set_name( $taxonomy );
//             $product_attribute->set_options( $term_ids );
//             $product_attribute->set_position( $position );
//             $product_attribute->set_visible( $visible );
//             $product_attribute->set_variation( $variation );
    
//             // Add the product WC_Product_Attribute object in the data array
//             $data[$taxonomy] = $product_attribute;
    
//             $position++; // Incrementing position
//         }
//         // Get an instance of the WC_Product object
//         $product = wc_get_product( $post_id );
    
//         // Set the array of WC_Product_Attribute objects in the product
//         $product->set_attributes( $data );
    
//         $product->save(); // Save the product
//     // }
// }
