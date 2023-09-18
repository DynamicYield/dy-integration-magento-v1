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
 * Class DynamicYield_Integration_SyncController
 */
class DynamicYield_Integration_SyncController extends Mage_Core_Controller_Front_Action {

    const SYNC_CART = 'sync_cart';

    /**
     * Sync cart event
     *
     * @return mixed
     */
    public function indexAction()
    {
        $session =  Mage::getSingleton('core/session');
        $getSessionId = $session->getData(self::SYNC_CART);

        if ($getSessionId != $session->getSessionId()) {
            $session->setData(self::SYNC_CART, $session->getSessionId());
        }

        $syncCart = $getSessionId == $session->getSessionId() ? true : false;

        if(!$syncCart) {
            $json = Mage::helper('core')->jsonEncode([
                'sync_cart' => $syncCart,
                'eventData' => Mage::getModel('dynamicyield_integration/event_syncCart')->build()->toArray()
            ]);
        } else {
            $json =  Mage::helper('core')->jsonEncode(array(
                'sync_cart' => $syncCart
            ));
        }
        return $this->getResponse()->setBody($json);

    }

}