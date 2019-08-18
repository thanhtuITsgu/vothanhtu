<?php
namespace Magenest\Location\Controller\Xa;
class Xa extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $url="https://thongtindoanhnghiep.co/api/district/"."$id"."/ward";
        $file = file_get_contents($url);
        echo json_encode($file);
        return;
    }
}
