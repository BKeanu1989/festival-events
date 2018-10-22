<?php 

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    // Put your plugin code here
    add_action( 'plugins_loaded', 'fe_register_festival_product_type' );
    add_action( 'woocommerce_product_data_panels', 'fe_festival_product_options_product_tab_content');
    add_action( 'woocommerce_process_product_meta', 'fe_save_festival_product_options_field' );
    add_action( 'woocommerce_single_product_summary', 'fe_festival_product_template', 60 );

    add_filter( 'product_type_selector', 'fe_add_festival_product_type' );
    add_filter( 'woocommerce_product_data_tabs', 'festival_product_tab' );

}

function fe_register_festival_product_type() {
    class WC_Product_Festival_Product extends WC_Product {
        public function __construct( $product ) {
            $this->product_type = 'festival_product';
            parent::__construct( $product );
        }

        
        // schließfächer options
        // m, l, xl 
        // m - hv, l - hv, xl 
        
        // full festival
        // daily
        
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
    
    // array_push($tabs['general']['class'], 'show_if_festival_product');
    return $tabs;
}

function fe_festival_product_options_product_tab_content() {
    // Dont forget to change the id in the div with your target of your product tab
    ?><div id='festival_product_options' class='panel woocommerce_options_panel'><?php
        ?><div class='options_group'><?php
            woocommerce_wp_checkbox( array(
                'id' 	=> '_enable_festival_product',
                'label' => __( 'Enable Festival Produkt Product', 'wcpt' ),
            ) );
            woocommerce_wp_text_input( array(
                'id'          => '_festival_product_price',
                'label'       => __( 'Price', 'wcpt' ),
                    'placeholder' => '',
                    'desc_tip'    => 'true',
                    'description' => __( 'Enter Festival Produkt Price.', 'wcpt' ),
            ));
        ?></div>
    </div><?php
}

function fe_save_festival_product_options_field( $post_id ) {
    $enable_festival_product = isset( $_POST['_enable_festival_product'] ) ? 'yes' : 'no';
    update_post_meta( $post_id, '_enable_festival_product', $enable_festival_product );
    if ( isset( $_POST['_festival_product_price'] ) ) :
        update_post_meta( $post_id, '_festival_product_price', sanitize_text_field( $_POST['_festival_product_price'] ) );
    endif;
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