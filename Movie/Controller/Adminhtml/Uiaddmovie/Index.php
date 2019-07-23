<?php
namespace Magenest\Movie\Controller\Adminhtml\Uiaddmovie;

class Index extends \Magento\Backend\App\Action
{
    protected $resultPageFactory ;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend((__('UIcomponent_Add_Movie')));

        return $resultPage;
    }


}