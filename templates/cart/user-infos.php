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

        for($y = 0; $y < $quantity; $y++) {
            echo '<h3>'. ($y + 1).'</h3>';
            echo apply_filters( 'woocommerce_cart_item_name', $product_name, $item, $key ) . '&nbsp;';
            echo wc_get_formatted_cart_item_data( $item );
        }

        for($i = 0; $i < $quantity; $i++) {
            echo '<p class="form-row form-row-wide" data-priority="5">'. ($i + 1) .'</p>';
            echo '<p class="form-row form-row-wide validate-required" data-priority="5">';
                echo '<label for="'.$key.'[first_name]['.$i.']">' . __('Vorname', 'festival-events') . ' <abbr class="required" title="'.__('erforderlich', 'festival-events').'">*</abbr></label>';
                echo '<span class="woocommerce-input-wrapper">';
                    echo '<input type="text" class="input-text" id="'.$key.'[first_name]['.$i.']" first_name="'.$key.'[first_name]['.$i.']" required>';
                echo '</span>';
            echo '</p>';

            echo '<p class="form-row form-row-wide validate-required" data-priority="5">';
            echo '<label for="'.$key.'[last_name]['.$i.']">' . __('Nachname', 'festival-events') . ' <abbr class="required" title="'.__('erforderlich', 'festival-events').'">*</abbr></label>';
            echo '<span class="woocommerce-input-wrapper">';
                echo '<input type="text" class="input-text" id="'.$key.'[last_name]['.$i.']" last_name="'.$key.'[last_name]['.$i.']" required>';
            echo '</span>';
            echo '</p>';

            echo '<p class="form-row form-row-wide validate-required" data-priority="5">';
            echo '<label for="'.$key.'[birthdate]['.$i.']">' . __('Geburtstag', 'festival-events') . ' <abbr class="required" title="'.__('erforderlich', 'festival-events').'">*</abbr></label>';
            echo '<span class="woocommerce-input-wrapper">';
                echo '<input type="date" class="input-text" id="'.$key.'[birthdate]['.$i.']" birthdate="'.$key.'[birthdate]['.$i.']" placeholder="01.01.1990" required>';
            echo '</span>';
            echo '</p>';
            echo '<p class="form-row form-row-wide" data-priority="5"><button class="button" id="populate_data_for_other_lockers">'.__('Daten für weitere Schließfächer übernehmen').'</button></p>';
        }
        echo '</div>';
    echo '</div>';
}

