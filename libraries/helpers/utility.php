<?php

class Utility {

    /**
     *
     * Generates a random string.
     * 
     * @param int $long Chain Length
     * @param String $only Type of chain <br /> 
     * 		'A' => Includes numbers and characters
     * 		'N' => only numbers
     * 		'C  => only characters
     * @return String generated string
     */
    static function lotery($long = 10, $only = "A") {
        $numbers = "0123456789";
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        switch ($only) {
            case 'A':
                $data = $numbers . $chars;
                break;
            case 'N':
                $data = $numbers;
                break;
            case 'C':
            default:
                $data = $chars;
                break;
        }
        mt_srand((double) microtime() * rand(1000000, 2000000));
        $i = 0;
        $pass = "";
        while ($i != $long) {
            $rand = mt_rand() % strlen($data);
            $tmp = $data[$rand];
            $pass = $pass . $tmp;
            $data = str_replace($tmp, "", $data);
            $i++;
        }
        return strrev($pass);
    }

    /**
     * Redirect to the specified Path
     * 
     * @param type $route
     * @return null
     */
    static function redirect($route = "") {
        if (!empty($route)) {
            header('location:' . BASE_URL . $route);
        } else {
            header('location:' . BASE_URL);
        }
        return null;
    }

}