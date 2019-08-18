<?php
namespace Magenest\Customer\Block;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template;

class Image extends Template
{
    public function getImageCustomer()
    {
        $objm = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objm->get('Magento\Customer\Model\Session');
        if ($customerSession->isLoggedIn()) {
            $customerId = $customerSession->getId();
           /* $data = $customerSession->getCustomer()->getAvatar();*/
         /*   $productCollection = $objm->create('Magento\Customer\Model\Customer');
            $collection = $productCollection->load($customerId);
            $collection->getData()->getFirstname();*/


            $resourch = $objm->create('Magento\Framework\App\ResourceConnection');
            $conection = $resourch->getConnection();
            $sql = "select value as nameImage from customer_entity_text where entity_id=$customerId";
            $result = $conection->fetchAll($sql);
            foreach($result as $data)
            {
             return  $name=$data['nameImage'];
            }
           /*$nameImage="pub/media/customer".$name;*/

        }
        /*$Avatar=$collection->getAvatar();*/
        /*$Collection=$objm->get('Magento\Customer\Model\ResourceModel\Customer\Collection');
        $data = $Collection->load($customerId);
        $image=$data->getAvatar();*/
    /*return $Avatar;*/
    }
}