<?php

namespace ECF;

class ECF {


    /**
     * @property array $fieldTypes
     */
    protected static $fieldTypes = array(
        'opt' => 'option',
        'cpt' => 'customPostType',
        'tax' => 'taxonomy'
    );


    /**
     * @property array $messages
     */
    protected static $messages = array(
        'invalidFieldType' => 'Invalid field type provided for method [{method}] of class [ECF]. Supported field types are: \'opt\', \'cpt\' and \'tax\'.'
    );


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
        $fieldTypes = self::$fieldTypes;

        // check if field type exists
        if ( ! isset($fieldTypes[$fieldType]) ) {
            Notifier::notify(str_replace('{method}', 'get', self::$messages['invalidFieldType']));
            return false;
        }

        switch ( $fieldType ) {
            case 'opt':
                $fieldData = self::getOptField($fieldName);
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
    protected static function getOptField($field) {
        $fields   = self::getFields();
        $property = null;

        if ( self::has($field, 'opt') ) {
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
        return self::sanitizeField(Collection::getCustomPostField($field, $postId));
    }


    /**
     * Get taxonomy field
     * @param $field
     * @param $termId
     * @return mixed
     */
    protected static function getTaxField($field, $termId) {
        return self::sanitizeField(Collection::getCustomTaxField($field, $termId));
    }


    /**
     * Has field
     * @param $fieldName
     * @param $fieldType
     * @param $id
     * @return bool
     */
    public static function has($fieldName, $fieldType, $id = null) {
        $exists = false;
        $fieldTypes = self::$fieldTypes;

        // check if field type exists
        if ( !isset($fieldTypes[$fieldType]) ) {
            Notifier::notify(str_replace('{method}', 'has', self::$messages['invalidFieldType']));
            return false;
        }

        switch ( $fieldType ) {
            case 'opt':
                $exists = self::hasOptField($fieldName);
                break;
            case 'cpt':
                $exists = self::hasCptField($fieldName, $id);
                break;
            case 'tax':
                $exists = self::hasTaxField($fieldName, $id);
                break;
            default:
                break;
        }

        return $exists;
    }


    /**
     * Has opt field
     * @param $fieldName
     * @return bool
     */
    private static function hasOptField($fieldName) {
        $fields = self::getFields();
        return self::exists($fields[$fieldName]);
    }


    /**
     * Has cpt field
     * @param $fieldName
     * @param $postId
     * @return bool
     */
    private static function hasCptField($fieldName, $postId) {
        $field = self::getCptField($fieldName, $postId);
        return self::exists($field);
    }


    /**
     * Has tax field
     * @param $fieldName
     * @param $termId
     * @return bool
     */
    private static function hasTaxField($fieldName, $termId) {
        $field = self::getTaxField($fieldName, $termId);
        return self::exists($field);
    }

    /**
     * Get all fields
     * @return mixed|void
     */
    public static function getAllOptFields() {
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


    /**
     * Exists
     * @param $field
     * @return bool
     */
    private static function exists($field) {
        return isset($field) && !empty($field);
    }

}