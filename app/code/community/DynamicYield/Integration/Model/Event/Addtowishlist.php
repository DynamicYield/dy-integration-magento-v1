<?php
/**
 * DynamicYield_Integration
 *
 * @category     DynamicYield
 * @package      DynamicYield_Integration
 * @author       Dynamic Yield Ltd <support@dynamicyield.com.com>
 * @copyright    Copyright (c) 2017 Dynamic Yield (https://www.dynamicyield.com)
 **/

/**
 * Class DynamicYield_Integration_Model_Event_Addtowishlist
 */
class DynamicYield_Integration_Model_Event_Addtowishlist extends DynamicYield_Integration_Model_Event_Abstract
{
    /**
     * @var Mage_Catalog_Model_Product
     */
    protected $productSku;

    /**
     * @return mixed
     */
    function getName() {
        return 'Add to Wishlist';
    }

    /**
     * @return mixed
     */
    function getType() {
        return 'add-to-wishlist-v1';
    }

    /**
     * @return mixed
     */
    function getDefaultProperties() {
        return array('productId' => NULL,);
    }

    /**
     * @return array
     */
    function generateProperties() {
        return array(
            'productId' => $this->productSku
        );
    }

    /**
     * @param $productSku
     */
    public function setProductSku($productSku) {
        $this->productSku = $productSku;
    }
}
