<?php
namespace Magenest\Notification\Controller\Adminhtml\Save;
class Index extends \Magento\Backend\App\Action
{
    public function execute()
    {
             $MovieData = $this->getRequest()->getPostValue();
             $contact = $this->_objectManager->create('Magenest\Notification\Model\Promo');
             $contact->setName($this->getRequest()->getParam('name'));
             $contact->setShort_description($this->getRequest()->getParam('short_description'));
             $contact->setStatus($this->getRequest()->getParam('status'));
             $contact->setRedirect_url($this->getRequest()->getParam('redirect_url'));
             $contact->save();
             $this->_eventManager->dispatch('Add_Notification_Status',['model'=>$contact,]);
             $this->messageManager->addSuccess(__('Add Succesfulllll'));
             $this->_redirect('notification/uinotification');
        }
}

