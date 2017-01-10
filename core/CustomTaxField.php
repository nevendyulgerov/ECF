<?php

namespace ECF;

/**
 * Class CustomPostField
 * Controls the custom fields for custom post types
 * @package ECF
 */
class CustomTaxField {


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
        $this->moduleConfig = $moduleConfig;
        $this->config       = $config;
    }


    /**
     * save taxonomy fields
     */
    public function saveTaxonomyFields() {
        $taxonomies = $this->getTaxonomies();

        if ( count($taxonomies) > 0 ) {
            foreach( $taxonomies as $tax ) {
                $taxName    = $tax['name'];
                $metafields = $tax['metafields'];

                add_action('edited_' . $taxName, function($termId) use ($taxName, $metafields) {
                    $this->saveCustomFields($termId, $taxName, $metafields);
                });
            }
        }
    }


    /**
     * Add taxonomy fields
     */
    public function addTaxonomyFields() {
        $taxonomies = $this->getTaxonomies();

        if ( count($taxonomies) > 0 ) {
            foreach( $taxonomies as $tax ) {
                $taxName    = $tax['name'];
                $metafields = $tax['metafields'];

                add_action($taxName . '_edit_form_fields', function($term) use ($taxName, $metafields) {
                    $this->addCustomMetafields($term, $taxName, $metafields);
                });
            }
        }
    }


    /**
     * Get taxonomies
     * @return array
     */
    protected function getTaxonomies() {
        $taxonomies      = $this->config;
        $taxArr          = $taxonomies['taxonomy'];
        $hasMultipleTaxs = $taxArr['name'] === null;

        if ( ! $hasMultipleTaxs ) {
            $taxArr = array($taxonomies['taxonomy']);
        }

        return $taxArr;
    }


    /**
     * Add custom metafields
     * @param $term
     * @param $taxName
     * @param $metafields
     */
    public function addCustomMetafields($term, $taxName, $metafields) {
        $termId  = $term->term_id;
        $taxMeta = get_option("taxonomy_$termId");

        if ( count($metafields) > 0 ) {
            foreach ( $metafields as $metafieldsArr ) {
                $metafield            = $metafieldsArr;
                $metafieldType        = $metafieldsArr['type'];
                $hasMultiplePostTypes = $metafieldType === null;

                if ( $hasMultiplePostTypes ) {
                    foreach( $metafieldsArr as $metafield ) {

                        // update field value
                        $metafield['value'] = $taxMeta[$metafield['name']];

                        // update field name
                        $metafield['name'] = $taxName . '[' . $metafield['name'] . ']';

                        // set module data
                        $metafield['module'] = (array) $this->moduleConfig->module;

                        // create custom metafield
                        $this->createCustomMetafield($metafield);
                    }
                } else {

                    // update field value
                    $metafield['value'] = $taxMeta[$metafield['name']];

                    // update field name
                    $metafield['name'] = $taxName . '[' . $metafield['name'] . ']';

                    // set module data
                    $metafield['module'] = (array) $this->moduleConfig->module;

                    // create custom metafield
                    $this->createCustomMetafield($metafield);
                }
            }
        }
    }


    /**
     * Create custom field
     * @param $metafield
     */
    public function createCustomMetafield($metafield) {
        ?>
        <tr class="form-field">
            <th scope="row" valign="top"></th>
            <td>
                <?php Metafield::createField($metafield); ?>
            </td>
        </tr>
        <?php
    }


    /**
     * Save Custom Fields
     * @param $termId
     * @param $taxName
     * @param $metafields
     */
    public function saveCustomFields($termId, $taxName, $metafields) {
        Collection::saveTaxCustomFields($termId, $taxName, $metafields);
    }
}