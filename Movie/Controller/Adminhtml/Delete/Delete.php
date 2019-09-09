<?php
namespace Magenest\Movie\Controller\Adminhtml\Delete ;

use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magenest\Movie\Model\ResourceModel\Movie\CollectionFactory;

class Delete extends \Magento\Backend\App\Action
{
    public function execute()
    {
            $Data = $this->getRequest()->getPostValue();
            $Selected = $Data["selected"];
            $Model = $this->_objectManager->create('Magenest\Movie\Model\Movie')->load($Selected[0]);
            $Model->delete();
            $Model->save() ;
            $this->messageManager->addSuccess('Delete Success');
            $this->_redirect('movie/uicomponentmovie/index');
    }
}
