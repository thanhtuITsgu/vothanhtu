<?php
namespace Magenest\Customer\Block;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template;

class Icon extends Template
{
    public function execute()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->create('Magento\Customer\Model\Session');
        if ($customerSession->isLoggedIn()) {
            $customer = $customerSession->getCustomer();
            $received = $customer->getNotification_received();
            $deleted = $customer->getNotification_deleted();
            $Array = explode(',', $received);
            $Erray = explode(',', $deleted);
            $Collection = $objectManager->create('Magenest\Notification\Model\ResourceModel\Promo\Collection');
            $Collection1 = $Collection->addFieldToFilter('entity_id', array('in' => $Array));
            $Collection2 = $Collection1->addFieldToFilter('entity_id', array('nin' => $Erray))->count();
        }

    }
}