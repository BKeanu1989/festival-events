<?php
if ( ! function_exists('write_log')) {
    function write_log ( $log )  {
        if ( is_array( $log ) || is_object( $log ) ) {
            error_log( print_r( $log, true ) );
        } else {
            error_log( $log );
        }
    }
}

// if (! function_exists('fe_multi_array_flatten')) {
//     function fe_multi_array_flatten ($array) {
//         $return = array();
//         foreach ($array as $key => $value) {
//             if (is_array($value)){
//                 $return = array_merge($return, fe_multi_array_flatten($value));
//             } else {
//                 $return[$key] = $value;
//             }
//         }
    
//         return $return;
//     }
// }

if (! function_exists('fe_array_flatten')) {
    function fe_array_flatten ($array) {
        $return = array();
        array_walk_recursive($array, function ($singleArray) use (&$return) {
            $return[] = $singleArray;
        });
    
        return $return;
    }
}

if (! function_exists('fe_return_first_array')) {
    function fe_return_first_array($array) {
        $keys = array_keys($array);
        return $array[$keys[0]];
    }
}

function fe_slugify_locker($name) {
    $name = strtolower($name);
    $needleStack_Array = [" ","high-voltage"];
    $replaceStack_Array = ["","-hv"];
    $slugified_locker = str_replace($needleStack_Array, $replaceStack_Array, $name);
    return $slugified_locker;
}

function fe_groupPersonData($postedData, $extra_person = true) {
    $array = [];
    if ($extra_person) {
        $countPersons = count($postedData['extra_person-first_name']);
        for($i = 0; $i < $countPersons; $i++) {
            $personData = [];
            
            $firstname = $postedData['extra_person-first_name'][$i];
            $lastname = $postedData['extra_person-last_name'][$i];
            $bday = $postedData['extra_person-birthday'][$i];
            $product_name = $postedData['extra_person-product_name'][$i];
    
            $personData["first_name"] = $firstname;
            $personData["last_name"] = $lastname;
            $personData["birthday"] = $bday;
            $personData["product_name"] = $product_name;
            $array[] = $personData;
        }
    }
    if ($extra_person === false) {
        $personData = [];

        $firstname = $postedData['_billing_first_name'];
        $lastname = $postedData['_billing_last_name'];
        $bday = $postedData['_billing_birthday'];
        $product_name = $postedData['extra_person-product_name'][0];

        $personData["first_name"] = $firstname;
        $personData["last_name"] = $lastname;
        $personData["birthday"] = $bday;
        $personData["product_name"] = $product_name;

        $array[] = $personData;
    }
    return $array;
}

function fe_validate_person_data($allPersonData) {
    for($i = 0; $i < count($allPersonData); $i++) {
        
        $personData = $allPersonData[$i];

        $firstname = $personData["first_name"];
        $lastname = $personData["last_name"];
        $bday = $personData["birthday"];

        if (empty($firstname)) {
            wc_add_notice( __( 'Der Vorname darf nicht leer sein.', 'festival-events' ), 'error' );
        }

        if (empty($lastname)) {
            wc_add_notice( __( 'Der Nachname darf nicht leer sein.', 'festival-events' ), 'error' );
        }

        if (empty($bday)) {
            wc_add_notice( __( 'Dein Geburtstag darf nicht leer sein.', 'festival-events' ), 'error' );
        }
    }
}