<?php

function fe_add_custom_data_to_order_item( $item, $cart_item_key, $values, $order ) {
    foreach( $item as $cart_item_key => $cart_item_values ) {
        $key = (isset($cart_item_values['key'])) ? $cart_item_values['key'] : '';
        if (! isset($_POST[$key])) continue;
        $quantity = $cart_item_values['quantity'];
        for($i = 0; $i < $quantity; $i++) {
            $name_for_locker = $_POST[$key]['name'][$i];
            if ($quantity > 1) {
                $item->add_meta_data("_multiple_values_bool", 1, true);
                $item->add_meta_data("_name_for_locker--{$quantity}", $name_for_locker, true);
            } else {
                $item->add_meta_data('_name_for_locker', $name_for_locker, true);
            }
        }
    }
}
add_action( 'woocommerce_checkout_create_order_line_item', 'fe_add_custom_data_to_order_item', 10, 4 );