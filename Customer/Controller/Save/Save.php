<?php
namespace Magenest\Customer\Controller\Save;
class Save extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {

        /*$MovieData = $this->getRequest()->getParam('contact');
        if(is_array($MovieData)) {
            $contact = $this->_objectManager->create('Magenest\Movie\Model\Movie');
            $contact->setData($MovieData)->save();
            $this->getResponse()->setBody('success');*/
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $id = $this->getRequest()->getParam('id');
        $customer = $objectManager->create('Magento\Customer\Model\Customer')->load($id);
        $customer->setFirstname($this->getRequest()->getParam('fistname'));
        $customer->setLastname($this->getRequest()->getParam('lastname'));
        $customer->save();
        /*$this->_redirect('customer/edit');*/
    }
}

