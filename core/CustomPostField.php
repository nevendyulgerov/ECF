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
     * Construct
     * @param $config
     */
    public function __construct($config) {
        $this->config = $config;
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

        if ( is_array($metafields) && count($metafields) > 0 ) {
            foreach ( $metafields as $metafield ) {
                $metafieldName = $metafield['name'];

                if ( isset($metafieldName) && !empty($metafieldName) ) {

                    // set metafield value
                    $metafield['value'] = get_post_meta($postId, $metafieldName, true);

                    // use the class Metafield, as an internal
                    // worker to create the field
                    Metafield::createField($metafield);

                } else {
                    $actualMetafields = $metafield;

                    if ( is_array($actualMetafields) && count($actualMetafields) > 0 ) {

                        foreach ( $actualMetafields as $actualMetafield ) {
                            $actualMetafieldName = $actualMetafield['name'];

                            // set metafield value
                            $actualMetafield['value'] = get_post_meta($postId, $actualMetafieldName, true);

                            // use the class Metafield, as an internal
                            // worker to create the field
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

            // use the class Collection, as an internal
            // worker to save the field
            Collection::savePostCustomFields($postId, $metafields);
        }
    }
}