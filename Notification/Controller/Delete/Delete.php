<?php
namespace Magenest\Notification\Controller\Delete ;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Session\SessionManagerInterface;
class Delete extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->create('Magento\Customer\Model\Session');
        if ($customerSession->isLoggedIn()) {
            $customerId = $customerSession->getCustomerId();
            $Data = $this->getRequest()->getPostValue();
            $customer = $objectManager->create('Magento\Customer\Model\Customer')->load($customerId);
            /*$data_viewed = $customer->getNotification_received();
            $Array = explode( ',',$data_viewed);
            $Array = array_diff($Array, array($Data['Id']));
            $data_viewed2 = implode(',', $Array);
            $customer->setNotification_received($data_viewed2) ;*/
            $data_deleted = $customer->getNotification_deleted();
            $data_deleted2= $data_deleted.','.$Data['Id'];
            $customer->setNotification_deleted($data_deleted2) ;
            $customer->save() ;
            $this->_redirect('notification/index/index');
        }
    }
}
