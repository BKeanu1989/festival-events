<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Check if WooCommerce is active
 **/
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    // Put your plugin code here
    add_action('woocommerce_product_data_panels', 'displayAllDataInTab');
    // add_action( 'woocommerce_process_product_meta', 'fe_save_festival_product_options_field' );

    add_filter('woocommerce_product_data_tabs', 'festival_product_tab');

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
    global $woocommerce;

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

    echo '</div>';
}

function woo_save_festival_times($post_id)
{
    // global $woocommerce;
    $festivalStart = $_POST['_festival_start'];
    $festivalEnd = $_POST['_festival_end'];
    // add validation
    //
    if (!empty($festivalStart)) {
        update_post_meta($post_id, '_festival_start', esc_attr($festivalStart));
    }
    if (!empty($festivalEnd)) {
        update_post_meta($post_id, '_festival_end', esc_attr($festivalEnd));
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
    }
}

$lockerDescription = ["", "M", "M HV", "L", "L HV", "XL", "XL HV"];

function woo_display_lockers()
{
    // TODO: only show lockers if location is populated
    // foreach location create checkbox ...
    global $woocommerce, $thepostid, $post, $lockerDescription;

    $locations = get_post_meta($thepostid, '_festival_locations', true);
    $lockers = get_post_meta($thepostid, '_lockers', true);

    if (count($lockers) == 0 || gettype($lockers) === 'string') {
        $lockers = setupLockers($locations);
    }

    echo '<div class="options_group">';
    echo '<h2>Schließfächer</h2>';
    foreach ($lockers as $location => $lockersForLocation) {
        echo "<h3>{$location}</h3>";

        $iteratorLockerValue = 0;
        foreach ($lockersForLocation as $key => $value) {
            $lockerValue = $iteratorLockerValue + 1;
            woocommerce_wp_checkbox(array(
                'id' => "_lockers[{$location}][{$lockerValue}]",
                'label' => __($lockerDescription[$key], 'festival-events'),
                'description' => __('vorhanden?', 'festival-events'),
                'value' => 'yes',
                'cbvalue' => $value,
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

function setupLockers($locations)
{
    $lockers = [];
    $lockerOptions = ["1" => 'no', "2" => 'no', "3" => 'no', "4" => 'no', "5" => 'no', "6" => 'no'];
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
        if (isset($_POST['_populate_attributes'])) {
            $attributes = [];
            setProductAttributes($post_id, $attributes);

            $parent_id = $post_id;
            $posted_lockers = $_POST['_lockers'];
            $festivalLocations = setupLocations($_POST['_festival_locations']);

            $reformatted_lockers = reformat_lockers($posted_lockers, $festivalLocations);

            foreach ($reformatted_lockers as $location_name => $value) {
                foreach ($value as $locker_description => $value2) {
                    if ($value2 === 'yes') {
                        $variation_data = [
                            'attributes' => [
                                'location' => $location_name,
                                'locker' => $locker_description,
                            ],
                            'sku' => '',
                            'regular_price' => '5.00',
                            'sale_price' => '',
                            '_stock_status' => 'instock',
                        ];

                        create_product_variation($parent_id, $variation_data);
                    }
                }
            }
        }
    } catch (Exception $e) {
        error_log(print_r($e->getMessage()));
        echo 'Exception abgefangen: ', $e->getMessage(), "\n";

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

        // If taxonomy doesn't exists we create it (Thanks to Carl F. Corneil)
        if (!taxonomy_exists($taxonomy)) {
            register_taxonomy(
                $taxonomy,
                'product_variation',
                array(
                    'hierarchical' => false,
                    'label' => ucfirst($taxonomy),
                    'query_var' => true,
                    'rewrite' => array('slug' => '$taxonomy'), // The base slug
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

        // Set/save the attribute data in the product variation
        update_post_meta($variation_id, 'attribute_' . $taxonomy, $term_slug);
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
function reformat_lockers($postedLockers, $festivalLocations)
{

    $defaultLockers = setupLockers($festivalLocations);
    $arrayToSave = $defaultLockers;

    foreach ($postedLockers as $key => $value) {
        foreach ($value as $key2 => $value2) {
            $arrayToSave[$key][$key2] = "yes";
        }
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
function setProductAttributes($post_id, $arrays) 
{
    write_log("festival start test:");
    write_log($_POST['_festival_start']);
// $array["baz"] = [];
// $array["baz"]["name"] = "baz";
// $array["baz"]["value"] = "set | via | code";
// $array["baz"]["position"] = 1;
// $array["baz"]["is_visible"] = 1;
// $array["baz"]["is_variation"] = 1;
// $array['baz']["is_taxonomy"] = 0;

    // update_post_meta($post_id, '_product_attributes', $array);
}

function displayAllDataInTab()
{
    ?>
        <div id="festival_product_options" class="panel woocommerce_options_panel">
    <?php
    woo_display_locations();
    woo_display_festival_times();
    woo_display_lockers();
    woo_display_populate();
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
function populateValueString($values, $key = null) {
    if (gettype($values) === 'array') {
        $valuesAsArray = array_map(function($element) {
            if (isset($key) && !empty($key)) {
                return $element[$key];
            }
        }, $values);
        return join("|", $valuesAsArray);
    }
}