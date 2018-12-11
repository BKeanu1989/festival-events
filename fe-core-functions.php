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