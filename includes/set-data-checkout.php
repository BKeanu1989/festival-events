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
add_filter( 'woocommerce_checkout_fields', 'fe_add_birthday_field_checkout');
function fe_add_birthday_field_checkout( $fields ) {

    $fields['billing']['billing_birthday'] = array(
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
    $bday = $_POST['billing_birthday'];
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


add_filter( 'woocommerce_checkout_fields', 'fe_add_not_renter_fields');
function fe_add_not_renter_fields( $fields ) {
    global $woocommerce;
    $items = $woocommerce->cart->get_cart();

    foreach($items AS $key => $item) {
        $quantity = $item['quantity'];
        $variation_id = $item['variation_id'];

        for($y = 0; $y < $quantity; $y++) {
            $fields['billing']['firstname_renter_' . $y] = array(
                'label' => __('Vorname des Mieters', 'festival-events'),
                'required' => false, // client side and only validate if are you renter is no
                'priority' => $y + 2,
                'type' => 'text',
                'clear' => true,

                'class' => ['hide_if_yes', 'hide_if_default', 'extra_person_field']
            );
            $fields['billing']['lastname_renter_' . $y] = array(
                'label' => __('Nachname des Mieters', 'festival-events'),
                'required' => false, // client side and only validate if are you renter is no
                'priority' => $y + 2,
                'type' => 'text',
                'clear' => true,

                'class' => ['hide_if_yes', 'hide_if_default', 'extra_person_field']
            );
            $fields['billing']['birthday_renter_' . $y] = array(
                'label' => __('Geburtstag des Mieters', 'festival-events'),
                'required' => false, // client side and only validate if are you renter is no
                'priority' => $y + 2,
                'type' => 'date',
                'class' => ['hide_if_yes', 'hide_if_default', 'extra_person_field'],
                'clear' => true,

            );
        }
    }

    // TODO: add populate button
    return $fields;
}

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
    echo "
    <p class='form-row inline validate-required' id='renter_field' data-priority='1'>
        <label for='yes' class=''>{$are_you_renter}
            <abbr class='required' title='erforderlich'>*</abbr>
        </label>
        <span class='woocommerce-input-wrapper'>
            <input type='radio' class='input-radio ' value='yes' name='renter' id='renter_yes'>
                <label for='renter_yes' class='radio '>{$yes}</label>
            <input type='radio' class='input-radio ' value='no' name='renter' id='renter_no'>
                <label for='renter_no' class='radio '>{$no}</label>
        </span>
    </p>";
}

add_action ( 'woocommerce_checkout_billing', 'fe_populate_button');
function fe_populate_button() {

}

// save custom values here -- dont event need to ...
add_action ('woocommerce_checkout_create_order', 'fe_test_order_new', 10, 2);
function fe_test_order_new( $order, $data ) {
    // works so far - $_POST available
    // write_log($order);
    // write_log($data);
}


// validate custom values here!!!
add_action( 'woocommerce_checkout_process' , 'fe_custom_validation');
function fe_custom_validation() {
    write_log($_POST);
    // wc_add_notice( __( 'Dein Geburtstag darf nicht leer sein.', 'festival-events' ), 'error' );
}
