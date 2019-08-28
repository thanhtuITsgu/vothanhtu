<?php

namespace Magenest\Notification\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Session\SessionManagerInterface;

class CustomerLogin implements ObserverInterface
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
        $customer = $observer->getEvent()->getCustomer();
        $received = $customer->getNotification_received();
        $viewed = $customer->getNotification_viewed();
        $deleted = $customer->getNotification_deleted();
        $Array = explode( ',',$received);
        $Erray = explode( ',',$viewed);
        $Orray = explode( ',',$deleted);
        $Collection = $objectManager->create('Magenest\Notification\Model\ResourceModel\Promo\Collection');
        $Collection1 = $Collection->addFieldToFilter('entity_id',array('in'=>$Array));
        $Collection2 = $Collection1->addFieldToFilter('entity_id',array('nin'=>$Orray));
        $Collection3 = $Collection2->addFieldToFilter('entity_id',array('nin'=>$Erray));
        $count = $Collection3->count();
        $metadata = $this->_cookieMetadataFactory->createPublicCookieMetadata()->setDuration(self::COOKIE_DURATION)->setPath($this->_sessionManager->getCookiePath())->setDomain($this->_sessionManager->getCookieDomain());
        $this->_cookieManager->setPublicCookie(self::Count, $count, $metadata);
    }
}