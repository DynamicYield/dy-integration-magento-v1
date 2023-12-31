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
 * Class DynamicYield_Integration_Model_Event_Purchase
 */
class DynamicYield_Integration_Model_Event_Purchase extends DynamicYield_Integration_Model_Event_Abstract
{
    /** @var  Mage_Sales_Model_Order */
    protected $order;

    /**
     * @return mixed
     */
    function getName() {
        return 'Purchase';
    }

    /**
     * @return mixed
     */
    function getType() {
        return 'purchase-v1';
    }

    /**
     * @return mixed
     */
    function getDefaultProperties() {
        return array('uniqueTransactionId' => NULL,'value' => NULL, 'currency' => NULL, 'cart' => array());
    }

    /**
     * @param Mage_Sales_Model_Order $order
     */
    function setOrder(Mage_Sales_Model_Order $order) {
        $this->order = $order;
    }

    /**
     * @return array
     */
    function generateProperties() {
        $prepareItems = array();
        $items = array();
        $cart = Mage::getModel('checkout/cart')->getQuote();
        foreach ($cart->getAllItems() as $item) {

            $sku = Mage::helper('dynamicyield_integration/data')->replaceSpaces($item->getSku());

            /**
             * Skip bundle and grouped products (out of scope)
             */
            if($item->getProductType() == "bundle" || $item->getProductType() == "grouped" || isset($prepareItems[$sku])) {
                continue;
            }

            $product = $item->getProduct();

            if(!$product || !Mage::helper('dynamicyield_integration')->validateSku($product)) {
                continue;
            }

            $prepareItems[$sku] = array(
                'productId' => Mage::helper('dynamicyield_integration')->validateSku($product) ? Mage::helper('dynamicyield_integration/data')->replaceSpaces($product->getSku()) : Mage::helper('dynamicyield_integration/data')->replaceSpaces($product->getData('sku')),
                'quantity' => $item->getQty(),
                'itemPrice' => round(Mage::helper('core')->currency($product->getFinalPrice(),false,false),2)
            );
        }

        foreach ($prepareItems as $item) {
            $items[] = $item;
        }

        return array('uniqueTransactionId' => (string) $this->order->getIncrementId(),'value' => round($this->order->getGrandTotal(),2), 'currency' => $this->order->getOrderCurrencyCode(), 'cart' => $items);
    }
}
