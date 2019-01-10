<?php
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
 * Source of truth: for 'locker person'
 */
// add_action( 'woocommerce_checkout_billing', 'fe_are_you_renter' );
function fe_are_you_renter($key = 0) {
    // works
    $are_you_renter = __('Bist du Mieter des Schließfachs?', 'festival-events');
    $yes = __('Ja', 'festival-events');
    $no = __('Nein', 'festival-events');
    $required = __('erforderlich', 'festival-events');
    echo "
    <p class='form-row inline validate-required' data-priority='1'>
        <label for='yes' class=''>{$are_you_renter}
            <abbr class='required' title='{$required}'>*</abbr>
        </label>
        <span class='woocommerce-input-wrapper'>
            <input type='radio' class='input-radio' value='yes' name='renter[$key]' id='renter_yes-[$key]' data-identifier='$key'>
                <label for='renter_yes-[$key]' class='radio'>{$yes}</label>
            <input type='radio' class='input-radio' value='no' name='renter[$key]' id='renter_no-[$key]' data-identifier='$key'>
                <label for='renter_no-[$key]' class='radio'>{$no}</label>
        </span>
    </p>";
}

// FIXME: client side js
// add_action ( 'woocommerce_checkout_billing', 'fe_locker_person_description');
function fe_locker_person_description() {
    $locker_person_description = __("Nachfolgend werden die einzelnen Schließfächer aufgelistet, sodass du ... personendaten eintragen kannst.", 'festival-events');
    echo "
        <div class='extra_person_field hide_if_default hide_if_yes'>
            <p><i class='fa fa-info-circle' aria-hidden='true'></i>$locker_person_description</p>
        </div>
    ";
}

add_filter( 'woocommerce_checkout_billing', 'fe_add_not_renter_fields');
function fe_add_not_renter_fields(  ) {
    global $woocommerce;
    $items = $woocommerce->cart->get_cart();
    $first_name_string = __('Vorname', 'festival-events');
    $last_name_string = __('Nachname', 'festival-events');
    $birthdate_string = __('Geburtstag', 'festival-events');
    $required = __('erforderlich', 'festival-events');

    foreach($items AS $key => $item) {
        $quantity = $item['quantity'];
        $variation_id = $item['variation_id'];
        $_product = wc_get_product($variation_id);
        $product_name = $_product->get_title();

        for($y = 0; $y < $quantity; $y++) {
            $identifier = $item["data_hash"] . $y;
            $stringifiedProduct = fe_stringify_product_attr($_product);
            echo "<p class='product_name'>1x $stringifiedProduct</p>";
            fe_are_you_renter($identifier);
            echo "<div class='extra_person__wrapper hide_if_yes hide_if_default extra_person_field' data-identifier='$identifier'>";
                echo "
                    <input type='hidden' name='extra_person-product_name[$variation_id][$y]' value='$product_name'>
                    <div class='extra_person__wrapper--input-wrapper'>
                        <div class='extra_person__wrapper--input-wrapper--group'>
                            <label for='extra_person-first_name[$variation_id][$y]' class=''>{$first_name_string}
                                <abbr class='required' title='{$required}'>*</abbr>
                            </label>
                            <input type='text' id='extra_person-first_name[$variation_id][$y]' placeholder='michaela' class='extra_person_field' name='extra_person-first_name[$variation_id][$y]'>
                        </div>
                        <div class='extra_person__wrapper--input-wrapper--group'>
                            <label for='extra_person-last_name[$variation_id][$y]' class=''>{$last_name_string}
                                <abbr class='required' title='{$required}'>*</abbr>
                            </label>
                            <input type='text' id='extra_person-last_name[$variation_id][$y]' placeholder='müller' class='extra_person_field' name='extra_person-last_name[$variation_id][$y]'>
                        </div>
                        <div class='extra_person__wrapper--input-wrapper--group'>
                            <label for='extra_person-birthdate[$variation_id][$y]' class=''>{$birthdate_string}
                                <abbr class='required' title='{$required}'>*</abbr>
                            </label>
                            <input type='date' id='extra_person-birthdate[$variation_id][$y]' placeholder='2000-12-12' class='extra_person_field input-text' name='extra_person-birthdate[$variation_id][$y]'>
                        </div>
                    </div>
                    ";
            echo "</div>";
        }
    }
}

add_action ('woocommerce_checkout_order_processed', 'fe_save_custom_fields', 10, 3);
function fe_save_custom_fields( $order_id, $posted_data, $order ) {
    $groupedPersonData = fe_groupPersonData($_POST);
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