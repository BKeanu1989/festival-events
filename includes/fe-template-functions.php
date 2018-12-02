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

if (! function_exists('fe_template_single_title')) {
    function fe_template_single_title() {
        include_once(FESTIVAL_EVENTS_PLUGIN_PATH . 'templates/single-product/title.php');
        
    }
}

/**
 * 
 * @ hooked into woocommerce_checkout_shipping
 */
if (! function_exists('fe_checkout_template_per_product')) {
    function fe_checkout_template_per_product() {
        include_once(FESTIVAL_EVENTS_PLUGIN_PATH . 'templates/cart/user-infos.php');
    }
}

/**
 * shortcode for 'live' products
 * aka which haven't taken place yet 
 * 
 */
add_shortcode('fe_coming_festivals', 'fe_coming_festivals_shortcode');
if (!function_exists('fe_coming_festivals_shortcode')) {
    function fe_coming_festivals_shortcode() {
        global $product, $woocommerce, $woocommerce_loop;
        $columns = 4;

        $now = date('Y-m-d');
        $args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            'meta_query' => [
                [
                    'key' => '_festival_start',
                    'value' => $now,
                    'compare' => '>'
                ]
            ]
        ];

        $loop = new WP_Query($args);
        ob_start();
        woocommerce_product_loop_start();
        while($loop->have_posts()) : $loop->the_post();
            wc_get_template_part('content', 'product');
        endwhile;
        woocommerce_product_loop_end();
        woocommerce_reset_loop();
        wp_reset_postdata();
        return '<div class="woocommerce columns-'.$columns.'">'.ob_get_clean().'</div>';
    }
}

/**
 * shortcode for 'finished'/past festivals
 * aka which haven't taken place yet 
 * 
 */
add_shortcode('fe_past_festivals_this_year', 'fe_past_festivals_this_year_shortcode');
if (!function_exists('fe_past_festivals_this_year_shortcode')) {
    function fe_past_festivals_this_year_shortcode() {
        global $product, $woocommerce, $woocommerce_loop;

        $now = date('Y-m-d');
        $year = date('Y');
        $args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            'meta_query' => [
                [
                    'key' => '_festival_start',
                    'value' => $now,
                    'compare' => '<'
                ], 
                [
                    'key' => '_festival_start',
                    'value' => $year,
                    'compare' => 'LIKE'
                ]
            ]
        ];

        $loop = new WP_Query($args);
        ob_start();
        // woocommerce_product_loop_start();
        while($loop->have_posts()) : $loop->the_post();
            echo '<div class="festivals__past--single">';
            echo get_the_post_thumbnail();
            echo '<p class="festivals__past--single-title">'.get_the_title().'</p>';
            echo '</div>';

        endwhile;
        wp_reset_postdata();
        return '<div class="festivals__past">'.ob_get_clean().'</div>';
    }
}


// add_filter( 'woocommerce_is_purchasable', 'fe_woocommerce_is_purchasable' );
add_filter( 'woocommerce_variation_is_purchasable', 'fe_woocommerce_is_purchasable' );
/**
 * Mark "Not ready to sell" products as not purchasable.
 */

function fe_woocommerce_is_purchasable($id) {
    global $product, $thepostid, $post;
    // source of truth is product cat now

    //TODO: if product has not purchasable ... return false
    $id = get_the_ID();
    $festivalStart = get_post_meta( $id, '_festival_start', true );
    $now = date('Y-m-d');

    $terms = get_the_terms($id, 'product_cat');
    $found = false;
    $slugs = array_column($terms,'slug');
    
    $black_listed_slugs = ['not-purchasable', 'nicht-erhaeltlich'];

    foreach($slugs AS $key => $value) {
        if (in_array($value, $black_listed_slugs)) {
            $found = true;
        }
    }
    
    // needs to be set after using translation plugins
    if (!$id) return true;
    return ( $found === true ? false : true );
}

add_action('fe_product_is_not_purchasable', 'fe_product_is_not_purchasable_func', 15, 0);
function fe_product_is_not_purchasable_func() 
{
    global $product;
    // $purchasable = $product->is_purchasable();
    $purchasable = fe_woocommerce_is_purchasable($product->get_id());
    if ($purchasable) return;
    echo '<p class="wrapper__title wrapper__title--warning">'. __('Dieses Produkt ist leider nicht mehr erhältlich.', 'festival-events'); '</p>';
}