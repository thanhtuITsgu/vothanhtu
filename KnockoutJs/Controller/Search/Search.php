<?php

namespace Magenest\KnockoutJs\Controller\Search;

use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Helper\Image;
use Magento\Store\Model\StoreManager;

class Search extends \Magento\Framework\App\Action\Action
{
    protected $productFactory;
    protected $imageHelper;
    protected $listProduct;
    protected $_storeManager;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Data\Form\FormKey $formKey,
        ProductFactory $productFactory,
        StoreManager $storeManager,
        Image $imageHelper
    )
    {
        $this->productFactory = $productFactory;
        $this->imageHelper = $imageHelper;
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    /*    public function getCollection()
        {
            return $this->productFactory->create()
                ->getCollection()
                ->addAttributeToSelect('*')
                ->setPageSize(5)
                ->setCurPage(1);
        }*/

    public function execute()
    {

        if ($name = $this->getRequest()->getParam('name')) {
            /* $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
             $ProductCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
             $result=$ProductCollection->addAttributeToFilter('name', array('like' => '%'.$name.'%'))->count();*/


            $product = $this->productFactory->create()->getCollection()->addAttributeToSelect([
                'name',
                'price', 'image'])->addAttributeToFilter('name', array('like' => '%' . $name . '%'));
            $productResult = $product->getItems();
            $productList = [];
            foreach ($productResult as $value) {
                $productData = [
                    'entity_id' => $value->getId(),
                    'name' => $value->getName(),
                    'price' => '$' . $value->getPrice(),
                    'src' => '/magento2/pub/media/catalog/product' . $value->getImage()
                ];
                $productList[] = $productData;
            }
            echo json_encode($productList);
            return;
        }
    }
}