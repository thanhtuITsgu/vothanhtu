<?php
namespace Magenest\Movie\Observer;
use Magento\Framework\Event\ObserverInterface;
class Edit implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $customerId = $observer->getCustomer()->getId();/*getEvent()->getData('customer');*/
        $objectManager =\Magento\Framework\App\ObjectManager::getInstance();
        $customer=$objectManager->create('Magento\Customer\Model\Customer')->load($customerId);
        $customer->setFirstname('Magenest');
        $customer->save();
    }
}