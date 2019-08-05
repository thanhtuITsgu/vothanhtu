<?php
namespace Magenest\Movie\Model\Config\Source;
class Directo implements
    \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        $options[] = ['label' => '', 'value' => ''];
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $movieCollection = $objectManager->create('\Magenest\Movie\Model\ResourceModel\Director\Collection');
        foreach ($movieCollection as $Data) {
            $options[] = [
                'label' => $Data->getName(),
                'value' => $Data->getDirector_id()
            ];
        }
        return $options;
    }
}