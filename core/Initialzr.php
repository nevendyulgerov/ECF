<?php

namespace ECF;

/**
 * Class Initialzr
 * Main module class
 * @package ECF
 */
class Initialzr {


    /**
     * @property array $instance
     */
    protected static $instance 	 = null;


    /**
     * @property array $moduleConfig
     */
    protected $moduleConfig      = array();


    /**
     * @property array $fieldConfig
     */
    protected $fieldsConfig      = array();


    /**
     * @property Helper $helper
     */
    protected $helper 			 = null;


    /**
     * @property View $view
     */
    protected $view 			 = null;


    /**
     * @property Collection $collection
     */
    protected static $collection = null;


    /**
     * @property null $customPostField
     */
    protected $customPostField   = null;


    /**
     * Get instance of the class
     * @param $config
     * @return mixed
     */
    public static function getInstance($config) {
        $c = get_called_class();

        if ( ! isset($instance[$c]) ) {
            self::$instance[$c] = new Initialzr($config);
        }

        return self::$instance[$c];
    }


    /**
     * Construct
     * @param $args
     */
    private function __construct($args) {

        // get dependencies
        $this->getDependencies();

        $isValid = Validator::validate($args, 'config');

        if ( ! $isValid ) {
            Notifier::notify('Invalid initialization for ECF! Make sure to provide a valid config xml file at initialization.');
            return;
        }

        // get config files
        $moduleConfig = $args['module_config'];
        $fieldsConfig = $args['fields_config'];

        $this->helper 	       = new Helper();
        $this->moduleConfig    = $this->helper->xmlToArr($moduleConfig);
        $this->fieldsConfig    = $this->helper->xmlToArr($fieldsConfig);

        $this->view   	       = new View($this->moduleConfig, $this->fieldsConfig);
        self::$collection      = new Collection($this->moduleConfig['module']['collection']);
        $this->customPostField = new CustomPostField($this->fieldsConfig['postTypes']);

        $this->setup();
        $this->monitor();
    }


    /**
     * Setup
     */
    protected function setup() {

        // register collection space
        add_action('admin_init', function() {
            self::$collection->register();
        });

        // add plugin backend page
        add_action('admin_menu', function() {
            $module = $this->moduleConfig['module'];
            add_menu_page($module['name'], $module['menuName'], 'manage_options', $module['dir'], array($this, 'getBackend'), $this->fieldsConfig['themeOptions']['settings']['menuIcon']);
        });

        // enqueue plugin resources
        add_action('admin_enqueue_scripts', function() {
            $module    = $this->moduleConfig['module'];
            $scriptExt = $module['mode'] === 'production' ? '.min.js' : '.js';

            wp_enqueue_media();
            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_style($module['dir'], get_stylesheet_directory_uri() . '/modules/' . $module['dir'] . '/assets/stylesheets/style.css');
            wp_enqueue_script($module['dir'], get_stylesheet_directory_uri() . '/modules/' . $module['dir'] . '/assets/javascripts/main' . $scriptExt, array('jquery'));
            wp_enqueue_script($module['dir'] . '_googleMaps', 'http://maps.googleapis.com/maps/api/js?sensor=false');
        });

        // add custom metafields to post types
        add_action('add_meta_boxes', function() {
            $this->customPostField->addCustomMetafields($this->fieldsConfig['postTypes']);
        });

        // save custom metafields for post types
        add_action('save_post', function() {
            $this->customPostField->saveCustomFields($this->fieldsConfig['postTypes']);
        });
    }


    /**
     * Get dependencies
     */
    protected function getDependencies() {
        require_once('View.php');
        require_once('Widget.php');
        require_once('Helper.php');
        require_once('Metafield.php');
        require_once('Collection.php');
        require_once('CustomPostField.php');
        require_once('Validator.php');
        require_once('Notifier.php');
        require_once('ECF.php');
    }


    /**
     * Monitor module for updates
     */
    protected function monitor() {
        if ( $this->view->isUpdated() ) {
            $this->save();
        }
    }


    /**
     * Save module data
     */
    protected function save() {
        self::$collection->updateAll();
    }


    /**
     * Get backend
     */
    public function getBackend() {
        $data = self::$collection->getAll();
        $this->view->renderBackend($data);
    }
}