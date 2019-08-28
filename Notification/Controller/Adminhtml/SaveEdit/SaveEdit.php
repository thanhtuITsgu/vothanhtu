<?php
namespace Magenest\Notification\Controller\Adminhtml\SaveEdit;

class SaveEdit extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'Index';

    protected $resultPageFactory;
    protected $contactFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magenest\Notification\Model\Promo $contactFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->contactFactory = $contactFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        $id = $data['entity_id'];
        if($id!='') {
            $contact = $this->_objectManager->create('Magenest\Notification\Model\Promo')->load($id);
        }else {
            $contact = $this->_objectManager->create('Magenest\Notification\Model\Promo');
        }
        $contact->setName($this->getRequest()->getParam('name'));
        $contact->setShort_description($this->getRequest()->getParam('short_description'));
        $contact->setStatus($this->getRequest()->getParam('status'));
        $contact->setRedirect_url($this->getRequest()->getParam('redirect_url'));
        $contact->save();
        $this->_eventManager->dispatch('Add_Notification_Status_Edit',['model'=>$contact,]);
        $this->messageManager->addSuccess(__('Succesful'));
        $this->_redirect('notification/uinotification');

    }
}
