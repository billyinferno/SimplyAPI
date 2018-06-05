<?php
/**
 * Common Function for SimplyAPI
 * -----------------------------
 * This file will stored the common function of the SimplyAPI, that
 * will be used for all the basic function when processing the
 * SimplyAPI framework.
 */

defined('__BASE') or exit('Direct script access is prohibited');

if (!function_exists('check_php_version')) {
    /**
     * check_php_version
     * -----------------
     * This function will check current PHP version with the version passed
     * on the parameter.
     * 
     * @param  string version_string
     * @return boolean
     */
    function check_php_version($version) {
        // convert version into string
        $version = (string) $version;

        // return the value for the version compare function
        return version_compare(PHP_VERSION, $version, '>=');
    }
}

if (!function_exists('is_https')) {
    function is_https() {
        if (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
            return TRUE;
        }
        else {
            if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https') {
                return TRUE;
            }
            else {
                if (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off' ) {
                    return TRUE;
                }
            }
        }
    
        // other than that, return false
        return FALSE;
    }
}

if (!function_exists('get_config')) {
    /**
     * get_config
     * ----------
     * This function will get the config data, and return it as variable
     * that can be used later on the class.
     * 
     * @return array
     */
    function &get_config() {
        // create a static variable to hold all the config data
        // will be loaded from the config file, so it will not going
        // to be reloaded again, once it's already loaded.
        static $config;

        // check whether $config is empty or not? if it's not empty
        // it means that the config is already filled before.
        if (empty($config)) {
            // $config is still empty, load the config
            $config_path = __USERLIB.'config'.DIRECTORY_SEPARATOR.'Config.php';

            // load the config file
            require($config_path);

            // after load the config file, now check whether the config
            // variable is already being set or not?
            if (! isset($config) || ! is_array($config)) {
                header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
                echo 'Configuration file not found.';
                exit(EXIT_COMMON);
            }
        }

        // once finished load the $config data, we can return the
        // config variable to the caller.
        return $config;
    }
}

if (!function_exists('config_item')) {
    /**
     * config_item
     * -----------
     * This function will get the configuration data from the config file based
     * on the key passed on the parameter. In case that there are key not found
     * on the configuration file, it will return it as NULL.
     * 
     * @param  string key
     * @return string
     */
    function config_item($key) {
        static $_config;

        // check if this $_config is empty?
        // if yes, then load the configuration file.
        if (empty($_config)) {
            // we cannot pass reference to static variables, hence we need to
            // use array as the holder of the return value from get_config function
            $_config[0] =& get_config();
        }

        // now check whether the key is exist on the config data or not?
        return (isset($_config[0][$key])) ? $_config[0][$key] : NULL;
    }
}

if (!function_exists('load_class')) {
    /**
     * load_class
     * ----------
     * This function will load the specific class mentioned on the param,
     * and put it as static variable on the SimplyAPI core, in any case
     * that the class is already exists, it will return the static
     * variable, instead creating new class.
     * 
     * @param  string name
     * @param  string location
     * @param  string param
     * @return object
     */
    function &load_class($name, $location, $param = NULL) {
        // create a static variable that will be used to hold the class data.
        static $_classes = array();
        static $_loaded = array();

        // ensure that name and location is not empty or null?
        if ((!($name     === NULL || trim($name)     === "")) &&
            (!($location === NULL || trim($location) === ""))) {
            // check if the class already loaded?
            if (isset($_classes[$name])) {
                return $_classes[$name];
            }

            // class is not yet exists, now try to load the class from the location mentioned.
            // but before that we need to check whether the class file is exists or not?
            //
            // the location will be splitted with ".", so first we need to change the "." in
            // the location into the actual path of the class
            $actual_path = explode('.', $location);

            // after separate the ".", ensure that the length of the array should be at least more than 1.
            if (count($actual_path) > 1) {
                // check if the first path is system or user?
                // if yes, then change the location with the location defined on the global parameter define.
                switch ($actual_path[0]) {
                    case 'system':
                    case 'user':
                        $sys_path = array_shift($actual_path);
                        break;
                    
                    default:
                        // no need to do anything, assume that user create another custom path that not
                        // registered as mandatory application path.
                        // this is to support any CSM given by the user for their own purpose.
                        $sys_path = NULL;
                        break;
                }

                // now get the real location of the class file by joining the actual path by joining
                // all the actual path with DIRECTORY_SEPARATOR.
                $real_path = implode(DIRECTORY_SEPARATOR, $actual_path).'.php';

                // after that check whether we have $sys_path being set? if yes then add the application
                // system path on the front of the $real_path.
                if ($sys_path == "system") {
                    $real_path = __SYSTEM.$real_path;
                }
                else if($sys_path == "user") {
                    $real_path == __USERLIB.$real_path;
                }

                // once we finished setup the actual location of the class file, we can check whether the
                // file is exists or not?
                if (file_exists($real_path)) {
                    // check whether we already have this class name on the system or not?
                    if (class_exists($name, FALSE) === FALSE) {
                        // load the class
                        require_once($real_path);

                        // once loaded, then check to ensure that the class name is already exists on the
                        // system, if not then throw error.
                        if (class_exists($name, FALSE) === FALSE) {
                            // class is still not exists, even after load the file.
                            header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
                            echo 'Application class location for class: '.$name.', is not found on: '.$location.'. Please check or contact your administrator.';
                            exit(EXIT_COMMON);
                        }
                        else {
                            $_classes[$name] = isset($param) ? new $name($param) : new $name;
                            return $_classes[$name];
                        }
                    }
                }
                else {
                    // class file not exists
                    header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
                    echo 'Application class location for class: '.$name.', is missing from the directory. Please check or contact your administrator.';
                    exit(EXIT_COMMON);
                }
            }
            else {
                // we cannot load this, since this file is not on the path
                header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
                echo 'Wrong class location for class '.$name.'. Please check or contact your administrator.';
                exit(EXIT_COMMON);
            } // end of if (count($actual_path) > 1)...
        }
        else {
            // nothing to load
            header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
            echo 'Null parameter for the class name and class location. Please check or contact your administrator.';
            exit(EXIT_COMMON);
        } // end of if (not($location === NULL && trim($location) === ""))...
    }
}

if (!function_exists('show_error')) {
    /**
     * show_error
     * ----------
     * This function will display the error message to the browser
     */
    function show_error($message, $code = 500, $title = 'An error was encountered.') {
        $code = abs($code);

        // defaulted the exit code as exit error
        $exit_code = 1;

        // check whether the error code is less than 500?
        if ($code < 500) {
            // assume that this is internal error
            $code = 500;
            // and exit code is exit other
            $exit_code = EXIT_ERROR_OTHER;
        }

        $_error &= load_class('Exceptions', 'system.core.Exceptions');
        // show the error
        echo $_error->show_error($title, $message, 'general', $code);
    }
}

if (!function_exists('set_http_header')) {
    /**
     * set_http_header
     * ---------------
     * This function will set the status HTTP header for the application.
     * 
     * @param  int    status code
     * @param  string status text 
     */
    function set_http_header($code = 200, $text = '') {
        // check whether code is empty and not numeric
        if (empty($code) || !is_numeric($code)) {

        }
    }
}

if (!function_exists('_error_handler')) {
    /**
     * _error_handler
     * --------------
     * This function will handling all the error occurs on the application, so it can format and display
     * the relevant information on the browser.
     */
    function _error_handler($severity, $message, $filepath, $line) {
        // check whether this error is equal with the severity given or not?
        $is_error = (((E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR | E_USER_ERROR) & $severity) === $severity);

        // for error we need to set the HTTP header into 500 to indicate 'Internal Server Error'
        if ($is_error) {
            // set header as internal error
            set_http_header(500); 
        }
    }
}
