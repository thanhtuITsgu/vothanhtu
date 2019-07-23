<?php
namespace Magenest\Movie\Controller\Adminhtml\Formui;
class Index extends \Magento\Backend\App\Action
{
    public function execute()
    {

        /*$MovieData = $this->getRequest()->getParam('contact');
        if(is_array($MovieData)) {
            $contact = $this->_objectManager->create('Magenest\Movie\Model\Movie');
            $contact->setData($MovieData)->save();
            $this->getResponse()->setBody('success');*/
             $MovieData = $this->getRequest()->getPostValue();
             $contact = $this->_objectManager->create('Magenest\Movie\Model\Movie');

             $contact->setName($this->getRequest()->getParam('name'));
             $contact->setDescription($this->getRequest()->getParam('description'));
             $contact->setRating($this->getRequest()->getParam('rating'));
             $contact->setDirector_id($this->getRequest()->getParam('director_id'));
             $contact->save();
             $this->messageManager->addSuccess(__('Add Succesful'));
             $this->_redirect('movie/uicomponentmovie');
        }
}

