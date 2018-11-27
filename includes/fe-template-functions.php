<?php

if ( ! function_exists( 'fe_show_product_image' ) ) {

/**
 * Output the product image before the single product summary.
 */
    function fe_show_product_image() {
        // wc_get_template( 'single-product/product-image.php' );
        include_once(FESTIVAL_EVENTS_PLUGIN_PATH . 'templates/single-product/product-image.php');
    }
}

if (! function_exists( 'fe_locker_info_html') ) {
    function fe_locker_info_html() {
        // echo "HATS GEFUNZT?";
        include_once(FESTIVAL_EVENTS_PLUGIN_PATH . 'templates/single-product/locker-info.php');
    }
}

if (! function_exists( 'fe_opening_items_and_locations_html') ) {
    function fe_opening_items_and_locations_html() {
        // echo "HATS GEFUNZT?";
        include_once(FESTIVAL_EVENTS_PLUGIN_PATH . 'templates/single-product/opening-items-and-locations.php');
    }
}