<?php
namespace Magenest\Location\Controller\Quan;
class Quan extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $url="https://thongtindoanhnghiep.co/api/city/"."$id"."/district";
        $file = file_get_contents($url);
        echo json_encode($file) ;
        return ;
    }
}
