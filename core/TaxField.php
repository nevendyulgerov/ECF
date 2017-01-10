<?php

namespace ECF;

/**
 * Class CustomPostField
 * Controls the custom fields for custom post types
 * @package ECF
 */
class TaxField {


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

                        $this->createCustomMetafields($metafield);
                    }
                } else {

                    // update field value
                    $metafield['value'] = $taxMeta[$metafield['name']];

                    // update field name
                    $metafield['name'] = $taxName . '[' . $metafield['name'] . ']';
                    $this->createCustomMetafields($metafield);
                }
            }
        }
    }


    /**
     * Create custom fields
     * @param $metafield
     */
    public function createCustomMetafields($metafield) {
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