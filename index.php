<?php

/**
 * SimplyAPI - Simple API Builder and Management
 * ---------------------------------------------
 * By : I Gede Adi Martha Ardiana Putra
 * ---------------------------------------------
 * Copyright (C) 2017 - Adi Martha
 */

/**
 * Directory Configuration
 * -----------------------
 * Locate all the directory that will be used for the SimplyAPI.
 */
    $common_path   = 'common';
    $system_path   = 'system';
    $hook_path     = 'hook';
    $user_lib_path = 'user';
    $builder_path  = 'builder';

/**
 * TODO: check if all the PATH is okay and correct
 */

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
 * TODO:
 * 1. trap the api_name and api_service $_GET from the URL.
 * 2. load all the class needed, and create API Class with param using
 *    api_name and api_service we got.
 * 3. Run API class to check whether the api_name and api_service is
 *    exist on the system or not? 
 */

 ?>