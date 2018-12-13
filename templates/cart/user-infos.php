<?php
defined( 'ABSPATH' ) || exit;

global $woocommerce;
$items = $woocommerce->cart->get_cart();

foreach($items AS $key => $item) {
    $quantity = $item['quantity'];
    $variation_id = $item['variation_id'];
    $_product = new WC_Product_Variation($variation_id);
    $product_name = $_product->get_name();
    
    echo '<div class="woocommerce-custom-fields">';
        echo '<h3>' . __('Persönliche Daten für das/die Schließfächer', 'festival-events') . '</h3>';
        echo '<div class="woocommerce-custom-fields__field_wrapper">';
        for($i = 0; $i < $quantity; $i++) {
            echo apply_filters( 'woocommerce_cart_item_name', $product_name, $item, $key ) . '&nbsp;';
            echo wc_get_formatted_cart_item_data( $item );
            echo '<p class="form-row form-row-wide validate-required" data-priority="5">';
                echo '<label for="'.$key.'[name]['.$i.']">' . __('Name', 'festival-events') . ' <abbr class="required" title="'.__('erforderlich', 'festival-events').'">*</abbr></label>';
                echo '<span class="woocommerce-input-wrapper">';
                    echo '<input type="text" class="input-text" id="'.$key.'[name]['.$i.']" name="'.$key.'[name]['.$i.']" required>';
                echo '</span>';
            echo '</p>';
        }
        echo '</div>';
    echo '</div>';
}

