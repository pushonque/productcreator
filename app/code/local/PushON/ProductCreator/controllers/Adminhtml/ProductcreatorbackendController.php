<?php

class PushON_ProductCreator_Adminhtml_ProductcreatorbackendController extends Mage_Adminhtml_Controller_Action {

    public function indexAction() {
        $this->loadLayout();
        $this->_title($this->__("Product Creator"));
        $this->renderLayout();
    }

    public function createAction() {
        $productCreator = Mage::getModel('productcreator/Product');
        $productCreator->createProduct($this->getRequest()->getPost());
        Mage::getSingleton("core/session")->setSimpleProductIds($productCreator->getChildrenIds());
        Mage::getSingleton("core/session")->setConfiguredProductId($productCreator->getProductConfiguredId());
        $this->_redirectUrl(Mage::helper('adminhtml')->getUrl('/adminhtml_productcreatorbackend/success'));
        
    }
    
    public function successAction(){
        $this->loadLayout();
        $this->_title($this->__("Product Creator"));
        $this->renderLayout();
    }

}
