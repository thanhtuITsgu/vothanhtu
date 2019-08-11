<?php
namespace Magenest\Customer\Controller\Save;
class Save extends \Magento\Backend\App\Action
{
    public function execute()
    {

        /*$MovieData = $this->getRequest()->getParam('contact');
        if(is_array($MovieData)) {
            $contact = $this->_objectManager->create('Magenest\Movie\Model\Movie');
            $contact->setData($MovieData)->save();
            $this->getResponse()->setBody('success');*/

        $MovieData = $this->getRequest()->getPostValue();
        $id=$this->getRequest()->getParam('id');
        $customer = $this->_objectManager->create('Magenest\Customer\Model\Customer');
        $collection = $customer->load($id);
        $collection->setFirstname($this->getRequest()->getParam('fistname'));
        $collection->setLastname($this->getRequest()->getParam('lastname'));
        $collection->save();
        $this->_redirect('customer/edit');
    }
}

