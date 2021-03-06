<?php

namespace ECF;

/**
 * Class CustomPostField
 * Controls the custom fields for custom post types
 * @package ECF
 */
class CustomPostField {


    /**
     * @property $config
     */
    protected $config = null;


    /**
     * @property null
     */
    protected $moduleConfig = null;


    /**
     * Construct
     * @param $moduleConfig
     * @param $config
     */
    public function __construct($moduleConfig, $config) {
        $this->config       = $config;
        $this->moduleConfig = $moduleConfig;
    }


    /**
     * Add custom metafields
     * @param $metafieldsData
     */
    public function addCustomMetafields($metafieldsData) {
        global $post;
        $postType   = get_post_type($post->ID);

        if ( is_array($metafieldsData) && count($metafieldsData) > 0 ) {

            foreach ( $metafieldsData as $metafieldsArr ) {

                $metafieldsPostType  = $metafieldsArr['name'];
                $metafieldsGroupName = $metafieldsArr['groupName'];
                $metafields          = $metafieldsArr['metafields'];

                $hasMultiplePostTypes = $metafieldsPostType === null;

                // TODO: Consider refactoring this branching code
                // The nested loop here slows things down, even with the 'break' optimization
                if ( $hasMultiplePostTypes ) {
                    $actualMetafieldsPostType = null;
                    $actualMetafields      = array();
                    $actualMetafieldsGroup = null;

                    foreach( $metafieldsArr as $metafield ) {
                        $currentPostType = $metafield['name'];

                        if ( $currentPostType === $postType ) {
                            $actualMetafieldsPostType = $metafield['name'];
                            $actualMetafields         = $metafield['metafields'];
                            $actualMetafieldsGroup    = $metafield['groupName'];
                            break;
                        }
                    }

                    $metafieldsPostType  = $actualMetafieldsPostType;
                    $metafieldsGroupName = $actualMetafieldsGroup;
                    $metafields          = $actualMetafields;
                }

                if ( $metafieldsPostType === $postType ) {

                    $metaboxSlug = $postType . '_' . $metafieldsGroupName;
                    $metaboxName = ucfirst($metafieldsGroupName);

                    add_meta_box($metaboxSlug, $metaboxName, array($this, 'createCustomMetafields'), $postType, 'normal', 'high', array(
                        'post_type'       => $postType,
                        'group_name'      => $metafieldsGroupName,
                        'metafields_data' => $metafields
                    ));
                    break;
                }
            }
        }
    }


    /**
     * Create custom fields
     * @param $post
     * @param $metabox
     */
    public function createCustomMetafields($post, $metabox) {
        $postId        = $post->ID;
        $metaboxData   = $metabox['args'];
        $metafieldData = $metaboxData['metafields_data'];

        $this->__createFields($postId, $metafieldData);
    }


    /**
     * Create fields
     * @param $postId
     * @param $metafields
     */
    protected function __createFields($postId, $metafields) {
        $view = new View($this->moduleConfig, $this->config);

        if ( is_array($metafields) && count($metafields) > 0 ) {
            foreach ( $metafields as $metafield ) {
                $metafieldName = $metafield['name'];

                if ( isset($metafieldName) && !empty($metafieldName) ) {

                    // set data, if metafield is 'dropdown_single' or 'dropdown_multiple'
                    if ( $metafield['type'] === 'dropdown_single' || $metafield['type'] === 'dropdown_multiple' ) {
                        $metafield['data'] = $view->getDropdownData($metafield);
                    }

                    // set metafield value
                    $metafield['value']  = get_post_meta($postId, $metafieldName, true);

                    // set module data
                    $metafield['module'] = $this->moduleConfig['module'];

                    // create field
                    Metafield::createField($metafield);

                } else {
                    $actualMetafields = $metafield;

                    if ( is_array($actualMetafields) && count($actualMetafields) > 0 ) {

                        foreach ( $actualMetafields as $actualMetafield ) {
                            $actualMetafieldName = $actualMetafield['name'];

                            // set data, if metafield is 'dropdown_single' or 'dropdown_multiple'
                            if ( $actualMetafield['type'] === 'dropdown_single' || $actualMetafield['type'] === 'dropdown_multiple' ) {
                                $actualMetafield['data'] = $view->getDropdownData($actualMetafield);
                            }

                            // set metafield value
                            $actualMetafield['value'] = get_post_meta($postId, $actualMetafieldName, true);

                            // set module data
                            $actualMetafield['module'] = $this->moduleConfig['module'];

                            // create field
                            Metafield::createField($actualMetafield);
                        }

                    }
                    break;
                }
            }
        }
    }


    /**
     * Save Custom Fields
     * @param $metafieldsData
     */
    public function saveCustomFields($metafieldsData) {
        global $post;
        $postType = get_post_type($post->ID);

        if ( is_array($metafieldsData) && count($metafieldsData) ) {
            foreach ( $metafieldsData as $metafieldsArr ) {
                $metafieldsPostType = $metafieldsArr['name'];
                $metafields         = $metafieldsArr['metafields'];
                $hasMultipleFields  = false;

                $hasMultiplePostTypes = $metafieldsPostType === null;

                // TODO: Consider refactoring this branching code
                // The nested loop here slows things down, even with the 'break' optimization
                if ( $hasMultiplePostTypes ) {
                    $actualMetafieldsPostType = null;
                    $actualMetafields         = array();

                    foreach( $metafieldsArr as $metafield ) {
                        $currentPostType = $metafield['name'];

                        if ( $currentPostType === $postType ) {
                            $actualMetafieldsPostType = $metafield['name'];
                            $actualMetafields         = $metafield['metafields'];
                            break;
                        }
                    }

                    $metafieldsPostType  = $actualMetafieldsPostType;
                    $metafields          = $actualMetafields;
                }

                if ( is_array($metafields) && count($metafields) > 0 && is_array($metafields['metafield']) ) {
                    $hasMultipleFields = isset($metafields['metafield'][0]['name']) && !empty($metafields['metafield'][0]['name']);
                }

                if ( $hasMultipleFields ) {
                    $metafields = $metafields['metafield'];
                }

                if ( $metafieldsPostType === $postType ) {
                    $this->__saveFieldsData($metafields);
                    break;
                }
            }
        }

    }


    /**
     * Save fields data
     * @param $metafields
     */
    protected function __saveFieldsData($metafields) {
        global $post, $pagenow;
        $postId      = $post->ID;
        $isValidSave = $pagenow == 'post.php' && current_user_can('edit_posts', $postId);

        if ( $isValidSave ) {
            Collection::savePostCustomFields($postId, $metafields);
        }
    }
}