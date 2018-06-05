<?php
// ensure that there are no direct script access here.
defined('__BASE') or exit('Direct script access is prohibited');

/**
 * Class Config
 * ------------
 * This class will populate the static config data that we got from get_config()
 * function, into more OO perspective, so it will be easier to be used within
 * SimplyAPI class.
 */
class Config {
   public $config = array();

   /**
    * __construct
    * ------------
    * constructor of the config class, this will get the config static data and
    * mapped it local variable so any changes performed on the variable will also
    * affect the actual static config variable.
    */
   public function __construct() {
      // first assign the variable with config loaded from the configuration file.
      $this->config =& get_config();

      // once we got the reference of the config from the memory, then try to
      // translate the base_url that will be used through out the application.
      if (empty($this->config['base_url'])) {
         // check whether server address variable is being set?
         if (isset($_SERVER['SERVER_ADDR'])) {
            // check whether there are port number on the server address or not?
            if (strpos($_SERVER['SERVER_ADDR'], ':') !== FALSE) {
               // if port number is exists, add bracket on front of the server address.
               $server_addr = '['.$_SERVER['SERVER_ADDR'].']';
            }
            else {
               // no port number, directly move from server address.
               $server_addr = $_SERVER['SERVER_ADDR'];
            }

            // now generate the base url for the application
            $base_url = (is_https() ? 'https' : 'http').'://'.$server_addr
                        .substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], basename($_SERVER['SCRIPT_FILENAME'])));
         }
         else {
            // assume that this coming from local machine.
            $base_url = 'http://localhost/';
         }

         // after that set the base url in the config
         $this->set_item('base_url', $base_url);
      }
   }

   /**
    * get_item
    * --------
    * this method used to get the config data.
    * @param  key          string
    * @return config_value string
    */
   public function get_item($key) {
      // check whether the key is exists for the config or ?
      return isset($this->config[$key]) ? $this->config[$key] : NULL;
   }

   /**
    * set_item
    * --------
    * this method used to set new config data, or replacing existing data on the config variable.
    * @param key   string
    * @param value string
    */
   public function set_item($key, $value) {
      $this->config[$key] = $value;
   }
}
