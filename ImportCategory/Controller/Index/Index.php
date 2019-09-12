<?php

namespace Magenest\ImportCategory\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action // Co the lien ket vs View\Layout tu day ??? !
{
    /** @var \Magento\Framework\View\Result\PageFactory */

    public function execute()
    {
        $productCollection = $this->_objectManager
            ->create
            ('Magento\Catalog\Model\ResourceModel\Category\Collection')
            ->addAttributeToSelect([
                'name',
                'price',
                'image','description','children_data',])
            /* ->addAttributeToFilter('name', array('like' => '%Bag%'));*/
            ->addAttributeToFilter('entity_id', array('in' => array(1, 2, 3)));
        /*  ->setPageSize(10,1);*/

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
