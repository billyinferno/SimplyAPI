<?php

/**
 * Bootstap
 * --------
 * this will load all the configuration, constant, and common function
 * that needed to run the Framework.
 */

// ensure that there are no direct script access here.
defined('__BASE') or exit('Direct script access is prohibited');

// load all the neccesary function and classed needed for the application
const APP_VERSION = '0.0.1a';

// ------------------------------------------------------------------------
// first load the common constants that we need for the application
// ------------------------------------------------------------------------
if (file_exists(__COMMON.'Constants.php')) {
   require_once(__COMMON.'Constants.php');
}

// ------------------------------------------------------------------------
// second load the common function for the application
// ------------------------------------------------------------------------
if (file_exists(__COMMON.'Function.php')) {
   require_once(__COMMON.'Function.php');
}

// ------------------------------------------------------------------------
// check if the PHP version is less than 5.4
// if so put security measures for this PHP.
// ------------------------------------------------------------------------
if (! check_php_version('5.4')) {
   // set the magic quotes runtime into 0
   ini_set('magic_quotes_runtime', 0);

   // check the global variables that registered for the application
   if ((bool) ini_get('register_global')) {
      // set all the global variables that we want to protect.
      $_protected = array(
         '_SERVER',
			'_GET',
			'_POST',
			'_FILES',
			'_REQUEST',
			'_SESSION',
			'_ENV',
			'_COOKIE',
			'GLOBALS',
			'HTTP_RAW_POST_DATA',
			'system_path',
			'application_folder',
			'view_folder',
			'_protected',
			'_registered'
      );


      // get all the global variables
      $_registered = ini_get('variables_order');
      // loop through to the global data to check which variable we will initialize
      foreach (array('E' => '_ENV', 'G' => '_GET', 'P' => '_POST', 'C' => '_COOKIE', 'S' => '_SERVER') as $key => $superglobal) {
         // if key is find on the registered global variable
         if (strpos($_registered, $key) === FALSE) {
            continue;
         }
         
         // now loop for all the index key array for the global variable that we will protect
         foreach (array_keys($$superglobal) as $var) {
            // check if we already setup this variable on the global or not?
            // or whether this is exists on the protected variable list?
            // if yes no need to set the variable into NULL.
            if (isset($GLOBALS[$var]) && (! in_array($var, $_protected))) {
               $GLOBALS[$var] = NULL;
            }
         }
      }
   }
}

// ------------------------------------------------------------------------
// after that setup the error handler for the application.
// ------------------------------------------------------------------------

echo "bootstrap run";
