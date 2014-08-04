<?php

class PushON_ProductCreator_Block_Adminhtml_ProductCreatorbackend extends Mage_Adminhtml_Block_Template {

    public function getAttributeOptions($attributeCode) {
        $attribute = Mage::getSingleton('eav/config')->getAttribute('catalog_product', $attributeCode);
        if ($attribute->usesSource()) {
            $options = $attribute->getSource()->getAllOptions(false);
        }
        
        return $options;
    }
  
    public function getCreatedSimpleProductsCollection($ids){
        
        $productsCollection = Mage::getModel('catalog/product')
                              ->getCollection()
                              ->addAttributeToSelect('*')
                              ->addAttributeToFilter('entity_id', array('in' => $ids));
        
        return $productsCollection;
        
    }
    
    public function getConfiguredProduct($id){
        
        return Mage::getModel('catalog/product')->load($id) ;
    }

}
