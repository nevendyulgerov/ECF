<?php

namespace ECF;

/**
 * Class Collection
 * Controls the interactions with the database
 * @package ECF
 */
class Collection {


    /**
     * @param array $config
     */
    protected static $config = array();


    /**
     * Construct
     * @param array $config
     */
	public function __construct(array $config) {
        self::$config = $config;
	}


    /**
     * Register collection space
     */
    public static function register() {
        register_setting(self::$config['optionGroup'], self::$config['optionName']);
    }


    /**
     * Get data
     * @param string $prop
     * @return array
     */
    public static function get($prop) {
        $data = self::getAll();

        if ( isset($data[$prop]) ) {
            return $data[$prop];
        }

        return null;
    }


    /**
     * Get all collection data
     * @return array
     */
    public static function getAll() {
        return get_option(self::$config['optionName']);
    }


    /**
     * Remove all, clear collection
     */
    public static function removeAll() {
        update_option(self::$config['optionName'], array());
    }


    /**
     * Update all collection data
     */
    public static function updateAll() {
        $collection = self::$config['optionName'];
        $data = $_POST[$collection];

        update_option(self::$config['optionName'], $data);
    }


    /**
     * Get post data
     * @param $postType
     * @return mixed
     */
    public function getPostData($postType) {
        $posts = get_posts(array(
            'post_type'      => $postType,
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC'
        ));

        return $posts;
    }


    /**
     * Get taxonomy data
     * @param $taxonomy
     * @return mixed
     */
    public function getTaxonomyData($taxonomy) {
        $terms = get_terms(array(
            'taxonomy'   => $taxonomy,
            'hide_empty' => 0,
            'orderby'    => 'name',
            'order'      => 'ASC'
        ));

        return $terms;
    }


    /**
     * Save post custom fields
     * @param $postId
     * @param $metafields
     */
    public static function savePostCustomFields($postId, $metafields) {

        if ( count($metafields) > 0 ) {

            foreach ( $metafields as $metafield ) {
                $metafieldName = $metafield['name'];
                $data          = $_POST[$metafieldName];
                $oldData       = get_post_meta($postId);

                if ( empty($oldData) ) {
                    add_post_meta($postId, $metafieldName, $data, true);

                } else if ( $data !== $oldData ) {
                    update_post_meta($postId, $metafieldName, $data);

                } else if ( empty($data) ) {
                    delete_post_meta($postId, $metafieldName, $oldData);
                }
            }
        }
    }


    /**
     * Save taxonomy custom fields
     * @param $termId
     * @param $taxName
     * @param $metafields
     */
    public static function saveTaxCustomFields($termId, $taxName, $metafields) {

        if ( isset( $_POST[$taxName] ) ) {
            $termMeta = get_option("taxonomy_$termId");
            $catKeys  = array_keys($_POST[$taxName]);

            foreach ( $catKeys as $key ){
                if (isset($_POST[$taxName][$key])){
                    $termMeta[$key] = $_POST[$taxName][$key];
                }
            }

            update_option("taxonomy_$termId", $termMeta);
        }
    }
}