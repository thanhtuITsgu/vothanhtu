<?php

namespace Magenest\Movie\Block\Adminhtml\TotalSynthetic;

use Magento\Framework\View\Element\Template;

class TotalSynthetic extends Template
{
    Protected $_resource;

    public function __construct(
        Template\Context $context,
        \Magento\Framework\App\ResourceConnection $Resource)
    {
        parent::__construct($context);
        $this->_resource = $Resource;

    }

    public function getNumberCustomer()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $CustomerCollection = $objectManager->create('\Magento\Customer\Model\ResourceModel\Customer\Collection');
        $result=$CustomerCollection->count();
        return $result ;
    }

    public function getNumberProduct()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $ProductCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
        $result=$ProductCollection->count();
        return $result ;
    }

    public function getNumberOrder()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $OrderCollection = $objectManager->create('Magento\Sales\Model\ResourceModel\Order\Collection');
        $result=$OrderCollection->count();
        return $result ;
    }

    public function getNumberInvoices()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $InvoicesCollection = $objectManager->create('Magento\Sales\Model\ResourceModel\Order\Invoice\Collection');
        $result=$InvoicesCollection->count();
        return $result ;
    }

    public function getNumberCreditmemos()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $CreditmemosCollection = $objectManager->create('Magento\Sales\Model\ResourceModel\Order\Creditmemo\Collection');
        $result=$CreditmemosCollection->count();
        return $result ;
    }
}