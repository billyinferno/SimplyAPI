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
    function &load_class( $name, $location ) {
        // create a static variable that will be used to hold
        // the class data.
        static $_classes = array();
        static $_loaded = array();

        // check if class already set on the array or not?
        if( isset($_classes[$name]) ) {
            return $_classes[$name];
        }
        else {
            // class is not yet exists, now try to load the class
            // from the location mentioned.
            //
            // but before that we need to check whether the class
            // file is exists or not?
        }
    }
}
