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
 * Class DynamicYield_Integration_Model_Config_Source_Category
 */
class DynamicYield_Integration_Model_Config_Source_Categorytree
{

    public function getCategories() {

        $categories = Mage::getModel('catalog/category')
            ->getCollection()
            ->addAttributeToSelect('name','level')
            ->addAttributeToSort('path', 'asc')
            ->addAttributeToFilter('level', array('eq' => '1'))
            ->load()
            ->toArray();

        $categoryTreeList = array();
        foreach ($categories as $catId => $category) {
            if (isset($category['name'])) {
                $categoryTreeList[] = array(
                    'label' => $category['name'],
                    'level'  =>$category['level'],
                    'value' => $catId
                );
            }
        }

        return $categoryTreeList;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = array();
        $categories = $this->getCategories();

        $options[] = array(
            'label' => '',
            'value' => ''
        );
        foreach($categories as $category)
        {
            $options[] = array(
                'label' => $category['label'],
                'value' => $category['value']
            );
        }

        return $options;
    }

}