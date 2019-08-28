<?php
namespace Magenest\Notification\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Session\SessionManagerInterface;
class Status implements \Magento\Framework\Event\ObserverInterface
{
    const Count = 'Count';
    const COOKIE_DURATION = 86400; // 5p

    protected $_cookieManager;

    protected $_cookieMetadataFactory;

    protected $_sessionManager;

    public function __construct(
        /* Context $context,*/
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        SessionManagerInterface $sessionManager
    )
    {
        $this->_cookieManager = $cookieManager;
        $this->_cookieMetadataFactory = $cookieMetadataFactory;
        $this->_sessionManager = $sessionManager;
        /*   parent::__construct($context);*/
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->create('Magento\Customer\Model\Session');
        $model = $observer->getModel();
        $entity_id = $model->getId();
        $status = $model->getStatus();
        if ($status == "Enable") {
            $collection = $objectManager->create('\Magento\Customer\Model\ResourceModel\Customer\Collection');
            $data = $objectManager->create('\Magento\Customer\Model\Customer')->load(2);
            $IdNotificationTmp = $data->getNotification_received();
            $IdNotificationTmp = $IdNotificationTmp . ',' . $entity_id;
            $collection->setDataToAll('notification_received', $IdNotificationTmp)->save();
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