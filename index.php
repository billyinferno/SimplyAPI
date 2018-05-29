<?php

/**
 * SimplyAPI - Simple API Builder and Management
 * ---------------------------------------------
 * By : I Gede Adi Martha Ardiana Putra
 * ---------------------------------------------
 * Copyright (C) 2017 - Adi Martha
 */

// check the environment to ensure the correct error and logging method
// that we will use
define('APP_ENV', isset($_SERVER['APP_ENV']) ? strtolower($_SERVER['APP_ENV']) : 'development');
switch(APP_ENV) {
    case 'development':
        error_reporting(-1);
        ini_set('display_errors', 1);
        break;
    
    case 'test':
    case 'production':
        ini_set('display_error', 0);
        // check the PHP version to knew which error we need to log
        if (version_compare(PHP_VERSION, '5.3', '>=')) {
            error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
        }
        else {
            error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
        }
        break;
    
    default:
        header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
        echo 'Unknown environment parameter found on the server configuration.';
        exit(1); // exit error
        break;
}

/**
 * Directory Configuration
 * -----------------------
 * Locate all the directory that will be used for the SimplyAPI.
 */
    $common_path   = 'common';
    $system_path   = 'system';
    $hook_path     = 'hook';
    $user_lib_path = 'user';

// check all the directory path, whether it's already correct or not?
// if STDIN is defined then change the directory to this file directory
// so we can get the correct real path.
if (defined('STDIN')) {
    chdir(dirname(__FILE__));
}

// check if common path is a real path
if (($_temp = realpath($common_path)) !== FALSE) {
    $common_path = $_temp.DIRECTORY_SEPARATOR;
}
else {
    // ensure that there will be correct trailing slash on the back of the path
    $common_path = strtr(rtrim($common_path, '/\\'), '/\\', true).DIRECTORY_SEPARATOR;   
}

// check if system path is a real path
if (($_temp = realpath($system_path)) !== FALSE) {
    $system_path = $_temp.DIRECTORY_SEPARATOR;
}
else {
    // ensure that there will be correct trailing slash on the back of the path
    $system_path = strtr(rtrim($system_path, '/\\'), '/\\', true).DIRECTORY_SEPARATOR;   
}

// check if hook path is a real path
if (($_temp = realpath($hook_path)) !== FALSE) {
    $hook_path = $_temp.DIRECTORY_SEPARATOR;
}
else {
    // ensure that there will be correct trailing slash on the back of the path
    $hook_path = strtr(rtrim($hook_path, '/\\'), '/\\', true).DIRECTORY_SEPARATOR;   
}

// check if user lib path is a real path
if (($_temp = realpath($user_lib_path)) !== FALSE) {
    $user_lib_path = $_temp.DIRECTORY_SEPARATOR;
}
else {
    // ensure that there will be correct trailing slash on the back of the path
    $user_lib_path = strtr(rtrim($user_lib_path, '/\\'), '/\\', true).DIRECTORY_SEPARATOR;   
}

// once all directory is being setup, ensure that all the directory (except builder)
// is present, since this directory is mandatory for the application.
if (!(is_dir($common_path) &&
      is_dir($system_path) &&
      is_dir($hook_path  ) &&
      is_dir($user_lib_path))) {
    // some of the path is not directory, abort the prosess
    header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
    echo 'One of mandatory directory is not being present for the system, please ensure that your configuration is correct.';
    exit(2); // startup error
}

/**
  * Once all path is checked, we can put all the path on the global
  * global definition.
  */
define('__COMMON', $common_path);
define('__BASE',   $system_path);
define('__HOOK',   $hook_path);
define('__USERLIB',$user_lib_path);
define('__SYSTEM', $system_path);

/**
 * all startup init finished, load the bootstrap for the application
 */
if (file_exists(__COMMON.'Bootstrap.php')) {
    require_once __COMMON.'Bootstrap.php';
}
else {
    // bootstrap not exists, terminate the application
    header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
    echo 'Cannot load the bootstrap for the application.';
    exit(2); // startup error
}
