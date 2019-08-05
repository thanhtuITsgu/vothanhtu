<?php
namespace Magenest\Movie\Observer;
class Rating implements \Magento\Framework\Event\ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $movieId=$observer->getModel()->getid();
        $objectManager =\Magento\Framework\App\ObjectManager::getInstance();
        $contact = $objectManager->create('Magenest\Movie\Model\Movie')->load($movieId);
        $contact->setRating('0');
        $contact->save();
    }
}