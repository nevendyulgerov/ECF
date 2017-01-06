<?php

namespace ECF;

class Validator {


    /**
     * Construct
     */
    public function __construct() {}


    /**
     * Validate
     * @param $args
     * @param $type
     * @return bool
     */
    public static function validate($args, $type) {
        $isValid = false;

        switch ( $type ) {
            case 'config':
                $isValid =
                    isset($args) &&
                    isset($args['fields_config']) &&
                    isset($args['module_config']) &&
                    is_object($args['fields_config']) &&
                    is_object($args['module_config']);
                break;
            default:
                break;
        }

        return $isValid;
    }

}