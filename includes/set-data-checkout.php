<?php

function fe_add_custom_data_to_order_item( $item, $cart_item_key, $values, $order ) {
    global $_POST;
    // TODO: add server side validation
    foreach( $item as $cart_item_key => $cart_item_values ) {
        $key = (isset($cart_item_values['key'])) ? $cart_item_values['key'] : '';
        write_log($key);
        if (! isset($_POST[$key])) continue;
        $quantity = $cart_item_values['quantity'];
        for($i = 0; $i < $quantity; $i++) {
            $first_name_for_locker = $_POST[$key]['first_name'][$i];
            $last_name_for_locker = $_POST[$key]['last_name'][$i];
            $birthdate_for_locker = $_POST[$key]['birthdate'][$i];
            if ($quantity > 1) {
                $item->add_meta_data("_multiple_values_bool", 1, true);
                $item->add_meta_data("_first_name_for_locker--{$quantity}", $first_name_for_locker, true);
                $item->add_meta_data("_last_name_for_locker--{$quantity}", $last_name_for_locker, true);
                $item->add_meta_data("_birthdate_for_locker--{$quantity}", $birthdate_for_locker, true);
            } else {
                $item->add_meta_data('_first_name_for_locker', $first_name_for_locker, true);
                $item->add_meta_data('_last_name_for_locker', $last_name_for_locker, true);
                $item->add_meta_data('_birthdate_for_locker', $birthdate_for_locker, true);
            }
        }
    }
}
add_action( 'woocommerce_checkout_create_order_line_item', 'fe_add_custom_data_to_order_item', 10, 4 );