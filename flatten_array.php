<?php
/**
 * Created by PhpStorm.
 * User: garyb
 * Date: 17/09/2015
 * Time: 11:07
 */
$array          = array();
$preserve_keys  = '';
$newArray       = array();

function flatten_array($array, $preserve_keys = 1, &$newArray = Array()) {
    foreach ($array as $key => $child) {
        if (is_array($child)) {
            $newArray =& flatten_array($child, $preserve_keys, $newArray);
        } elseif ($preserve_keys + is_string($key) > 1) {
            $newArray[$key] = $child;
        } else {
            $newArray[] = $child;
        }
    }
    return $newArray;
}