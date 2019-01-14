<?php
/**
 * Locker Info
 * This pulls/prints every data which is available from the $product object
 * 
 */

defined( 'ABSPATH' ) || exit;

/**
 * 
 * @param array     | $variations
 */
function getLockers($variations) {
    // foreach lockertypes print out lockertype
    $lockers = [];
    foreach($variations AS $ikey => $variation ) {
        // foreach($variation AS $akey => $value) {
        // }
        $lockers[] = $variation['attributes']['attribute_schliessfaecher'];
    }
    
    return $lockers;
}
global $product;

$variations = $product->get_available_variations();

// foreach($variations AS $key => $variation) {
    // global $uniqueLockers;
include(FESTIVAL_EVENTS_PLUGIN_PATH . 'templates/single-product/locker-infos.php');
// }
?>