<?php

namespace ECF;

class ECF {


    /**
     * Construct
     */
    public function __construct() {}


    /**
     * Get field
     * @param $field
     * @return null
     */
    public static function get($field) {
        $fields = self::getFields();
        $property     = null;

        if ( self::has($field) ) {
            $property = $fields[$field];
        }

        return self::sanitizeField($property);
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
            foreach ( $fields as $field ) {
                array_push($sanitizedFields, self::sanitizeField($field));
            }
        }

        return $sanitizedFields;
    }
}