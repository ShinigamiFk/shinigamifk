<?php

/**
 * Description of validate
 *
 * @author 
 */
class Validate {

    /**
     * Validate an email address.
     * 
     * @param string $email Email
     * @return boolean
     */
    static function isEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Validate a name
     * 
     * @param string $name
     * @return boolean
     */
    static function isName($name) {
        return preg_match('/^[a-zA-ZñÑ\\s]+$/', $name);
    }

    /**
     * Validate a date
     * 
     * @param String $date
     * @return boolean
     */
    static function isDate($date) {
        $data = date_parse($date);
        return ($data['error_count'] == 0 && $data['warning_count'] == 0);
    }

}