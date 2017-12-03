<?php
defined('__BASE') or exit('Direct script access is prohibited');

class CWX_API {
    public $config;
    public $db;
    public $api;
    public $service;
    public $fields = array();

    /**
     * API Class Construct
     * -------------------
     * Create and load all the variable that will be inherited on the
     * child class that will extends this 
     */
    public function __construct( $api_name, $api_service ) {
        // set the api and service name on our global with the one
        // that passed on the param.
        $this->api = $api_name;
        $this->service = $api_service;

        // get the database and config class

    }

    public function Run() {
        /**
         * TODO:
         * 1. Check if the API and Service is exists on DB.
         * 2.
         * */
        if($this->__checkApi()) {
            // API is exists, all the service info already been load
            // to the variable also.
        }
        else {
            // cannot found the API, throw exception error to display
            // on the web page.
        }
    }

    /**
     * __checkApi()
     * ------------
     * check whether the API is exists or not in the DB.
     */
    private function __checkApi() {
        // TODO:
        // 1. If API exists, load a new CWX_Service class and put
        //    all the information there.
    }

    /**
     * getInfo()
     * ---------
     * this function will return current information of the API, such
     * as version number, fields available, etc.
     */
    public getInfo() {
        // TODO: return the information as JSON here
    }
}

?>