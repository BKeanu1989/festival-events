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
            // wc_get_template_part('content', 'product');
            // the_thumbnail();
            echo '<div class="festivals__past--single">';
            echo get_the_post_thumbnail();
            echo '<p class="festivals__past--single-title">'.get_the_title().'</p>';
            echo '</div>';

        endwhile;
        // woocommerce_product_loop_end();
        // woocommerce_reset_loop();
        wp_reset_postdata();
        return '<div class="festivals__past">'.ob_get_clean().'</div>';
    }
}


add_filter( 'woocommerce_is_purchasable', 'fe_woocommerce_set_purchasable' );
add_filter( 'woocommerce_variation_is_purchasable', 'fe_woocommerce_set_purchasable' );
/**
 * Mark "Not ready to sell" products as not purchasable.
 */
function fe_woocommerce_set_purchasable() {
    global $product, $thepostid, $post;
    $festivalStart = get_post_meta( get_the_ID(), '_festival_start', true );
    $now = date('Y-m-d');

    $productPurchasable = $now < $festivalStart;
    // return ( 'yes' === $festivalStart ? false : true );
    return ( $productPurchasable === true ? true : false );
    // return false;
}

// add_filter('fe_product_is_purchasable', 'fe_product_is_purchasable_func');
add_action('fe_product_is_purchasable', 'fe_product_is_purchasable_func', 5, 0);
function fe_product_is_purchasable_func() 
{
    global $product;
    $purchasable = $product->is_purchasable();
    if ($purchasable) return;
    // echo $purchasable;
    echo '<p class="wrapper__title wrapper__title--warning">test123</p>';
}