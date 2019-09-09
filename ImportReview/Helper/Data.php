<?php
namespace Magenest\ImportReview\Helper;
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public function getTitle()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $ModelCategory = $objectManager->create('Magento\Catalog\Model\Category')->load(3);
        $id = $ModelCategory['Cms_New'];
        $ModelCms = $objectManager->create('Magento\Cms\Model\Block')->load($id);
        return $ModelCms->getIdentifier();
    }

}