<?php

namespace Magenest\Movie\Block\Adminhtml\TotalModule;

use Magento\Framework\View\Element\Template;

class Total extends Template
{
    Protected $_resource;
    protected $fullModuleList;
    public function __construct(
        Template\Context $context,
        \Magento\Framework\App\ResourceConnection $Resource,
        \Magento\Framework\Module\FullModuleList $fullModuleList)
    {
        parent::__construct($context);
        $this->_resource = $Resource;
        $this->fullModuleList = $fullModuleList;;

    }

    public function getModuleNameMagento()
    {
       /* $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resurce=$objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resurce->getConnection();
        $sql='SELECT count(*) as Total FROM setup_module WHERE module LIKE "Magento%"';
        $result = $connection->fetchAll($sql);
        return $result ;*/
        $string = new \Magento\Framework\Stdlib\StringUtils;
        $allModules = $this->fullModuleList->getAll();
        $number=null;
        foreach ($allModules as $value)
        {    $name=$string->substr($value['name'],0,7);
             if($name == 'Magento')
             $number++;
        }
        return $number;
    }

    public function getModuleNameNotMagento()
    {
       /* $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $connection = $this->_resource->getConnection();
        $sql='SELECT count(*) as Total FROM setup_module WHERE module NOT LIKE "%Magento%"';
        $result = $connection->fetchAll($sql);
        return $result ;*/

        $string = new \Magento\Framework\Stdlib\StringUtils;
        $allModules = $this->fullModuleList->getAll();
        $number=null;
        foreach ($allModules as $value)
        {    $name=$string->substr($value['name'],0,7);
            if($name != 'Magento')
                $number++;
        }
        return $number;
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
