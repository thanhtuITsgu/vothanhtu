<?php
namespace Magenest\Movie\Observer;
use Magento\Framework\Event\ObserverInterface;
class Editfirstnamecustomer implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $customer  = $observer->getCustomer();
        $firstname=$customer->getFirstName();
        $customerId = $customer->getId();
        $customerData = $this->_customerRepositoryInterface->getById($customerId);
        $customer->setFirstName('Magenest');
        $customerData->save();
        $this->messageManager->addSuccess(__('Edit Succesful'));
    }
}