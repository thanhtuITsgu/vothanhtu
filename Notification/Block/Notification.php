<?php
namespace Magenest\Notification\Block;

Use Magento\Framework\View\Element\Template;
Use Magento\Customer\Model\Session;
Use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\App\ObjectManager;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Zend\Db\Sql\Ddl\Column\Varchar;

class Notification extends Template
{
    protected $_sessionManager;

    public function __construct(
        Context $context,
        Session $session
    )
    {
        $this->_sessionManager = $session;
        parent::__construct($context);
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        if ($this->_sessionManager->isLoggedIn()) {
            $customer = $this->_sessionManager->getCustomer();
            $received = $customer->getNotification_received();
            $deleted = $customer->getNotification_deleted();
            $Array = explode( ',',$received);
            $Erray = explode( ',',$deleted);
            $Collection = $objectManager->create('Magenest\Notification\Model\ResourceModel\Promo\Collection');
            $Collection1 = $Collection->addFieldToFilter('entity_id',array('in'=>$Array));
            $Collection2 = $Collection1->addFieldToFilter('entity_id',array('nin'=>$Erray));
            $this->setCollection($Collection2);
        }

        if ($this->getCollection()) {
            // create pager block for collection
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'notidication.record.pager'
            )/*->setAvailableLimit(array(10=>10,15=>15,15=>15))*/->setCollection(
                $this->getCollection() // assign collection to pager
            );
            $this->setChild('pager', $pager);// set pager block in layout
        }
        return $this;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function getViewEd()
    {
        if ($this->_sessionManager->isLoggedIn()) {
            $customer =$this->_sessionManager->getCustomer();
            $String = $customer->getNotification_viewed();
            $Array = explode( ',',$String);
            return $Array ;
        }
    }

    public function getDeleted()
    {
        if ($this->_sessionManager->isLoggedIn()) {
            $customer = $this->_sessionManager->getCustomer();
            $String = $customer->getNotification_deleted();
            $Array = explode( ',',$String);
            return $Array ;
        }
    }

    public function testRedirect($string){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $redirect = $objectManager->get('\Magento\Framework\App\Response\Http');
        $redirect->setRedirect($string);
            return $redirect ;
    }

    public function getLabel(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        if ($this->_sessionManager->isLoggedIn()) {
            $customer = $this->_sessionManager->getCustomer();
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
            $count = $Collection3->count() ;
            return $count ;
        }
        /*$label = "My Notification";
        return $label." ".$count;*/
    }
}