<?php
defined( 'ABSPATH' ) || exit;

global $woocommerce;
$items = $woocommerce->cart->get_cart();
var_dump($items);
echo "TEST";