<?php
namespace Magenest\Notification\Observer;
class StatusEdit implements \Magento\Framework\Event\ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $model = $observer->getModel();
        $entity_id = $model->getId();
        $status = $model->getStatus();
        if ($status == "Enable") {
            $collection= $objectManager->create('\Magento\Customer\Model\ResourceModel\Customer\Collection');
            $data = $objectManager->create('\Magento\Customer\Model\Customer')->load(2);
            $IdNotificationTmp = $data->getNotification_received();
            $IdNotificationTmp = $IdNotificationTmp.','.$entity_id;
            $collection->setDataToAll('notification_received',$IdNotificationTmp)->save();
        }
    }
}
/*$count = $collection->count() ;
$i=1 ;
while($i <= $count) {
    $data = $objectManager->create('\Magento\Customer\Model\Customer')->load($i);
    $IdNotificationTmp = $data->getNotification_received();
    $IdNotificationTmp = $IdNotificationTmp . ',' . $entity_id;
    $data->setNotification_received($IdNotificationTmp);
    $data->save() ;
    $i++;
}*/