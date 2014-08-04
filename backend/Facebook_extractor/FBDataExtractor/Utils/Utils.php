<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FBDataExtractor\Utils;

/**
 * Description of Utils
 *
 * @author gbaskaran
 */
class Utils {
    
public static function printTwoDimenArray($array){
    if(!is_array($array) || count($array) == 0){
        echo "\nNothing to print";
        return;
    }
    
    $keys = array_keys($array[0]);
    foreach($array as $row){
        echo "\n---------------------------------------------------------";
        foreach($keys as $key){
            echo  "\n".$key." = ".$row[$key];
        }
        echo "\n---------------------------------------------------------";
    }

    
}


public static function getValueSafelyArr($array, $keys, $default = '',
            $splitter = ',') {
        if (!is_array($array) || empty($array)) {
            return $default;
        }

        if (!is_array($keys)) {
            $keys = array_values(explode($splitter, $keys));
        }
        $refArray = $array;
        foreach ($keys as $key) {
            if (isset($refArray) && isset($refArray[$key]))
                $refArray = $refArray[$key];
            else
                return $default;
        }
        return $refArray;
    }
}
