<?php
/**
 * Module Name: Enhanced Custom Fields
 * Module URI: http://kenobisoft.com
 * Description: This is the module entry-point.
 * Version: 1.0.0
 * Author: KenobiSoft, Neven Dyulgerov
 * Author URI: http://kenobisoft.com
 * License: GPL2
 */

namespace ECF;


// die, if directly accessed
defined('ABSPATH') or die('Access denied!');


/**
 * Class EnhancedCustomFields
 * Entry-point class for the module
 * @package ECF
 */
class EnhancedCustomFields {

    /**
     * Construct
     */
    public function __construct() {}


    /**
     * Init
     * Entry-point initialization function
     * @param $config
     */
    public static function init($config) {

        // get main module class
        require_once('core/Initialzr.php');

        // load fields config
        $fieldsConfig = simplexml_load_file($config);

        // load module config
        $moduleConfig = simplexml_load_file(__DIR__ . '/config.xml');

        // initialize ECF
        Initialzr::getInstance(array(
            'module_config' => $moduleConfig,
            'fields_config' => $fieldsConfig
        ));
    }
}