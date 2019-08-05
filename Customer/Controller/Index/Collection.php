<?php

namespace Magenest\Customer\Controller\Index;
class Collection extends
    \Magento\Framework\App\Action\Action
{
    public function execute()
    {
        $productCollection = $this->_objectManager
            ->create
            ('Magento\Customer\Model\ResourceModel\Customer\Collection')
            ->addAttributeToSelect([
                'name',
                'price',
                'avatar',])
            /* ->addAttributeToFilter('name', array('like' => '%Bag%'));*/
          ->setPageSize(10,1);

        $output = ''; //$productCollection->getSelect()->__toString(); //Tra ve cau sql !
        /*
                $productCollection->setDataToAll('price', 20);// alldata set Price = 10 ;

                $productCollection->save(); // Save sau khi Setalldata .*/
        foreach ($productCollection as $product) {
            echo "<pre>";
            $output .= \Zend_Debug::dump($product->debug(), null, //Tao bang cai mang tra ve !
                false);
            echo "</pre>";
        }
        $this->getResponse()->setBody($output);

    }
}
// Coleccton dung de tra ve san pham khoa dieu kien 1
// De tra ve het ta dung : ->setPageSize(10,1);
// De ra ve Where name ="abc" ta dung :->addAttributeToFilter('name', 'Overnight Duffle');
// id: ->addAttributeToFilter('entity_id', array('in' => array(159, 160, 161);
// Wherer name like % % ta dung :->addA