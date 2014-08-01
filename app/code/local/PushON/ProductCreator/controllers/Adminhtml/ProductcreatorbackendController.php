<?php
class PushON_ProductCreator_Adminhtml_ProductcreatorbackendController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
    {
       $this->loadLayout();
	   $this->_title($this->__("Product Creator"));
	   $this->renderLayout();
    }
}