<?php

namespace Magenest\ReviewImport\Controller\Adminhtml\Index;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\ResultFactory;
USE \Magento\Review\Model\Review;

class Import extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    protected $uploaderFactory;

    protected $varDirectory;

    protected $csvProcessor;

    protected $storeID;

    protected $productCollectionFactory;

    protected $reviewFactory;

    protected $customerFactory;

    protected $errorArray;

    protected $reviewProductEntityId;

    protected $reviewResource;

    protected $ratingFactory;

    protected $objectManager;

    /**
     * @var array
     */
    protected $ratings;

    protected $directoryList;


    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\File\Csv $csvProcessor,
        \Magento\Store\Model\StoreManager $storeManager,
        \Magento\Review\Model\ReviewFactory $reviewFactory,
        \Magento\Review\Model\ResourceModel\Review $reviewResource,
        \Magento\Review\Model\RatingFactory $ratingFactory,
        \Magento\Catalog\Model\ProductFactory $productCollectionFactory,
        \Magento\Customer\Model\CustomerFactory $customerFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->uploaderFactory = $uploaderFactory;
        $this->varDirectory = $filesystem->getDirectoryWrite(DirectoryList::VAR_DIR); // Get default 'var' directory
        $this->csvProcessor = $csvProcessor;
        $this->storeID = $storeManager->getStore()->getId();
        $this->reviewFactory = $reviewFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->customerFactory = $customerFactory;
        $this->ratingFactory = $ratingFactory;
        $this->reviewResource = $reviewResource;

        $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    }


    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $review = $this->reviewFactory->create();

        $temp = 0 ;
        $params = $this->getRequest()->getFiles('reviews_import_file');
        $type = ["text/csv"];
        if($params['size']===0) {
            $resultRedirect->setPath('*/*/') ;
            $this->messageManager->addErrorMessage("File is Empty");
        } else {

            if (in_array($params['type'], $type)) {
                $importRawData = $this->csvProcessor
                    ->setDelimiter(',')
                    ->setEnclosure('"')
                    ->getData($params['tmp_name']);

                foreach ($importRawData as $key => $dataRaw) {
                    if ($key != 0) {
                        $data = $this->getArrayData($importRawData[0], $dataRaw); //De gan gia tri row[0] cho cac bien !!!
                        if ($this->validateDataImport($data, $review)) {
                            $source= $review->setEntityId($review->getEntityIdByCode(\Magento\Review\Model\Review::ENTITY_PRODUCT_CODE))
                                ->setEntityPkValue($data['product_id'])
                                ->setStatusId($data['status'])
                                ->setCreatedAt($data['create_at'])
                                ->setData('nickname', $data['nickname'])
                                ->setData('title', $data['title'])
                                ->setData('detail', $data['detail'])
                                ->setData('store_id',$data['store_id'])
                                ->setStores([$this->storeID]);
                            $this->reviewResource->save($source);
                            $review->unsetData(); // de xoa Data trong Review['Data'] sau moi lan them !!!
                            $temp ++;
                        }
                    }
                }
                $resultRedirect->setPath('*/*/');

                if ($temp != 0) {
                    echo $temp;
                    $this->messageManager->addNoticeMessage(__('Import %1 review successful!', $temp));
                }
                else {
                    $this->messageManager->addErrorMessage("Can\'t Import Review!");
                }

            } else {
                $resultRedirect->setPath('*/*/');
                $this->messageManager->addErrorMessage("Please select file type csv for import!");
            }
        }
        return $resultRedirect;
    }

    public function validateDataImport($params, $model){
        $valid = true;
        $errList = array();
        foreach($params as $key=>$data){
            if($data == ""){
                $params[$key] = null;
            }
        }
        if ($this->emailValid($params['email']) == false)
        {
            $this->messageManager->addErrorMessage(__('The Email %1 invalid. Please use other Email', $params['email']));
            $valid = false;
        }
        if( !isset($params['sku'],
            $params['email'],
            $params['nickname'],
            $params['detail'],
            $params['title'],
            $params['create_at'],
            $params['status']))
        {
            $valid = false;
            $this->messageManager->addErrorMessage(__('Please complete required fields!'));
        }
        else {

            /*//check nickname exist ?
            $existAddressLocation = $model
                ->getCollection()
                ->addFieldToFilter('nickname', $params['nickname']);
            if (count($existAddressLocation) > 0) {
                $this->messageManager->addErrorMessage(__('The Nickname %1 already exist. Please use other Nickname', $params['nickname']));
                $valid = false;
            }*/

            //check product_id ( entity_id )

            $productFactory = $this->productCollectionFactory->create()
                ->getCollection()
                ->addFieldToFilter('entity_id', $params['product_id']);
            if (count($productFactory) == 0) {
                $this->messageManager->addErrorMessage(__('Product id %1 not exist. Please use other Product', $params['product_id']));
                $valid = false;
            }
        }
        return $valid;
    }

    public function emailValid($string)
    {
        if (preg_match ("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+\.[A-Za-z]{2,6}$/", $string))
            return true;
        return false;
    }

    public function getArrayData($arrayIndex, $arrayValue) //Gán product_id = '1',sku ='Bag men 1' ,.... Nếu không nó sẽ '0'='1' , 1='Bag M1en'
    {
        $data = array();
        foreach ($arrayIndex as $key => $index) {
            $data += array(
                $index=>$arrayValue[$key]
            );
        }
        return $data;
    }
}



