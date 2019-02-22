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

    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_phone']);
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
        'type' => 'text',
        'placeholder' => '1990-02-05'
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
    $are_you_renter = __('Bist du Nutzer des Schließfachs?', 'festival-events');
    $yes = __('Ja', 'festival-events');
    $no = __('Nein', 'festival-events');
    $required = __('erforderlich', 'festival-events');
    echo "
    <div class='checkbox-required' data-identifier='$key'>
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
        </p>
    </div>";
}

// FIXME: client side js
// add_action ( 'woocommerce_checkout_billing', 'fe_locker_person_description');
// function fe_locker_person_description() {
//     $locker_person_description = __("Nachfolgend werden die einzelnen Schließfächer aufgelistet, sodass du ... personendaten eintragen kannst.", 'festival-events');
//     echo "
//         <div class='extra_person_field hide_if_default hide_if_yes'>
//             <p><i class='fa fa-info-circle' aria-hidden='true'></i>$locker_person_description</p>
//         </div>
//     ";
// }

add_filter( 'woocommerce_checkout_billing', 'fe_add_extra_persons_title');
function fe_add_extra_persons_title() {
    $extra_person_title = __('Schließfach Nutzer', 'festival-events');
    echo "<div class='extra_persons'>";
    echo "<h3 class='custom_title'>{$extra_person_title}</h3>";
}
add_filter( 'woocommerce_checkout_billing', 'fe_add_not_renter_fields');
function fe_add_not_renter_fields(  ) {
    global $woocommerce;
    $items = $woocommerce->cart->get_cart();
    $first_name_string = __('Vorname', 'festival-events');
    $last_name_string = __('Nachname', 'festival-events');
    $birthdate_string = __('Geburtstag', 'festival-events');
    $required = __('erforderlich', 'festival-events');
    
    $counter = 1;
    $lockerExtraPersonNote = __('Gib bitte hier die Daten des Schließfach-Nutzers ein: ', 'festival-events');

    $birthday_title_note = __('Gib das Datum in diesem Format ein YYYY-MM-DD');
    // <input type='date' id='extra_person-birthdate[$identifier]' placeholder='2000-12-12' class='extra_person_field input-text' name='extra_person-birthdate[$identifier]'>

    foreach($items AS $key => $item) {
        $quantity = $item['quantity'];
        $variation_id = $item['variation_id'];
        $variation = wc_get_product($variation_id);
        $product_id = wp_get_post_parent_id($variation_id);
        $product_name = $variation->get_title();


        for($y = 0; $y < $quantity; $y++) {
            $identifier = $item["data_hash"] . $y;
            $stringifiedProduct = fe_stringify_product_attr($variation);
            echo "<div class='extra_person__container'>";
                echo "<p class='product_name'>$counter. $stringifiedProduct</p>";
                fe_are_you_renter($identifier);
                echo "<div class='extra_person__wrapper hide_if_yes hide_if_default extra_person_field' data-identifier='$identifier'>";
                    echo "
                    <span class='extra_person-note'>$lockerExtraPersonNote</span>
                    <input type='hidden' name='extra_person-product_name[$identifier]' value='$product_name'>
                    <input type='hidden' name='extra_person-variation_id[$identifier]' value='$variation_id'>
                    <input type='hidden' name='extra_person-product_id[$identifier]' value='$product_id'>
                        <div class='extra_person__wrapper--input-wrapper'>
                            <div class='extra_person__wrapper--input-wrapper--group'>
                                <label for='extra_person-first_name[$identifier]' class=''>{$first_name_string}
                                    <abbr class='required' title='{$required}'>*</abbr>
                                </label>
                                <input type='text' id='extra_person-first_name[$identifier]' placeholder='' class='extra_person_field' name='extra_person-first_name[$identifier]'>
                            </div>
                            <div class='extra_person__wrapper--input-wrapper--group'>
                                <label for='extra_person-last_name[$identifier]' class=''>{$last_name_string}
                                    <abbr class='required' title='{$required}'>*</abbr>
                                </label>
                                <input type='text' id='extra_person-last_name[$identifier]' placeholder='' class='extra_person_field' name='extra_person-last_name[$identifier]'>
                            </div>
                            <div class='extra_person__wrapper--input-wrapper--group'>
                                <label for='extra_person-birthdate[$identifier]' class=''>{$birthdate_string}
                                    <abbr class='required' title='{$required}'>*</abbr>
                                </label>
                                <input type='text' id='extra_person-birthdate[$identifier]' placeholder='2000-12-12' class='extra_person_field input-text' name='extra_person-birthdate[$identifier]' pattern='(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))' title='$birthday_title_note'>
                            </div>
                        </div>
                        ";
                echo "</div>";
            echo "</div>";
            $counter++;
        }
    }
}
add_filter( 'woocommerce_checkout_billing', 'fe_add_extra_persons_title_close');
function fe_add_extra_persons_title_close() {
    echo "</div>";
}
add_action ('woocommerce_checkout_order_processed', 'fe_save_custom_fields', 10, 3);
function fe_save_custom_fields( $order_id, $posted_data, $order ) {
    $groupedPersonData = fe_groupPersonData($_POST);
    for ($i = 0; $i < count($groupedPersonData); $i++) {
        foreach($groupedPersonData[$i] AS $key => $value) {
            $meta_key = $key . '_' . $i;
            update_post_meta($order_id, $meta_key, $value);
        }
    }
    update_post_meta($order_id, '_locker_person_data', $groupedPersonData);
}


// validate custom values here!!!
add_action( 'woocommerce_checkout_process' , 'fe_validate_custom_fields');
function fe_validate_custom_fields() {
    // write_log($_POST);
    // if ($_POST['renter'] === 'no') {
        // validate each extra person field
    $groupedData = fe_groupPersonData($_POST);
    fe_validate_person_data($groupedData);
    // }
}


// add_action('woocommerce_after_order_notes', 'fe_checkout_widerrufsrecht');
add_action('woocommerce_checkout_terms_and_conditions', 'fe_checkout_widerrufsrecht');
function fe_checkout_widerrufsrecht( ) {

    echo '<div id="fe_checkout_widerruf">';
    $home = get_home_url();
    // FIXME: get widerrufsrecht page for language
    $label = __('Mit Abgabe einer Bestellung best&auml;tigen Sie, das');
    woocommerce_form_field( 'fe_checkout_widerruf', array(
    'type'          => 'checkbox',
    'class'         => array('notes'),
    'label'         => sprintf(__('Ich bin einverstanden und verlange ausdrücklich, dass Sie vor Ende der Widerrufsfrist mit der Ausführung der beauftragten Dienstleistung beginnen. Mir ist bekannt, dass ich bei vollständiger Vertragserfüllung durch Sie mein <a href="%s/agb-widerruf" target="_blank">Widerrufsrecht</a> verliere.', 'festival-events'), $home),
    'placeholder'       => __('WRB'),
    'required'         => true,
    ));
    echo '</div>'; 
}

add_action('woocommerce_checkout_process', 'fe_checkout_widerrufsrecht_valid');
function fe_checkout_widerrufsrecht_valid() {
    $home = get_home_url();
    if (!isset($_POST['fe_checkout_widerruf'])) {
        wc_add_notice(sprintf( __( 'Bitte best&auml;tige die Kenntnisnahme des <a href="%s/agb-widerruf" target="_blank">Widerrufs</a>.', 'festival-events' ), $home), 'error' );
    }
}