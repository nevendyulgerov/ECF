<?php

namespace ECF;

class ECF {


    /**
     * Construct
     */
    public function __construct() {}


    /**
     * Get field
     * @param $fieldName
     * @param fieldType
     * @param $id
     * @return null
     */
    public static function get($fieldName, $fieldType, $id = null) {
        $fieldData = null;

        switch ( $fieldType ) {
            case 'opt':
                $fieldData = self::getOptionField($fieldName);
                break;
            case 'cpt':
                $fieldData = self::getCptField($fieldName, $id);
                break;
            case 'tax':
                $fieldData = self::getTaxField($fieldName, $id);
                break;
            default:
                break;
        }

        return $fieldData;
    }


    /**
     * Get option field
     * @param $field
     * @return string
     */
    protected static function getOptionField($field) {
        $fields = self::getFields();
        $property     = null;

        if ( self::has($field) ) {
            $property = $fields[$field];
        }

        return self::sanitizeField($property);
    }


    /**
     * Get CPT field
     * @param $field
     * @param $postId
     * @return mixed
     */
    protected static function getCptField($field, $postId) {
        return Collection::getCustomPostField($field, $postId);
    }


    /**
     * Get taxonomy field
     * @param $field
     * @param $termId
     * @return mixed
     */
    protected static function getTaxField($field, $termId) {
        return Collection::getCustomTaxField($field, $termId);
    }


    /**
     * Has field
     * @param $field
     * @return bool
     */
    public static function has($field) {
        $fields = self::getFields();

        return isset($fields[$field]) && !empty($fields[$field]);
    }


    /**
     * Get all fields
     * @return mixed|void
     */
    public static function getAll() {
        $fields          = self::getFields();
        $sanitizedFields = self::sanitizeFields($fields);

        return $sanitizedFields;
    }


    /**
     * Get fields
     * @return mixed|void
     */
    private static function getFields() {
        return get_option('sa_options');
    }


    /**
     * Sanitize field
     * @param $field
     * @return string
     */
    private static function sanitizeField($field) {
        return stripslashes($field);
    }


    /**
     * Sanitize fields
     * @param $fields
     * @return array
     */
    private static function sanitizeFields($fields) {
        $sanitizedFields = array();

        if ( is_array($fields) && count($fields) > 0 ) {
            foreach ( $fields as $key => $field ) {
                $sanitizedFields[$key] = self::sanitizeField($field);
            }
        }

        return $sanitizedFields;
    }
}