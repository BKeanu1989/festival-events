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


function fe_set_product_atts( ) {
    if (isset($_REQUEST)) {
        $data = $_REQUEST["data"];
        $post_id = $data["ID"];
    
        $visible   = '1'; // can be: '' or '1'
        $variation = '1'; // can be: '' or '1'
    
        ## --- The code --- ##
    
        // Get all existing product attributes
        global $wpdb;
        $attributes = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}woocommerce_attribute_taxonomies" );
    
        $position   = 0;  // Auto incremented position value starting at '0'
        $data_attributes       = array(); // initialising (empty array)
    
        

        // Loop through each exiting product attribute
        foreach( $attributes as $attribute ){
            // Get the correct taxonomy for product attributes
            $taxonomy = 'pa_'.$attribute->attribute_name;
            // $taxonomy = $attribute->attribute_name;
            $attribute_id = $attribute->attribute_id;
            
            // Get all term Ids values for the current product attribute (array)
            $term_ids = get_terms(array('taxonomy' => $taxonomy, 'fields' => 'ids', 'hide_empty' => false));
    
            // Get an empty instance of the WC_Product_Attribute object
            $product_attribute = new WC_Product_Attribute();
    
            // Set the related data_attributes in the WC_Product_Attribute object
            $product_attribute->set_id( $attribute_id );
            $product_attribute->set_name( $taxonomy );
            $product_attribute->set_options( $term_ids );
            $product_attribute->set_position( $position );
            $product_attribute->set_visible( $visible );
            $product_attribute->set_variation( $variation );
    
            // Add the product WC_Product_Attribute object in the data_attributes array
            $data_attributes[$taxonomy] = $product_attribute;
    
            $position++; // Incrementing position
        }
        // Get an instance of the WC_Product object
        $product = wc_get_product( $post_id );
    
        // Set the array of WC_Product_Attribute objects in the product
        $product->set_attributes( $data_attributes );
    
        $product->save(); // Save the product
    }
}

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