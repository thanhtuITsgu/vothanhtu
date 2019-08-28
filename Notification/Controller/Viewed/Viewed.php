<?php
namespace Magenest\Notification\Controller\Viewed;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Session\SessionManagerInterface;
class Viewed extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->create('Magento\Customer\Model\Session');
        if ($customerSession->isLoggedIn()) {
            $customerId = $customerSession->getCustomerId();
            $Data = $this->getRequest()->getPostValue();
            $customer = $objectManager->create('Magento\Customer\Model\Customer')->load($customerId);
            $data_viewed = $customer->getNotification_viewed();
            $data_viewed2 = $data_viewed.','.$Data['Id'];
            $customer->setNotification_viewed($data_viewed2) ;
            $customer->save() ;
            $this->_redirect('notification/index/index');
        }
    }
}
