<?php
namespace Magenest\Movie\Model\Config\Backend;

class Numrowactor extends \Magento\Framework\App\Config\Value
{
    /*  public function getValue()
      {
          $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
          $movieCollection = $objectManager->create('\Magenest\Movie\Model\ResourceModel\Movie\Collection');
          $result = $movieCollection->getSelectCountSql();

          return (int)$this->setValue($result);
      }*/
    public function _afterLoad()
    {
        $objectManager =\Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $sql ="SELECT count(*) as total FROM magenest_actor";
        $row=$connection->fetchAll($sql);
        foreach ($row as $value)
        {
            $this->setValue($value['total']);
        }
    }
}