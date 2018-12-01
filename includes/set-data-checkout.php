<?php

function fe_add_custom_data_to_order_item( $item, $cart_item_key, $values, $order ) {
    write_log("values:");
    write_log("really never gets called at live site:");
    write_log($values);
    $posted = $_POST;

    write_log("POSTED:");
    write_log($_POST);

    // foreach( $item as $cart_item_key=>$values ) {
    //     //TODO: format date
    //     if( isset( $values[MK_DATE_OF_MESSAGE] ) ) {
    //         $item->add_meta_data( __( MK_DATE_OF_MESSAGE, 'mokka-digital-greet' ), $values[MK_DATE_OF_MESSAGE], true );
    //     }
    
    //     // TODO: truncate
    //     if( isset( $values[MK_MESSAGE] ) ) {
    //         $item->add_meta_data( __( MK_MESSAGE, 'mokka-digital-greet' ), $values[MK_MESSAGE], true );
    //     }

    //     if( isset( $values[MK_MOBIL_NUMBER] ) ) {
    //         $item->add_meta_data( __( MK_MOBIL_NUMBER, 'mokka-digital-greet' ), $values[MK_MOBIL_NUMBER], true );
    //     }
    // }
}
add_action( 'woocommerce_checkout_create_order_line_item', 'fe_add_custom_data_to_order_item', 10, 4 );