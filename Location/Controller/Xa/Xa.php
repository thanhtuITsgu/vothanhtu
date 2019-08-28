<?php
namespace Magenest\Location\Controller\Xa;
class Xa extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $url="https://thongtindoanhnghiep.co/api/district/"."$id"."/ward";
        $file = file_get_contents($url);

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resultJsonFactory=$objectManager->create('\Magento\Framework\Controller\Result\JsonFactory');
        $resultJson = $resultJsonFactory->create();
        $resultJson->setData($file);
        return $resultJson ;
    }
}
