<?php

namespace Magenest\Location\Controller\Save;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Session\SessionManagerInterface;

class Save extends \Magento\Framework\App\Action\Action
{
    const City = 'City';
    const District = 'District';
    const Village = 'Village';
    const COOKIE_DURATION = 300; // 5p
    /**
     * @var CookieManagerInterface
     */
    protected $_cookieManager;

    /**
     * @var CookieMetadataFactory
     */
    protected $_cookieMetadataFactory;


    protected $_sessionManager;

    /**
     * @param Context $context
     * @param CookieManagerInterface $cookieManager
     * @param CookieMetadataFactory $cookieMetadataFactory
     */
    public function __construct(
        Context $context,
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        SessionManagerInterface $sessionManager
    )
    {
        $this->_cookieManager = $cookieManager;
        $this->_cookieMetadataFactory = $cookieMetadataFactory;
        $this->_sessionManager = $sessionManager;
        parent::__construct($context);
    }

    public function execute()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->create('Magento\Customer\Model\Session');
        if ($customerSession->isLoggedIn()) {
            $customerId = $customerSession->getId();
            $customer = $objectManager->create('Magento\Customer\Model\Address')->load($customerId);
            $customer->setCity($this->getRequest()->getParam('City'));
            $customer->setRegion($this->getRequest()->getParam('District'));
            $customer->setStreet($this->getRequest()->getParam('Village'));
            $customer->save();
        } else {
            $metadata = $this->_cookieMetadataFactory->createPublicCookieMetadata()->setDuration(self::COOKIE_DURATION)->setPath($this->_sessionManager->getCookiePath())
                ->setDomain($this->_sessionManager->getCookieDomain());;
            $City = $this->getRequest()->getParam('City');
            $District = $this->getRequest()->getParam('District');
            $Village = $this->getRequest()->getParam('Village');

            $this->_cookieManager->setPublicCookie(self::City, $City, $metadata);
            $this->_cookieManager->setPublicCookie(self::District, $District, $metadata);
            $this->_cookieManager->setPublicCookie(self::Village, $Village, $metadata);
        }
    }
}