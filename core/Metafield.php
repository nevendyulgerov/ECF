<?php

namespace ECF;

/**
 * Class Metafield
 * This class contains metafield creation methods
 * @package ECF
 *
 * Supported metafields:
 * - text
 * - email
 * - hidden
 * - number
 * - checkbox
 * - date
 * - file
 * - image
 * - textarea
 * - textarea_hidden
 * - dropdown_single
 * - dropdown_multiple
 * - editor
 * - map
 * - gallery
 * - plain_text
 */
class Metafield {


    /**
     * @property bool $createNonce
     */
    protected static $createNonce = false;


    /**
     * Construct
     */
    public function __construct() {}


    /**
     * Create field
     * @param $args
     * @param bool $createNonce
     */
    public static function createField($args, $createNonce = false) {
        self::$createNonce = $createNonce;

        switch ($args['type']) {
            case 'text':
                self::_createTextInput($args);
                break;
            case 'email':
                self::_createEmailInput($args);
                break;
            case 'hidden':
                self::_createHiddenInput($args);
                break;
            case 'number':
                self::_createNumberInput($args);
                break;
            case 'checkbox':
                self::_createCheckboxInput($args);
                break;
            case 'date':
                self::_createDateInput($args);
                break;
            case 'file':
                self::_createFileInput($args);
                break;
            case 'image':
                self::__createImageInput($args);
                break;
            case 'textarea':
                self::_createTextarea($args);
                break;
            case 'textarea_hidden':
                self::_createHiddenTextarea($args);
                break;
            case 'dropdown_single':
                self::_createDropdownSingle($args);
                break;
            case 'dropdown_multiple':
                self::_createDropdownMultiple($args);
                break;
            case 'editor':
                self::_createWYSIWYG($args);
                break;
            case 'map':
                self::_createMap($args);
                break;
            case 'gallery':
                self::_createGallery($args);
                break;
            case 'plain_text':
                self::_createPlainText($args);
                break;
            default:
                break;
        }
    }


    /**
     * Create text input
     * @param $args
     */
    protected static function _createTextInput($args) {
        $name        = $args['name'];
        $value       = self::sanitizeValue($args['value']);
        $fieldName   = self::getFieldName($args['option_name'], $name);
        $size        = self::getSize($args['size']);
        $required    = self::getRequired($args['required']);
        $selector    = self::getSelector($args['selector']);
        ?>

        <div class="custom-metafield <?php echo $size; ?>" data-metafield="text">
            <?php self::createLabel($args['label'], $fieldName); ?>

            <input type="text" class="metafield <?php echo $selector; ?>" id="<?php echo $fieldName; ?>" name="<?php echo $fieldName; ?>" value="<?php echo $value; ?>" <?php echo $required; ?>/>

            <?php self::createDescription($args['description']); ?>
        </div>
        <?php
    }


    /**
     * Create text input
     * @param $args
     */
    protected static function _createEmailInput($args) {
        $name        = $args['name'];
        $value       = self::sanitizeValue($args['value']);
        $fieldName   = self::getFieldName($args['option_name'], $name);
        $size        = self::getSize($args['size']);
        $required    = self::getRequired($args['required']);
        $selector    = self::getSelector($args['selector']);
        ?>

        <div class="custom-metafield <?php echo $size; ?>" data-metafield="email">
            <?php self::createLabel($args['label'], $fieldName); ?>

            <input type="email" pattern="[a-z0-9!#$%&'*+/=?^_`{|}~.-]+@[a-z0-9-]+(\.[a-z0-9-]+)*" class="metafield <?php echo $selector; ?>" id="<?php echo $fieldName; ?>" name="<?php echo $fieldName; ?>" value="<?php echo $value; ?>" <?php echo $required; ?>/>

            <?php self::createDescription($args['description']); ?>
        </div>
    <?php
    }


    /**
     * Create number input
     * @param $args
     */
    protected static function _createNumberInput($args) {
        $name        = $args['name'];
        $value       = self::sanitizeValue($args['value']);
        $fieldName   = self::getFieldName($args['option_name'], $name);
        $size        = self::getSize($args['size']);
        $required    = self::getRequired($args['required']);
        $selector    = self::getSelector($args['selector']);
        $min         = isset($args['min']) && is_numeric(floatval($args['min'])) ? $args['min'] : 0;
        $max         = isset($args['max']) && is_numeric(floatval($args['max'])) ? $args['max'] : 1000000;
        $step        = isset($args['step']) && is_numeric(floatval($args['step'])) ? $args['step'] : 1;

        ?>
        <div class="custom-metafield <?php echo $size; ?>" data-metafield="number">
            <?php self::createLabel($args['label'], $fieldName); ?>

            <input type="number" step="<?php echo $step; ?>" min="<?php echo $min; ?>" max="<?php echo $max; ?>" class="metafield <?php echo $selector; ?>" id="<?php echo $fieldName; ?>" name="<?php echo $fieldName; ?>" value="<?php echo $value; ?>" <?php echo $required; ?>/>

            <?php self::createDescription($args['description']); ?>
        </div>
        <?php
    }


    /**
     * Create hidden input
     * @param $args
     */
    protected static function _createHiddenInput($args) {
        $name        = $args['name'];
        $value       = self::sanitizeValue($args['value']);
        $fieldName   = self::getFieldName($args['option_name'], $name);
        ?>
        <div class="custom-metafield hidden" data-metafield="hidden_input">
            <input type="hidden" class="metafield" name="<?php echo $fieldName; ?>" value="<?php echo $value; ?>" />
        </div>
        <?php
    }


    /**
     * Create checkbox input
     * @param $args
     */
    protected static function _createCheckboxInput($args) {
        $name        = $args['name'];
        $value       = self::sanitizeValue($args['value']);
        $fieldName   = self::getFieldName($args['option_name'], $name);
        $size        = self::getSize($args['size']);
        $required    = self::getRequired($args['required']);
        $selector    = self::getSelector($args['selector']);
        $checked     = intval($value) === 1 ? 'checked' : '';
        ?>

        <div class="custom-metafield <?php echo $size; ?>" data-metafield="checkbox">
            <?php
            self::createLabel($args['label'], $fieldName);
            self::_createHiddenInput($args);
            ?>

            <input type="checkbox" id="<?php echo $name; ?>" class="metafield <?php echo $selector; ?>" id="<?php echo $fieldName; ?>" value="<?php echo $value; ?>" <?php echo $required; ?> <?php echo $checked; ?>/>

            <?php self::createDescription($args['description']); ?>
        </div>
        <?php
    }


    /**
     * Create date input
     * @param $args
     */
    protected static function _createDateInput($args) {
        $name        = $args['name'];
        $value       = self::sanitizeValue($args['value']);
        $fieldName   = self::getFieldName($args['option_name'], $name);
        $size        = self::getSize($args['size']);
        $required    = self::getRequired($args['required']);
        $selector    = self::getSelector($args['selector']);
        $dateFormat  = isset($args['format']) ? $args['format'] : 'dd/mm/yy';
        ?>

        <div class="custom-metafield <?php echo $size; ?>" data-metafield="date" data-format="<?php echo $dateFormat; ?>">
            <?php self::createLabel($args['label'], $fieldName); ?>

            <input type="text" class="metafield <?php echo $selector; ?>" id="<?php echo $fieldName; ?>" name="<?php echo $fieldName; ?>" value="<?php echo $value; ?>" <?php echo $required; ?>/>

            <?php self::createDescription($args['description']); ?>
        </div>
        <?php
    }


    /**
     * Create file input
     * @param $args
     */
    protected static function _createFileInput($args) {
        $name        = $args['name'];
        $value       = stripslashes($args['value']);
        $fieldName   = self::getFieldName($args['option_name'], $name);
        $size        = self::getSize($args['size']);
        $fileUrl     = null;

        if ( ! empty($value) ) {
            $data = json_decode($value, JSON_PRETTY_PRINT);
            $fileUrl = $data['url'];
        }
        ?>

        <div class="custom-metafield <?php echo $size; ?>" data-metafield="file">
            <?php self::createLabel($args['label'], $fieldName); ?>

            <a href="<?php echo !empty($value) ? $fileUrl : '#' ?>" class="button-open <?php echo !empty($value) ? '' : 'hidden' ?>" target="_blank"><?php echo __('Open file'); ?></a>

            <?php self::_createHiddenTextarea($args); ?>

            <div class="after-metafield">
                <a href="#" class="button-add"><?php echo __('Add file'); ?></a>
                <a href="#" class="button-remove <?php echo !empty($value) ? '' : 'hidden'; ?>"><?php echo __('Remove file'); ?></a>

                <?php self::createDescription($args['description']); ?>
            </div>
        </div>
        <?php
    }

    /**
     * Create image upload
     * @param $args
     */
    protected function __createImageInput($args) {
        $name        = $args['name'];
        $value       = stripslashes($args['value']);
        $fieldName   = self::getFieldName($args['option_name'], $name);
        $size        = self::getSize($args['size']);
        $imageId     = null;
        $imageUrl    = null;

        if ( ! empty($value) ) {
            $data = json_decode($value, JSON_PRETTY_PRINT);
            $imageId  = $data['id'];
            $imageUrl = $data['url'];
        }
        ?>

        <div class="custom-metafield <?php echo $size; ?>" data-metafield="image" data-image-id="<?php echo $imageId; ?>">
            <?php self::createLabel($args['label'], $fieldName); ?>

            <a href="<?php echo !empty($value) ? $imageUrl : '#' ?>" class="button-open-image <?php echo !empty($value) ? '' : 'hidden' ?>" target="_blank">
                <img src="<?php echo !empty($value) ? $imageUrl : '' ?>" alt="metafield-image" class="<?php echo !empty($value) ? '' : 'hidden' ?>" height="220px"/>
            </a>

            <?php self::_createHiddenTextarea($args); ?>

            <div class="after-metafield">
                <a href="#" class="button-add"><?php echo __('Add image'); ?></a>
                <a href="#" class="button-remove <?php echo !empty($value) ? '' : 'hidden'; ?>"><?php echo __('Remove image'); ?></a>

                <?php self::createDescription($args['description']); ?>
            </div>
        </div>
        <?php
    }


    /**
     * Create textarea
     * @param $args
     */
    protected function _createTextarea($args) {
        $name        = $args['name'];
        $value       = self::sanitizeValue($args['value']);
        $fieldName   = self::getFieldName($args['option_name'], $name);
        $size        = self::getSize($args['size']);
        $required    = self::getRequired($args['required']);
        $selector    = self::getSelector($args['selector']);
        $rows        = isset($args['rows']) && is_numeric($args['rows']) ? $args['rows'] : 4;
        $cols        = isset($args['cols']) && is_numeric($args['cols']) ? $args['cols'] : 6;
        ?>

        <div class="custom-metafield <?php echo $size; ?>" data-metafield="textarea">
            <?php self::createLabel($args['label'], $fieldName); ?>

            <textarea name="<?php echo $fieldName; ?>" rows="<?php echo $rows; ?>" cols="<?php echo $cols; ?>" id="<?php echo $fieldName; ?>" class="metafield <?php echo $selector; ?>" <?php echo $required; ?>><?php echo $value; ?></textarea>

            <?php self::createDescription($args['description']); ?>
        </div>
        <?php
    }


    /**
     * Create hidden textarea
     * @param $args
     */
    protected function _createHiddenTextarea($args) {
        $name        = $args['name'];
        $value       = self::sanitizeValue($args['value']);
        $fieldName   = self::getFieldName($args['option_name'], $name);
        ?>

        <textarea id="<?php echo $fieldName ?>" name="<?php echo $fieldName; ?>" class="metafield textarea-hidden"><?php echo $value; ?></textarea>
    <?php
    }


    /**
     * Create dropdown single
     * @param $args
     */
    protected function _createDropdownSingle($args) {
        $name        = $args['name'];
        $value       = self::sanitizeValue($args['value']);
        $fieldName   = self::getFieldName($args['option_name'], $name);
        $size        = self::getSize($args['size']);

        // DEV NOTE: Required attribute is not used for this field
        $required    = self::getRequired($args['required']);

        $selector    = self::getSelector($args['selector']);
        $data        = isset($args['data']) && !empty($args['data']) ? $args['data'] : array();
        ?>

        <div class="custom-metafield <?php echo $size; ?> <?php echo $selector; ?>" data-metafield="dropdown_single">
            <?php self::createLabel($args['label'], $fieldName); ?>

            <select name="<?php echo $fieldName; ?>">
                <option value="-1" selected="selected"><?php echo __('Select one of the options') ?></option>

                <?php foreach ($data as $item): ?>
                    <option value="<?php echo $item->ID; ?>" <?php echo $value == $item->ID ? 'selected="selected"' : ''; ?>><?php echo $item->post_title; ?></option>
                <?php endforeach ?>
            </select>

            <?php self::createDescription($args['description']); ?>
        </div>
        <?php
    }


    /**
     * Create dropdown multiple
     * @param $args
     */
    protected function _createDropdownMultiple($args) {
        $value       = isset($args['value']) && is_array($args['value']) ? $args['value'] : array();
        $name        = $args['name'];
        $fieldName   = self::getFieldName($args['option_name'], $name) . '[]';
        $size        = isset($args['size']) ? $args['size'] : '';

        // DEV NOTE: Required attribute is not used for this field
        $required    = isset($args['required']) && $args['required'] === 'true';

        $height      = isset($args['height']) ? $args['height'] : '';
        $selector    = isset($args['selector']) ? $args['selector'] : '';
        $data        = isset($args['data']) && !empty($args['data']) ? $args['data'] : array();
        ?>

        <div class="custom-metafield <?php echo $size; ?> <?php echo $selector; ?>" data-metafield="dropdown_multiple">
            <?php self::createLabel($args['label'], $fieldName); ?>

            <?php self::_createHiddenInput($args); ?>

            <ul class="multiple-list <?php echo $height; ?>">
                <?php foreach ($data as $item): ?>
                    <?php $itemId = 'id-' . $item->ID; ?>

                    <li>
                        <div>
                            <input type="checkbox" <?php echo in_array($item->ID, $value) ? 'checked="checked"' : ''; ?> value="<?php echo $item->ID; ?>" name="<?php echo $fieldName ?>" id="<?php echo $itemId; ?>"/>
                            <label for="<?php echo $itemId; ?>">
                                <?php echo $item->post_title; ?>
                            </label>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>

            <?php self::createDescription($args['description']); ?>
        </div>
    <?php
    }


    /**
     * Create wysiwyg editor
     * @param $args
     */
    protected static function _createWYSIWYG($args) {
        $name        = $args['name'];
        $value       = self::sanitizeValue($args['value']);
        $fieldName   = self::getFieldName($args['option_name'], $name);
        $size        = self::getSize($args['size']);

        // DEV NOTE: Required attribute is not used for this field
        $required    = self::getRequired($args['required']);

        $selector    = self::getSelector($args['selector']);
        $iconsDir    = get_stylesheet_directory_uri() . '/modules/' . $args['module']['dir'] . '/assets/fonts/trumbowyg/icons.svg';
        $height      = isset($args['$height']) ? $args['$height'] : '';
        ?>

        <div class="custom-metafield <?php echo $size; ?> <?php echo $height ?>" data-metafield="editor" data-icons-dir="<?php echo $iconsDir; ?>">
            <?php self::createLabel($args['label'], $fieldName); ?>

            <textarea class="textarea-hidden" name="<?php echo $fieldName; ?> <?php echo $selector; ?>"><?php echo $value; ?></textarea>
            <div id="<?php echo $fieldName; ?>" class="wysiwyg-wrapper"></div>

            <?php self::createDescription($args['description']); ?>
        </div>
    <?php
    }


    /**
     * Create map
     * @param $args
     */
    protected function _createMap($args) {
        $name        = $args['name'];
        $value       = stripslashes($args['value']);
        $fieldName   = self::getFieldName($args['option_name'], $name);
        $size        = self::getSize($args['size']);

        // DEV NOTE: These fields are currently not used
        $required    = self::getRequired($args['required']);
        $selector    = self::getSelector($args['selector']);

        $markerDir   = get_stylesheet_directory_uri() . '/modules/' . $args['module']['dir'] . '/assets/images/map-marker.png';
        $rawValues   = $value;
        $values      = json_decode($rawValues);

        $height      = isset($args['height']) && is_numeric(floatval($args['height']))? $args['height'] : '400px';

        $default_lat_val = 44;
        $default_lng_val = 23;
        $default_zoom_val = 4;

        $lat_val = isset($values->lat) && !empty($values->lat) ? $values->lat : $default_lat_val;
        $lng_val = isset($values->lng) && !empty($values->lng) ? $values->lng : $default_lng_val;
        $zoom_val = isset($values->zoom) && !empty($values->zoom) ? $values->zoom : $default_zoom_val;
        ?>

        <div class="custom-metafield <?php echo $size; ?>" data-metafield="map" data-marker-dir="<?php echo $markerDir; ?>" data-lat="<?php echo $lat_val; ?>" data-lng="<?php echo $lng_val; ?>" data-zoom="<?php echo $zoom_val; ?>" data-map-id="<?php echo $fieldName; ?>">

            <div class="map-controls">
                <a href="#" class="button-open-map-settings" title="<?php echo __('Toggle map settings'); ?>" data-control-opened="false"><span class="dashicons dashicons-admin-settings"></span></a>

                <label><?php echo __('Latitude'); ?></label>
                <input type="number" min="-200" max="200" step="0.0000001" class="lat control" value="<?php echo $lat_val; ?>"/>

                <label><?php echo __('Longitude'); ?></label>
                <input type="number" min="-200" max="200" step="0.0000001" class="lng control" value="<?php echo $lng_val; ?>"/>

                <label><?php echo __('Zoom level'); ?></label>
                <input type="number" min="0" max="22" step="1" class="zoom control" value="<?php echo $zoom_val; ?>"/>

                <a href="#" class="button-update-map" title="<?php echo __('Press to update the map') ?>"><?php echo __('Update the map'); ?></a>
            </div>

            <textarea class="textarea-hidden" name="<?php echo $fieldName; ?>" ><?php echo $rawValues; ?></textarea>

            <div id="<?php echo $fieldName; ?>" class="metafield" style="<?php echo $height; ?>"></div>

            <?php self::createDescription($args['description']); ?>
        </div>
        <?php
    }



    /**
     * Create plain text
     * @param $args
     */
    protected function _createPlainText($args) {
        $size          = $args['size'];
        $block         = is_array($args['block']) ? $args['block'] : array($args['block']);
        $block['text'] = isset($block['text']['p']) ? array($block['text']) : $block['text'];
        $selector      = isset($args['selector']) ? $args['selector'] : '';
        ?>

        <div class="custom-metafield <?php echo $size; ?> <?php echo $selector; ?>" data-metafield="plain_text">

        <?php

        if ( ! empty($block) ) {
            foreach( $block as $b ) {
                $currBlock = is_array($b) ? $b : array($b);

                foreach( $currBlock as $field ) {
                    $heading = isset($field['h']) ? $field['h'] : null;
                    $text = isset($field['p']) ? $field['p'] : null;
                    $link = isset($field['link']) ? $field['link'] : null;
                    $link_text = isset($field['linkText']) ? $field['linkText'] : null;
                    $ribbon = isset($field['ribbon']) ? $field['ribbon'] : null;

                    if ( $heading ) {
                        ?>
                            <p><strong><?php echo __($heading); ?></strong></p>
                        <?php
                    }

                    if ( $text ) {
                        $text = is_array($text) ? $text : array($text);
                        foreach ( $text as $t ) {
                            ?>
                                <p><?php echo __($t); ?></p>
                            <?php
                        }
                    }

                    if ( $ribbon ) {
                        $ribbon = is_array($ribbon) ? $ribbon : array($ribbon);
                        foreach ( $ribbon as $r ) {
                            ?>
                                <div class="ribbon"><p><?php echo __($r); ?></p></div>
                            <?php
                        }
                    }

                    if ( $link && $link_text ) {
                        ?>
                            <a href="<?php echo $link; ?>" target="_blank"><?php echo __($link_text); ?></a>
                        <?php
                    }
                }
            }
        }

        ?>
        </div>
        <?php
    }


    /**
     * Create gallery
     * @param $args
     */
    protected function _createGallery($args) {
        $name        = $args['name'];
        $value       = stripslashes($args['value']);
        $values      = json_decode($value);
        $valuesExist = count($values) > 0;
        $fieldName   = self::getFieldName($args['option_name'], $name);
        $size        = self::getSize($args['size']);

        // DEV NOTE: These fields are currently not used
        $required    = self::getRequired($args['required']);
        $selector    = self::getSelector($args['selector']);
        ?>

        <div class="custom-metafield <?php echo $size; ?>" data-metafield="gallery">
            <?php self::createLabel($args['label'], $fieldName); ?>

            <div class="gallery-wrapper">

                <div class="gallery-frame" id="<?php echo $name; ?>">
                    <ul>
                        <?php if ( $valuesExist ) : ?>
                            <?php foreach ($values as $item): ?>
                                <li>
                                    <div class="gallery-image-wrapper">
                                        <a href="<?php echo $item->url; ?>" class="gallery-image-link"><img src="<?php echo $item->url; ?>" /></a>
                                    </div>
                                </li>
                            <?php endforeach ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <a href="#" class="button-add"><?php echo __('Select images') ?></a>
            <a href="#" class="button-remove <?php echo $valuesExist ? '' : 'hidden'; ?>"><?php echo __('Remove images') ?></a>

            <textarea class="textarea-hidden" name="<?php echo $fieldName; ?>" id="<?php echo $fieldName; ?>"><?php echo $value; ?></textarea>

            <?php self::createDescription($args['description']); ?>
        </div>
    <?php
    }


    /**
     * Sanitize value
     * @param $value
     * @return string
     */
    public static function sanitizeValue($value) {
        if ( is_array($value) ) {
            return self::sanitizeValues($value);
        } else {
            return stripslashes(htmlentities($value));
        }
    }


    /**
     * Sanitize values
     * @param $values
     * @return array
     */
    protected static function sanitizeValues($values) {
        $sanitizedValues = array();

        if ( count($values) > 0 ) {
            foreach ( $values as $value ) {
                array_push($sanitizedValues, self::sanitizeValue($value));
            }
        }

        return $sanitizedValues;
    }


    /**
     * Get field name
     * @param string $optionName
     * @param string $name
     * @return string
     */
    protected static function getFieldName($optionName, $name) {
        if ( isset($optionName) && !empty($optionName) ) {
            return $optionName . '[' . $name . ']';
        } else {
            return $name;
        }
    }


    /**
     * Get size
     * @param $size
     * @return string
     */
    protected static function getSize($size) {
        return isset($size) && !empty($size) ? $size : 'auto';
    }


    /**
     * Get required
     * @param $required
     * @return string
     */
    protected static function getRequired($required) {
        return isset($required) && !empty($required) && $required === 'true' ? 'required' : '';
    }


    /**
     * Get selector
     * @param $selector
     * @return string
     */
    protected static function getSelector($selector) {
        return isset($selector) && !empty($selector) ? $selector : '';
    }


    /**
     * Create label
     * @param $label
     * @param $for
     */
    protected static function createLabel($label, $for = null) {
        if ( isset($label) && !empty($label) ) {
            ?>
            <label for="<?php echo $for; ?>"><?php echo $label; ?></label>
            <?php
        }
    }


    /**
     * Create description
     * @param $description
     */
    protected static function createDescription($description) {
        if ( isset($description) && !empty($description) ) {
            ?>
            <p class="description"><?php echo $description; ?></p>
            <?php
        }
    }


    /**
     * Create nonce
     * @param $args
     */
    protected static function createNonce($args) {
        ?>
            <input type="hidden" name="<?php echo $args['name'] . '_noncename'; ?>" id="<?php echo $args['name'] . '_noncename'; ?>" value="<?php echo wp_create_nonce(plugin_basename(__FILE__)); ?>"/>
        <?php
    }

}