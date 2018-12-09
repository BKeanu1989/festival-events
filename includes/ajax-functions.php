<?php

defined('ABSPATH') || exit;

function fe_set_prices() {
    if (isset($_REQUEST)) {
        write_log($_REQUEST);
        $data = $_REQUEST["data"];

        $ID = $data["ID"];
        $price = $data["price"];

        $productVariation = new WC_Product_Variation($ID);
        $productVariation->set_price($price);
        $productVariation->set_regular_price($price);

        // finally workds ...
        update_post_meta($ID, '_regular_price', $price);
        update_post_meta($ID, '_price', $price);
        write_log("ajax set prices!!!");
        write_log($productVariation);
        die;
    }
}
