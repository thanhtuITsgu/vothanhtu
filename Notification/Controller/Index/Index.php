<?php

namespace Magenest\Notification\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action // Co the lien ket vs View\Layout tu day ??? !
{
    /** @var \Magento\Framework\View\Result\PageFactory */
    protected $resultPageFactory;

   public function execute() {
   $this->_view->loadLayout();
   $this->_view->renderLayout();
   }

}
