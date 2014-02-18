<?php

class Auth {

    const KEY_SESSION = 'shinigamiFk';

    /**
     * @param String $username Username
     * @param String $password Password
     * @return Boolean Comprueba
     */
    public static function login($username, $password) {
        try {
            /**
             * Code here
             */
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Destroy the user session
     * 
     * @return void
     */
    public static function logout($redirect = null) {
        Session::singleton()->__unset(self::KEY_SESSION);
        !is_null($redirect) ? Utility::redirect($redirect) : false;
    }

    /**
     * Checks if the user is authenticated.
     *
     * @param Mixed $redirect 
     * @return Boolean
     */
    public static function check() {
        $user = Session::singleton()->__get(self::KEY_SESSION);
        return (!is_null($user)) ? true : false;
    }

    /**
     * Gets the session user
     * 
     * @return Usuario|null
     */
    public static function getUser() {
        return (self::check()) ? Session::singleton()->__get(self::KEY_SESSION) : null;
    }

}
