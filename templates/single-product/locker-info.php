<?php
/**
 * Locker Info
 * This pulls/prints every data which is available from the $product object
 * 
 */


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
defined( 'ABSPATH' ) || exit;
global $product;

$variations = $product->get_available_variations();
$lockers = getLockers($variations);

$uniqueLockers = array_unique($lockers);
foreach($variations AS $key => $variation) {

    include(FESTIVAL_EVENTS_PLUGIN_PATH . 'templates/single-product/locker-single-info.php');
}
?>