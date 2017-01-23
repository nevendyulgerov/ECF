<?php

namespace ECF;

/**
 * Class View
 * Controls the module's backend view
 * @package ECF
 */
class View {


    /**
     * @property array $moduleConfig
     */
    protected static $moduleConfig = array();


    /**
     * @property array $fieldsConfig
     */
    protected static $fieldsConfig = array();


    /**
     * @property Helper $helper
     */
    protected $helper = null;


    /**
     * @property array $data
     */
    protected $data = array();


    /**
     * @property Collection $collection
     */
    protected $collection = null;


    /**
     * Construct
     * @param $moduleConfig
     * @param $fieldsConfig
     */
    public function __construct($moduleConfig, $fieldsConfig) {
        self::$moduleConfig = $moduleConfig;
        self::$fieldsConfig = $fieldsConfig;

        $this->helper       = new Helper();
        $this->collection   = new Collection(self::$moduleConfig['module']['collection']);
    }


    /**
     * Render backend
     * @param $data
     */
    public function renderBackend($data) {
        $this->data = $data;

        $this->monitorView();
        $this->beforeView();
        $this->loadHeader();
        $this->loadMain();
        $this->loadFooter();
        $this->afterView();
    }


    /**
     * Monitor view
     */
    protected function monitorView() {
        $name = self::$fieldsConfig['themeOptions']['settings']['name'];

        if ( $this->isUpdated() ) {

            // notify for save
            $this->notify('success', array(
                'title'    => __('Data saved'),
                'subtitle' => $name . ' ' . __('data saved successfully.')
            ));
        }
    }


    /**
     * Before view
     */
    protected function beforeView() {
        ?>
        <!-- plugin-view -->
        <div class="plugin-view" data-view="index">
        <?php
    }


    /**
     * Load header
     */
    protected function loadHeader() {
        $page_index = $this->getPageIndex();
        $url        = $this->getUrl();
        $pages      = $this->getData('page', self::$fieldsConfig['themeOptions']['pages']);
        $title      = self::$fieldsConfig['themeOptions']['settings']['name'];
        ?>

        <!-- heading -->
        <div class="view-heading">

            <!-- title -->
            <div class="view-title">
                <p><?php echo __($title); ?></p>
            </div>
            <!-- /title -->

            <div class="view-component" data-view-component="navigation">
                <div class="row row-full">

                    <!-- navigation -->
                    <ul class="navigation">
                        <?php foreach ($pages as $index => $page) : ?>
                            <li class="nav-element <?php echo $page_index == $index ? 'active' : ''; ?>">
                                <div class="view-nav-element">
                                    <a href="<?php echo $url . '&' . self::$moduleConfig['module']['params']['page'] . '=' . $index; ?>" class="view-button-nav" data-index="<?php echo $index; ?>"><?php echo ucfirst($page['name']); ?></a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <!-- /navigation -->

                </div>
            </div>
        </div>
        <!-- /heading -->
        <?php
    }


    /**
     * Load main
     */
    protected function loadMain() {
        $this->beforeSection();
        $this->afterSection();
    }


    /**
     * Before section
     */
    protected function beforeSection() {
        $page     = $this->getCurrentPage();
        $data     = $this->getPageData($page);
        $sections = $this->getData('section', $data['sections']);
        $masonry  = isset($data['masonry']) && $data['masonry'] === 'true';


        $pages = self::$fieldsConfig['themeOptions']['pages'];
        $hasMultiplePages = $pages['page']['name'] === null;

        if ( $hasMultiplePages ) {
            $pages = $pages['page'];
        }

        $showSave = true;

        foreach ( $pages as $pageNode ) {
            if ( strtolower($pageNode['name']) === $page && isset($pageNode['showSave']) && $pageNode['showSave'] === 'false') {
                $showSave = false;
                break;
            }
        }
        ?>

        <!-- main -->
        <div class="view-main" id="initialzr-main-wrapper">

            <?php if ( $showSave ) : ?>

                <div class="buttons-group">
                    <a href="#" class="button-save" title="<?php echo __('Click to save your data'); ?>"><?php echo __('Save'); ?></a>
                </div>

            <?php endif; ?>

            <!-- view-page -->
            <div class="view-page" data-view-page="<?php echo $this->getCurrentPage(); ?>">

                <!-- REQUIRED: form -->
                <form class="view-form" action="" method="POST">

                    <!-- REQUIRED: insert security fields -->
                    <?php settings_fields(self::$moduleConfig['module']['collection']['optionName']); ?>

                    <!-- REQUIRED: create hidden fields for all options -->
                    <?php $this->createDataFields(); ?>

                    <!-- page name -->
                    <p class="view-page-heading"><?php echo $data['name']; ?></p>

                    <div class="row row-full initialzr-grid">
                        <?php if ( count($sections) > 0 ) : ?>
                            <?php foreach ( $sections as $section ) : ?>
                                <?php $this->loadBlock($section); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <?php if ( $masonry ) : ?>
                            <script type="text/javascript">
                                jQuery(document).ready(function($) {
                                    $('.initialzr-grid').isotope({
                                        itemSelector: '.initialzr-col',
                                        gutter: 20
                                    });
                                });
                            </script>
                        <?php endif; ?>
        <?php
    }


    /**
     * Load block
     * @param $data
     */
    protected function loadBlock($data) {
        $metafields   = $this->formatData($this->getData('metafield', $data['metafields']));
        $sectionWidth = $this->getSectionWidth($data['width']);
        $widgets      = $this->getData('widget', $data['widgets']);
        $widgets      = is_array($widgets) ? $widgets : array($widgets);
        ?>

        <div class="<?php echo $sectionWidth; ?> col-full block-col initialzr-col initialzr-section">

            <!-- view-block -->
            <div class="view-block">

                <!-- title -->
                <div class="view-block-title">
                    <p class="block-title"><?php echo $data['title']; ?></p>

                    <p class="block-subtitle"><?php echo $data['subtitle']; ?></p>
                </div>
                <!-- /title -->

                <!-- content -->
                <div class="view-block-content">
                    <?php if ( count($widgets) > 0 ) : ?>
                        <?php foreach ( $widgets as $widget ) : ?>
                            <?php $this->loadWidget($widget); ?>
                        <?php endforeach; ?>
                    <?php endif; ?>


                    <?php if ( count($metafields) > 0 ) : ?>
                        <?php foreach ( $metafields as $metafield ) : ?>
                            <?php $this->loadMetafield($metafield); ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <!-- /content -->
            </div>
            <!-- /view-block -->
        </div>
        <?php
    }


    /**
     * Get metafield value
     * @param $field
     * @param $prop
     * @return mixed
     */
    protected function getMetafieldValue($field, $prop) {
        $value = null;

        foreach ( $this->data as $data ) {
            if ( strpos($field, $data['field']) !== false ) {
                $value = $data[$prop];
            }
        }

        return $value;
    }


    /**
     * Load widget
     * @param array $data
     */
    protected function loadWidget($data) {
        Widget::createWidget($data);
    }


    /**
     * Load metafield
     * @param array $data
     */
    protected function loadMetafield($data) {

        // set data, if metafield is 'dropdown_single' or 'dropdown_multiple'
        if ( $data['type'] === 'dropdown_single' || $data['type'] === 'dropdown_multiple' ) {
            $data['data'] = $this->getDropdownData($data);
        }

        // set metafield value
        $data['value'] = $this->collection->get($data['name']);

        // set module data
        $data['module'] = self::$moduleConfig['module'];

        // set option name
        $data['option_name'] = $data['module']['collection']['optionName'];

        // create metafield
        Metafield::createField($data);
    }


    /**
     * After section
     */
    protected function afterSection() {
        ?>
                    </div>

                    <!-- REQUIRED: button-submit-hidden -->
                    <!-- This hidden button is triggered via JS, without it the form validation doesn't work -->
                    <input type="submit" class="button-submit-hidden"/>

                </form>
                <!-- /form -->

            </div>
            <!-- /view-page -->
        </div>
        <?php
    }


    /**
     * Load footer
     */
    protected function loadFooter() {
        ?>

        <!-- footer -->
        <div class="view-footer">

            <div class="view-page-part" data-view-page-part="footer">
                <div class="footer-controls">

                </div>
            </div>

        </div>
        <!-- /footer -->
    <?php
    }


    /**
     * After view
     */
    protected function afterView() {
        ?>
        </div>
        <!-- /plugin-view -->
        <?php
    }


    /**
     * Notify
     * @param $event
     * @param $data
     */
    public function notify($event, $data) {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                var $notifier = $('body').notifier;
                $notifier.init();

                $notifier.notify({
                    'type': '<?php echo $event; ?>',
                    'title': '<?php echo $data['title']; ?>',
                    'subtitle': '<?php echo $data['subtitle']; ?>'
                });
            });
        </script>
    <?php
    }


    /**
     * Is updated
     * @return bool
     */
    public function isUpdated() {
        return
            $this->helper->postParamExist('action') &&
            $this->helper->getParamExist('page') &&
            $_POST['action'] === self::$moduleConfig['module']['params']['update'] &&
            $_GET['page'] === self::$moduleConfig['module']['dir'];
    }


    /**
     * Format data
     * @param $data
     * @return array
     */
    protected function formatData($data) {
        $dataArr = array();

        if ( $data ) {
            foreach ( $data as $d ) {
                $dataArr[$d['name']] = $d;
            }
        }

        return $dataArr;
    }


    /**
     * Get page index
     * @return int
     */
    protected function getPageIndex() {
        $pageParam = self::$moduleConfig['module']['params']['page'];
        return isset($_GET[$pageParam]) ? $_GET[$pageParam] : 0;
    }


    /**
     * Get url
     * @return mixed
     */
    protected function getUrl() {
        return $this->clearUrlParams(get_site_url() . $_SERVER['REQUEST_URI']);
    }


    /**
     * Get page data
     * @param $page
     * @return null
     */
    protected function getPageData($page) {
        $pages = $this->getData('page', self::$fieldsConfig['themeOptions']['pages']);
        $data  = null;

        foreach ( $pages as $p ) {
            if ( strtolower($p['name']) === $page ) {
                $data = $p;
                break;
            }
        }

        return $data;
    }


    /**
     * Get current page
     * @return null|string
     */
    public function getCurrentPage() {
        $pages     = $this->getData('page', self::$fieldsConfig['themeOptions']['pages']);
        $pageIndex = intval($this->getPageIndex());
        $view      = null;

        foreach ( $pages as $index => $page ) {
            if ( $pageIndex === $index ) {
                $view = strtolower($page['name']);
                break;
            }
        }

        return $view;
    }


    /**
     * Clear url params
     * @param $url
     * @return mixed
     */
    public function clearUrlParams($url) {

        // Strip module params
        $url = preg_replace('/' . self::$moduleConfig['module']['params']['page'] . '=\d+/', '', $url, 1);

        // Strip update param
        $url = preg_replace('/' . self::$moduleConfig['module']['params']['update'] . '=[a-z]+/', '', $url, 1);

        // Strip special chars
        $url = preg_replace('/&+/', '', $url, 1);

        // Strip special chars and return
        return preg_replace('/&+/', '', $url, 1);
    }


    /**
     * Get data
     * @param $type
     * @param $propData
     * @return array
     */
    protected function getData($type, $propData) {
        $isSingle = false;

        switch ($type) {
            case 'page':
                $isSingle = isset($propData[$type]['name']);
                break;
            case 'section':
                $isSingle = isset($propData[$type]['title']);
                break;
            case 'metafield':
                $isSingle = isset($propData[$type]['type']);
                break;
            case 'widget':
                $isSingle = isset($propData[$type]['name']);
                break;
            default:
                break;
        }

        $data = $isSingle ? array($propData[$type]) : $propData[$type];
        return $data;
    }


    /**
     * Get dropdown data
     * @param array $args
     * @return array
     */
    public function getDropdownData($args) {
        $dataType    = isset($args['dataType']) && is_string($args['dataType']) ? $args['dataType'] : 'custom';
        $dataExists  = isset($args['data']) && !empty($args['data']);
        $dataArr     = array();

        if ( $dataType === 'post' ) {
            $postType = $dataExists ? $args['data'] : 'post';

            if ( post_type_exists($postType) ) {
                $dataArr = $this->collection->getPostData($postType);
            }

        } else if ( $dataType === 'taxonomy' ) {
            $taxType = $dataExists ? $args['data'] : '';

            if ( taxonomy_exists($taxType) ) {
                $customData = $this->collection->getTaxonomyData($taxType);

                $dataArr = array_map(function($val) {
                    return (object) array(
                        'ID'         => $val->term_id,
                        'post_title' => $val->name
                    );
                }, $customData);
            }

        } else if ( $dataType === 'custom' ) {
            $customData = $dataExists ? (strpos($args['data'], ',') !== 'false' ? explode(',', $args['data']) : array($args['data'])) : array();

            $dataArr = array_map(function($val) {
                return (object) array(
                    'ID'         => $val,
                    'post_title' => $val
                );
            }, $customData);
        }

        return $dataArr;
    }


    /**
     * Get section width
     * @param $width
     * @return null|string
     */
    protected function getSectionWidth($width) {
        $sectionConfig = null;

        switch ($width) {
            case '1/6':
                $sectionConfig = 'col-xs-12 col-sm-6 col-lg-2';
                break;
            case '2/6':
                $sectionConfig = 'col-xs-12 col-sm-6 col-lg-4';
                break;
            case '3/6':
                $sectionConfig = 'col-xs-12 col-sm-6';
                break;
            case '4/6':
                $sectionConfig = 'col-xs-12 col-sm-6 col-lg-8';
                break;
            case '5/6':
                $sectionConfig = 'col-xs-12 col-sm-6 col-lg-10';
                break;
            case '1':
                $sectionConfig = 'col-xs-12';
                break;
            default:
                $sectionConfig = 'col-xs-12';
                break;
        }

        return $sectionConfig;
    }


    /**
     * Create option fields
     */
    protected function createDataFields() {

        // Get module data
        $collection = $this->collection->getAll();
        $data       = is_array($collection) ? $collection : array();
        $optionName = self::$moduleConfig['module']['collection']['optionName'];

        // Iterate over all fields
        foreach ( $data as $name => $value ) {

            // Create hidden input
            Metafield::createField(array(
                'type'        => 'hidden',
                'name'        => $name,
                'value'       => $value,
                'option_name' => $optionName
            ));
        }
    }
}