<?php
// still working?
add_action( 'woocommerce_checkout_create_order_line_item', 'fe_add_custom_data_to_order_item', 10, 4 );
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

/**
 * Add Confirm Email Field
 * 
 */
add_filter( 'woocommerce_checkout_fields' , 'fe_add_email_verification_field_checkout' );
function fe_add_email_verification_field_checkout( $fields ) {
    $fields['billing']['billing_email']['class'] = array('form-row-first');
    $fields['billing']['billing_email_confirm'] = array(
        'label' => __('Bestätige deine Email Adresse', 'festival-events'),
        'required' => true,
        'class' => array('form-row-last'),
        'clear' => true,
        'priority' => 999,
        'type' => 'email'
    );
    return $fields;
}

add_action( 'woocommerce_checkout_process', 'fe_matching_email_addresses');
function fe_matching_email_addresses() { 
    $email1 = $_POST['billing_email'];
    $email2 = $_POST['billing_email_confirm'];
    if ( $email2 !== $email1 ) {
        wc_add_notice( __( 'Deine Email Adressen stimmen nicht überein.', 'festival-events' ), 'error' );
    }
}

/**
 * Add Bday field
 */
add_filter( 'woocommerce_checkout_fields', 'fe_add_birthdate_field_checkout');
function fe_add_birthdate_field_checkout( $fields ) {

    $fields['billing']['billing_birthdate'] = array(
        'label' => __('Geburtstag', 'festival-events'),
        'required' => true,
        'class' => [],
        'clear' => true,
        'priority' => 25,
        'type' => 'date'
    );
    return $fields;
}

add_action( 'woocommerce_checkout_process', 'fe_validate_bday');
function fe_validate_bday() { 
    $bday = $_POST['billing_birthdate'];
    if ( empty($bday) ) {
        wc_add_notice( __( 'Dein Geburtstag darf nicht leer sein.', 'festival-events' ), 'error' );
    }
}

/**
 * 
 */

// add_filter( 'woocommerce_checkout_fields', 'fe_add_are_you_renter');
// function fe_add_are_you_renter( $fields ) {
//     $fields['billing']['renter'] = array(
//         'label' => __('Bist du Mieter des Schließfachs?', 'festival-events'),
//         'required' => true,
//         'class' => ['inline'],
//         'clear' => true,
//         'priority' => 1,
//         'type' => 'radio',
//         'options' => [
//             "yes" => __('Ja', 'festival-events'),
//             "no" => __('Nein', 'festival-events')
//         ]
//     );

//     return $fields;
// }

/**
 * Source of truth: for 'locker person'
 */
add_action( 'woocommerce_checkout_billing', 'fe_are_you_renter' );
function fe_are_you_renter() {
    // works
    global $woocommerce;
    $items = $woocommerce->cart->get_cart();
    $are_you_renter = __('Bist du Mieter des Schließfachs?', 'festival-events');
    $yes = __('Ja', 'festival-events');
    $no = __('Nein', 'festival-events');
    $required = __('erforderlich', 'festival-events');
    echo "
    <p class='form-row inline validate-required' id='renter_field' data-priority='1'>
        <label for='yes' class=''>{$are_you_renter}
            <abbr class='required' title='{$required}'>*</abbr>
        </label>
        <span class='woocommerce-input-wrapper'>
            <input type='radio' class='input-radio ' value='yes' name='renter' id='renter_yes'>
                <label for='renter_yes' class='radio '>{$yes}</label>
            <input type='radio' class='input-radio ' value='no' name='renter' id='renter_no'>
                <label for='renter_no' class='radio '>{$no}</label>
        </span>
    </p>";
}

// FIXME: client side js
add_action ( 'woocommerce_checkout_billing', 'fe_locker_person_description');
function fe_locker_person_description() {
    $locker_person_description = __("Nachfolgend werden die einzelnen Schließfächer aufgelistet, sodass du ... personendaten eintragen kannst.", 'festival-events');
    echo "
        <div class='extra_person_field hide_if_default'>
            <p>$locker_person_description</p>
        </div>
    ";
}

add_filter( 'woocommerce_checkout_billing', 'fe_add_not_renter_fields');
function fe_add_not_renter_fields(  ) {
    global $woocommerce;
    $items = $woocommerce->cart->get_cart();

    foreach($items AS $key => $item) {
        $quantity = $item['quantity'];
        $variation_id = $item['variation_id'];
        $_product = wc_get_product($variation_id);
        $product_name = $_product->get_title();
        for($y = 0; $y < $quantity; $y++) {
            // FIXME: button role - dont submit ... not role button?!?!?
            if ($quantity > 1) {
                if ($y === 1) {
                    echo "
                        <div class='hide_if_default hide_if_yes'>
                            <button role='button' type='button' id='populate_data_for_other_lockers'>populate user data</button>
                        </div>
                    ";
                }
            }
            echo "
                <div class='single_product_wrapper hide_if_yes hide_if_default extra_person_field'>
                    <p class='product_name'>$product_name</p>
                    <input type='hidden' name='extra_person-product_name[$y]' value='$product_name'>
                    <input type='text' placeholder='michaela' class='hide_if_yes hide_if_default extra_person_field' name='extra_person-first_name[$y]'>
                    <input type='text' placeholder='müller' class='hide_if_yes hide_if_default extra_person_field' name='extra_person-last_name[$y]'>
                    <input type='date' placeholder='2000-12-12' class='hide_if_yes hide_if_default extra_person_field' name='extra_person-birthdate[$y]'>
                </div>
            ";
        }
    }
}

add_action ('woocommerce_checkout_order_processed', 'fe_save_custom_fields', 10, 3);
function fe_save_custom_fields( $order_id, $posted_data, $order ) {
    $groupedPersonData = fe_groupPersonData($_POST);
    // TODO: push locker info
    update_post_meta($order_id, 'locker_person_data', $groupedPersonData);

}


// validate custom values here!!!
add_action( 'woocommerce_checkout_process' , 'fe_validate_custom_fields');
function fe_validate_custom_fields() {
    // write_log($_POST);

    if ($_POST['renter'] === 'no') {
        // validate each extra person field
        $groupedData = fe_groupPersonData($_POST);
        fe_validate_person_data($groupedData);
    }
}