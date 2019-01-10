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
    // TODO: test new way
    $array = [];
    if ($extra_person) {
        // should we use another source of truth?
        $_products = $postedData['extra_person-first_name'];
        foreach ($_products as $variation_id => $value) {
            $countProducts = count($_products[$variation_id]);
            for($i = 0; $i < $countProducts; $i++) {
                $personData = [];
                
                $firstname = $postedData['extra_person-first_name'][$variation_id][$i];
                $lastname = $postedData['extra_person-last_name'][$variation_id][$i];
                $bday = $postedData['extra_person-birthdate'][$variation_id][$i];
                $product_name = $postedData['extra_person-product_name'][$variation_id][$i];
        
                $personData["first_name"] = $firstname;
                $personData["last_name"] = $lastname;
                $personData["birthdate"] = $bday;
                $personData["product_name"] = $product_name;
                $personData["variation_id"] = $variation_id;
                $array[] = $personData;
            }
        }
    }
    if ($extra_person === false) {
        $_products = $postedData['extra_person-first_name'];

        foreach ($_products as $variation_id => $value) {
            $personData = [];
    
            $firstname = $postedData['_billing_first_name'];
            $lastname = $postedData['_billing_last_name'];
            $bday = $postedData['_billing_birthdate'];
            $product_name = $postedData['extra_person-product_name'][$variation_id][0];
    
            $personData["first_name"] = $firstname;
            $personData["last_name"] = $lastname;
            $personData["birthdate"] = $bday;
            $personData["product_name"] = $product_name;
            $personData["variation_id"] = $variation_id;
    
            $array[] = $personData;
        }

    }
    return $array;
}

function fe_validate_person_data($allPersonData) {
    for($i = 0; $i < count($allPersonData); $i++) {
        
        $personData = $allPersonData[$i];

        $firstname = $personData["first_name"];
        $lastname = $personData["last_name"];
        $bday = $personData["birthdate"];

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