<?php
/**
 * Common Function for SimplyAPI
 * -----------------------------
 * This file will stored the common function of the SimplyAPI, that
 * will be used for all the basic function when processing the
 * SimplyAPI framework.
 */

defined('__BASE') or exit('Direct script access is prohibited');

/**
 * load_class
 * ----------
 * This function will load the specific class mentioned on the param,
 * and put it as static variable on the SimplyAPI core, in any case
 * that the class is already exists, it will return the static
 * variable, instead creating new class.
 */
if ( ! function_exists('load_class') ) {
    function &load_class($name, $location, $param = NULL ) {
        // create a static variable that will be used to hold
        // the class data.
        static $_classes = array();
        static $_loaded = array();

        // check if class already set on the array or not?
        if( isset($_classes[$name]) ) {
            return $_classes[$name];
        }
        else {
            // ensure that location is not empty or null?
            if ( not( $location === NULL && trim($location) === "" ) ) {
               // class is not yet exists, now try to load the class
               // from the location mentioned.
               //
               // but before that we need to check whether the class
               // file is exists or not?
               //
               // the location will be splitted with ".", so first we
               // need to change the "." in the location into the actual
               // path of the class
               $actual_path = explode('.', $location);

               // check if the first path is system or user?
               // if yes, then change the location with the location
               // defined on the global parameter define.
               switch ($actual_path[0]) {
                   case 'system':
                       $actual_path[0] = __SYSTEM;
                       break;

                   case 'user':
                       $actual_path[0] = __USER;
                       break;
                   
                   default:
                       // nothing to do
                       break;
               }

               // after that we need to get the class name. the class
               // name should be the same name as the file, hence
               // we can get the class name by get the last element
               // of the location array to get the class name.
               $class_name = array_values(array_slice($actual_path, -1))[0];

               // now we got everything we need, join everything back
               // together
            }
        }
    }
}

/**
 * get_config
 * ----------
 * This function will get the config data, and return it as variable
 * that can be used later on the class.
 */
if (!function_exists('get_config')) {
    function &get_config() {
        // create a static variable to hold all the config data
        // will be loaded from the config file, so it will not going
        // to be reloaded again, once it's already loaded.
        static $config;

        // check whether $config is empty or not? if it's not empty
        // it means that the config is already filled before.
        if (empty($config)) {
            // $config is still empty, load the config
            // TODO:
        }

        // once finished load the $config data, we can return the
        // config variable to the caller.
        return $config;
    }
}

/**
 * log_message
 * -----------
 * here we load the logger class and used it to write the log message
 * to the log file.
 */
if (!function_exists('log_message')) {
    function log_message($log_num, $log_level, $log_message) {
        // create static variable that will hold the logger class
        static $_log;

        // check if we already load the logger class or not?
        if (empty($_log)) {
            // load the logger class
            $_log =& load_class('Logger', 'system.core');
        }

        // TODO: write the log message using logger class
    }
}
