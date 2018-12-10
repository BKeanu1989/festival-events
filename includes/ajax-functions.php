<?php

defined('ABSPATH') || exit;

function fe_set_prices() {
    if (isset($_REQUEST)) {
        $data = $_REQUEST["data"];

        $ID = $data["ID"];
        $price = $data["price"];

        $productVariation = new WC_Product_Variation($ID);
        $productVariation->set_price($price);
        $productVariation->set_regular_price($price);

        // finally workds ...
        update_post_meta($ID, '_regular_price', $price);
        update_post_meta($ID, '_price', $price);
        die;
    }
}

/**
 * This sets the given attributes via an ajax call
 * data:
 *  @param id $post_id
 *  @param string $festivalStart
 *  @param string $festivalEnd
 *  @param bool $enumerateDays
 * 
 * @return void - reloads the page in success js callback
 */
function fe_set_product_atts( ) {
    if (isset($_REQUEST)) {
        try {
            $data = $_REQUEST["data"];
    
            $post_id = $data["ID"];
            $festivalStart = $data["FestivalStart"];
            $festivalEnd = $data["FestivalEnd"];
            $enumerateDays = $data["EnumerateDays"];
    
            $lockers = $data["Lockers"];
            $locations = $data["Locations"];
    
            $visible   = '1';
            $variation = '1';
        
        
            global $wpdb;
            $attributes = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}woocommerce_attribute_taxonomies WHERE attribute_name IN ('locker', 'period', 'location');" );
        
            list($lockerAttributes, $periodAttributes, $locationAttributes) = $attributes;


            $data_attributes       = array(); // initialising (empty array)
            setDataForAttribute($data_attributes, $lockerAttributes, $lockers);
            setDataForPeriods($data_attributes, $periodAttributes, [$festivalStart, $festivalEnd, $enumerateDays]);
            setDataForAttribute($data_attributes, $locationAttributes, $locations);

            $product = wc_get_product( $post_id );

            $product->set_attributes( $data_attributes );
        
            $saved = $product->save(); // Save the product
        } catch(Exception $e) {
            echo 'Exception: '. $e->getMessage();
            die;
        }
    }
}

/**
 * 
 * Enumerates days between start & end 
 * 
 * @param string $start (YYYY-MM-DD)
 * @param string $end (YYYY-MM-DD)
 * @param boolean $includeEnd
 * 
 * @return array
 */

function enumerateDaysBetween($start, $end, $includeEnd) {
    $enumeratedDays = [];

    $start    = (new DateTime($start));
    $end      = (new DateTime($end));
    if ($includeEnd) {
        $end->modify('+1 day');
    }
    $interval = DateInterval::createFromDateString('1 day');
    $period   = new DatePeriod($start, $interval, $end);

    
    foreach ($period as $dt) {
        $enumeratedDays[] = $dt->format("Y-m-d");
    }

    return $enumeratedDays;
}

/**
 * 
 * @param array &$data_attributes | as reference
 * @param array $attribute        | attribute row from database (woocommerce_attribute_taxonomies)
 * @param array $whiteListed          | chosen lockers & to filter from
 */
function setDataForAttribute(&$data_attributes, $attribute, $whiteListed) 
{
    // TODO: make it 'nicer'
    $position = 0;
    $visible = 1;
    $variation = 1;

    if ($attribute->attribute_name === 'pa_location') $position = 2;
    // Get the correct taxonomy for product attributes
    $taxonomy = 'pa_'.$attribute->attribute_name;
    // $taxonomy = $attribute->attribute_name;
    $attribute_id = $attribute->attribute_id;
    
    // Get all term Ids values for the current product attribute (array)
    $term_ids = get_terms(array('taxonomy' => $taxonomy, 'hide_empty' => false));
    $filteredTermIDs = array_column(array_filter($term_ids, function($id) use ($whiteListed) {
        if (in_array($id->name, $whiteListed)) {
            return $id;
        }
    }), 'term_id');

    // Get an empty instance of the WC_Product_Attribute object
    $product_attribute = new WC_Product_Attribute();

    // Set the related data_attributes in the WC_Product_Attribute object
    $product_attribute->set_id( $attribute_id );
    $product_attribute->set_name( $taxonomy );
    $product_attribute->set_options( $filteredTermIDs );
    $product_attribute->set_position( $position );
    $product_attribute->set_visible( $visible );
    $product_attribute->set_variation( $variation );

    // Add the product WC_Product_Attribute object in the data_attributes array
    $data_attributes[$taxonomy] = $product_attribute;
}

/**
 * 
 * @param array &$data_attributes | as reference
 * @param array $attribute        | attribute row from database (woocommerce_attribute_taxonomies)
 * @param array $dateInfo         | array (start,end,enumerate)
 */

function setDataForPeriods(&$data_attributes, $attribute, $dateInfo) {

    $whiteListed = [];
    $whiteListed[] = 'Full Festival';
    $visible = 1;
    $variation = 1;

    list($festivalStart, $festivalEnd, $enumerateDays) = $dateInfo;
    $position = 1;
    $taxonomy = 'pa_'.$attribute->attribute_name;
    // $taxonomy = $attribute->attribute_name;
    $attribute_id = $attribute->attribute_id;
    
    if ($enumerateDays) {
        $whiteListed = array_merge($whiteListed, enumerateDaysBetween($festivalStart, $festivalEnd, true));
    }

    // Get all term Ids values for the current product attribute (array)
    $term_ids = get_terms(array('taxonomy' => $taxonomy, 'hide_empty' => false));
    $filteredTermIDs = array_column(array_filter($term_ids, function($id) use ($whiteListed) {
        if (in_array($id->name, $whiteListed)) {
            return $id;
        }
    }), 'term_id');

    // Get an empty instance of the WC_Product_Attribute object
    $product_attribute = new WC_Product_Attribute();

    // Set the related data_attributes in the WC_Product_Attribute object
    $product_attribute->set_id( $attribute_id );
    $product_attribute->set_name( $taxonomy );
    $product_attribute->set_options( $filteredTermIDs );
    $product_attribute->set_position( $position );
    $product_attribute->set_visible( $visible );
    $product_attribute->set_variation( $variation );

    // Add the product WC_Product_Attribute object in the data_attributes array
    $data_attributes[$taxonomy] = $product_attribute;
}