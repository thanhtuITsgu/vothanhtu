<?php
namespace Magenest\Movie\Model\Config\Backend;

class Numrowactor extends \Magento\Framework\App\Config\Value
{
    public function _afterLoad()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $movieCollection = $objectManager->create('\Magenest\Movie\Model\ResourceModel\Actor\Collection');
        $result = $movieCollection->count();
        return $this->setValue($result);
    }
}