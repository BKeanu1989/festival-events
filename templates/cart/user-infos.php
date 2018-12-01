<?php
defined( 'ABSPATH' ) || exit;

global $woocommerce;
$items = $woocommerce->cart->get_cart();
// var_dump($items);
// echo "TEST";

foreach($items AS $key => $item) {
    // var_dump($item);
    // var_dump($value);
    // write_log($item);
    $quantity = $item['quantity'];
    echo '<div class="woocommerce-custom-fields">';
        echo '<h3>' . __('Persönliche Daten für das/die Schließfächer', 'festival-events') . '</h3>';
        echo '<div class="woocommerce-custom-fields__field_wrapper">';
            for($i = 0; $i < $quantity; $i++) {
                echo '<p class="form-row form-row-wide validate-required">';
                    echo '<label for="'.$key.'[name]['.$i.']">' . __('Name', 'festival-events') . ' <abbr class="required" title="'.__('erforderlich', 'festival-events').'">*</abbr></label>';
                    echo '<span class="woocommerce-input-wrapper">';
                        echo '<input type="text" class="input-text" id="'.$key.'[name]['.$i.']" name="'.$key.'[name]['.$i.']" required>';
                    echo '</span>';
                echo '</p>';
            }
        echo '</div>';
    echo '</div>';
}

