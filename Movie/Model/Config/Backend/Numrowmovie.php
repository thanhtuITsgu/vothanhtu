<?php
namespace Magenest\Movie\Model\Config\Backend;

class Numrowmovie extends \Magento\Framework\App\Config\Value
{
  /*  public function _afterLoad()
    {
          $objectManager =\Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $sql ="SELECT count(*) as total FROM magenest_movie";
        $row=$connection->fetchAll($sql);
        foreach ($row as $value)
        {
            $this->setValue($value['total']);
        }
    }*/
    public function _afterLoad()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $movieCollection = $objectManager->create('\Magenest\Movie\Model\ResourceModel\Movie\Collection');
        $result = $movieCollection->count();
        return $this->setValue($result);
    }
}