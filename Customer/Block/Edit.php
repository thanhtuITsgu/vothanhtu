<?php
namespace Magenest\Customer\Block;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template;

class Edit extends Template
{
    public function getLastname()
    {
        $objm = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objm->create('Magento\Customer\Model\Session');
        if ($customerSession->isLoggedIn()) {
            $customerId = $customerSession->getId();
            $productCollection = $objm->create('Magento\Customer\Model\Customer');
            $collection = $productCollection->load($customerId);
            $firstname = $collection->getData('lastname');
            return $firstname ;
        }
    }

    public function getFirstname()
    {
        $objm = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objm->create('Magento\Customer\Model\Session');
        if ($customerSession->isLoggedIn()) {
            $customerId = $customerSession->getId();
            $productCollection = $objm->create('Magento\Customer\Model\Customer');
            $collection = $productCollection->load($customerId);
            $firstname = $collection->getData('firstname');
            return $firstname ;
        }
    }

    public function getIdcustomer()
    {
        $objm = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objm->create('Magento\Customer\Model\Session');
        if ($customerSession->isLoggedIn()) {
            $customerId = $customerSession->getId();
           return $customerId ;
        }
    }

}