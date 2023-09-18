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
 * Class DynamicYield_Integration_Model_Event_Addtocart
 */
class DynamicYield_Integration_Model_Event_Addtocart extends DynamicYield_Integration_Model_Event_Abstract
{
    /**
     * @var Mage_Catalog_Model_Product
     */
    protected $product;

    /**
     * @var int
     */
    protected $qty;

    /**
     * @return mixed
     */
    function getName() {
        return 'Add to Cart';
    }

    /**
     * @return mixed
     */
    function getType() {
        return 'add-to-cart-v1';
    }

    /**
     * @return mixed
     */
    function getDefaultProperties() {
        return array('value' => 0, 'currency' => NULL, 'productId' => '', 'quantity' => 0,);
    }

    function generateProperties() {
        $product = $this->product;

        /**
         * @var $quote Mage_Sales_Model_Quote
         */
        $quote = Mage::getModel('checkout/cart')->getQuote();

        $currency = $quote->getQuoteCurrencyCode();
        if (!$currency) {
            $currency = $quote->getStoreCurrencyCode();
        }

        $qty = $this->qty;
        if(!$qty) {
            $qty = $quote->getItemByProduct($product)->getQty();
        }

        return array(
            'productId' => Mage::helper('dynamicyield_integration')->validateSku($product) ? Mage::helper('dynamicyield_integration/data')->replaceSpaces($product->getSku()) : Mage::helper('dynamicyield_integration/data')->replaceSpaces($product->getData('sku')),
            'value' => round(Mage::helper('core')->currency($product->getFinalPrice(),false,false),2),
            'currency' => $currency,
            'quantity' => $qty,
            'cart' => $this->getCartItems()
        );
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     */
    public function setProduct(Mage_Catalog_Model_Product $product) {
        $this->product = $product;

        return $this;
    }

    /**
     *
     * @param int $qty
     */
    public function setQty($qty) {
        $this->qty = $qty;

        return $this;
    }
}
