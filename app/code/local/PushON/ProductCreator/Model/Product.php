<?php

class PushON_ProductCreator_Model_Product extends Mage_Core_Model_Abstract {

    private $productChildren;
    private $attributesUsedArray;
    private $configuredProductId;

    public function createMainProduct($productData) {
       // print_r($productData);
       // die;
        $productModel = Mage::getModel('catalog/product');
        try {
            $productModel
                    //    ->setStoreId(1) //you can set data in store scope
                    ->setWebsiteIds(array(1)) //website ID the product is assigned to, as an array
                    ->setAttributeSetId(4) //ID of a attribute set named 'default'
                    ->setTypeId('configurable') //product type
                    ->setCreatedAt(strtotime('now')) //product creation time
                    //    ->setUpdatedAt(strtotime('now')) //product update time
                    ->setSku($productData['sku']) //SKU
                    ->setName($productData['name']) //product name
                    ->setWeight(4.0000)
                    ->setStatus(1) //product status (1 - enabled, 2 - disabled)
                    ->setTaxClassId(4) //tax class (0 - none, 1 - default, 2 - taxable, 4 - shipping)
                    ->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH) //catalog and search visibility
                    ->setManufacturer(28) //manufacturer id
                    //->setColor(6)
                    //->setData('color',7)
                    ->setNewsFromDate('06/26/2014') //product set as new from
                    ->setNewsToDate('06/30/2014') //product set as new to
                    ->setCountryOfManufacture('AF') //country of manufacture (2-letter country code)
                    ->setPrice(11.22) //price in form 11.22
                    ->setCost(22.33) //price in form 11.22
                    ->setSpecialPrice(00.44) //special price in form 11.22
                    ->setSpecialFromDate('06/1/2014') //special price from (MM-DD-YYYY)
                    ->setSpecialToDate('06/30/2014') //special price to (MM-DD-YYYY)
                    ->setMsrpEnabled(1) //enable MAP
                    ->setMsrpDisplayActualPriceType(1) //display actual price (1 - on gesture, 2 - in cart, 3 - before order confirmation, 4 - use config)
                    ->setMsrp(99.99) //Manufacturer's Suggested Retail Price
                    ->setMetaTitle('test meta title 2')
                    ->setMetaKeyword('test meta keyword 2')
                    ->setMetaDescription('test meta description 2')
                    //->setDescription($productData['description'])
                    ->setShortDescription('This is a short description')
                    ->setStockData(array(
                        'use_config_manage_stock' => 0, //'Use config settings' checkbox
                        'manage_stock' => 1, //manage stock
                        'min_sale_qty' => 1, //Minimum Qty Allowed in Shopping Cart
                        'max_sale_qty' => 2, //Maximum Qty Allowed in Shopping Cart
                        'is_in_stock' => 1, //Stock Availability
                        'qty' => 999 //qty
                            )
                    )
            
                    ->getTypeInstance()->setUsedProductAttributeIds($this->attributesUsedArray);
            $productModel->getTypeInstance()->setUsedProductAttributeIds($this->attributesUsedArray); //attribute ID of attribute 'color' in my store
            $configurableAttributesData = $productModel->getTypeInstance()->getConfigurableAttributesAsArray();
            $productModel->setCanSaveConfigurableAttributes(true);
            $productModel->setConfigurableAttributesData($configurableAttributesData);
            if(isset($productData['items_per_sheet'])){
                $productModel->setData('items_per_sheet' , $productData['items_per_sheet']);
            }
            $productModel->save();
            //endif;
        } catch (Exception $e) {
            Mage::log($e->getMessage());
        }
        
        $mainProductId = Mage::getModel('catalog/product')->getIdBySku($productData['sku']);
        $this->configuredProductId = $mainProductId;
        $mainProduct = Mage::getModel('catalog/product')->load($mainProductId);
        
        foreach($this->productChildren as $childId){
            $this->_attachProductToConfigurable($childId, $mainProduct);
        }
        
    }

    public function createProduct($productData) {
        $this->createSimpleProducts($productData);
        $this->createMainProduct($productData);
    }

    public function createSimpleProducts($productData) {

        $productsInfo = $this->prepareSimpleProducts($productData);
        //print_r($productData);
        $counter = 0;
        $skuArray = array();

        foreach ($productsInfo as $product) {

            $skuArray[] = $product['sku'];

            ++$counter;
            $productModel = Mage::getModel('catalog/product');
            try {
                $productModel
                        //    ->setStoreId(1) //you can set data in store scope
                        ->setWebsiteIds(array(1)) //website ID the product is assigned to, as an array
                        ->setAttributeSetId(4) //ID of a attribute set named 'default'
                        ->setTypeId('simple') //product type
                        ->setCreatedAt(strtotime('now')) //product creation time
                        //    ->setUpdatedAt(strtotime('now')) //product update time
                        ->setSku($product['sku']) //SKU
                        ->setName($product['name']) //product name
                        ->setWeight(4.0000)
                        ->setStatus(1) //product status (1 - enabled, 2 - disabled)
                        ->setTaxClassId(4) //tax class (0 - none, 1 - default, 2 - taxable, 4 - shipping)
                        ->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE) //catalog and search visibility
                        ->setManufacturer(28) //manufacturer id
                        //->setColor(6)
                        //->setData('color',7)
                        ->setNewsFromDate('06/26/2014') //product set as new from
                        ->setNewsToDate('06/30/2014') //product set as new to
                        ->setCountryOfManufacture('AF') //country of manufacture (2-letter country code)
                        ->setPrice(11.22) //price in form 11.22
                        ->setCost(22.33) //price in form 11.22
                        ->setSpecialPrice(00.44) //special price in form 11.22
                        ->setSpecialFromDate('06/1/2014') //special price from (MM-DD-YYYY)
                        ->setSpecialToDate('06/30/2014') //special price to (MM-DD-YYYY)
                        ->setMsrpEnabled(1) //enable MAP
                        ->setMsrpDisplayActualPriceType(1) //display actual price (1 - on gesture, 2 - in cart, 3 - before order confirmation, 4 - use config)
                        ->setMsrp(99.99) //Manufacturer's Suggested Retail Price
                        ->setMetaTitle('test meta title 2')
                        ->setMetaKeyword('test meta keyword 2')
                        ->setMetaDescription('test meta description 2')
                        ->setDescription($product['description'])
                        ->setShortDescription('This is a short description')
                        ->setStockData(array(
                            'use_config_manage_stock' => 0, //'Use config settings' checkbox
                            'manage_stock' => 1, //manage stock
                            'min_sale_qty' => 1, //Minimum Qty Allowed in Shopping Cart
                            'max_sale_qty' => 2, //Maximum Qty Allowed in Shopping Cart
                            'is_in_stock' => 1, //Stock Availability
                            'qty' => 999 //qty
                                )
                        )
                        ->setCategoryIds(array(3, 10)); //assign product to categories

                $productAttributes = explode(',', $product['attributes']);
                foreach ($productAttributes as $singAttribute) {
                    $attribute = explode('=', $singAttribute);
                    $productModel->setData($attribute[0], $attribute[1]);
                }
                $productModel->save();
                //endif;
            } catch (Exception $e) {
                Mage::log($e->getMessage());
            }
        }
        $childrenProducts = Mage::getModel('catalog/product')
                ->getCollection()
                ->addAttributeToFilter('sku', array('in' => $skuArray))
                ->addAttributeToSelect('*');

        $childrenIDs = array();
        foreach ($childrenProducts as $childProduct) {
            $childrenIDs[] = $childProduct->getId();
        }

        $this->productChildren = $childrenIDs;
       
    }

    public function prepareSimpleProducts($productData) {

        if (isset($productData['paper_size'])){
            $data['paper_size'] = $this->addKeysToArrayString('paper_size', $productData['paper_size']);
            $this->attributesUsedArray[] = 133;
        }
        if (isset($productData['finish'])){
            $data['finish'] = $this->addKeysToArrayString('finish', $productData['finish']);
            $this->attributesUsedArray[] = 141;
        }
        if (isset($productData['paper_weight'])){
            $data['paper_weight'] = $this->addKeysToArrayString('paper_weight', $productData['paper_weight']);
            $this->attributesUsedArray[] = 143;
        }
        if (isset($productData['colour'])){
            $data['colour'] = $this->addKeysToArrayString('colour', $productData['colour']);
            $this->attributesUsedArray[] = 144;
        }
        $perms = Mage::Helper('productcreator/permutations')->getPermutations($data);

        $productArray = array();
        $counter = 0;
        foreach ($perms as $productAttibutes) {
            $counter++;
            $productArray[] = array('sku' => $productData['sku'] . $counter,
                'name' => $productData['name'],
                'description' => $productData['description'],
                'attributes' => $productAttibutes);
        }
        return $productArray;
    }

    public function addKeysToArrayString($key, $array) {

        $prepedArray = array();
        foreach ($array as $val) {
            $prepedArray[] = $key . '=' . $val;
        }

        return $prepedArray;
    }

    private function _attachProductToConfigurable($_childProduct, $_configurableProduct) {
        $loader = Mage::getResourceModel('catalog/product_type_configurable')->load($_configurableProduct, $_configurableProduct->getId());

        $ids = $_configurableProduct->getTypeInstance()->getUsedProductIds();
        $newids = array();
        foreach ($ids as $id) {
            $newids[$id] = 1;
        }

        $newids[$_childProduct] = 1;

        //$loader->saveProducts( $_configurableProduct->getid(), array_keys( $newids ) );                
        $loader->saveProducts($_configurableProduct, array_keys($newids));
    }
    
    public function getChildrenIds(){
        return $this->productChildren;
    }
    
    public function getProductConfiguredId(){
        return $this->configuredProductId;
    }

}
