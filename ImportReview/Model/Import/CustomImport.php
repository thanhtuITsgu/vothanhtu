<?php

namespace Magenest\ImportReview\Model\Import;

use Magenest\ImportReview\Model\Import\CustomImport\RowValidatorInterface as ValidatorInterface;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;

class CustomImport extends \Magento\ImportExport\Model\Import\Entity\AbstractEntity
{
    const id = 'id';
    const product_id = 'product_id';
    const sku = 'sku';
    const email = 'email';
    const nickname = 'nickname';
    const title = 'title';
    const detail = 'detail';
    const create_at = 'create_at';
    const status = 'status';
    const store_id = 'store_id';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = [
        ValidatorInterface::ERROR_MESSAGE_IS_EMPTY => 'Message is empty',
        ValidatorInterface::ERROR_PRODUCT_ID_INVALID => 'Product_Id Invalid',
        ValidatorInterface::ERROR_ROW => 'Missing column',
        ValidatorInterface::SUCCESS,
        ValidatorInterface::ERROR_EMAIL_INVALID
    ];
    /*  protected $_permanentAttributes = [self::ID];*/
    /**
     * If we should check column names
     *
     * @var bool
     */
    protected $needColumnCheck = true;
    /**
     * Valid column names
     *
     * @array
     */
    protected $validColumnNames = [
        self::id,
        self::product_id,
        self::sku,
        self::email,
        self::nickname,
        self::title,
        self::detail,
        self::create_at,
        self::status,
        self::store_id
    ];
    /**
     * Need to log in import history
     *
     * @var bool
     */
    protected $logInHistory = true;
    protected $_validators = [];
    protected $reviewFactory;
    protected $reviewModel;
    protected $reviewResource;
    protected $storeManager;
    protected $storeID;
    protected $productCollectionFactory;
    protected $messageManager;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_connection;
    protected $_resource;

    /**
     * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
     */
    public function __construct(
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\ImportExport\Helper\Data $importExportData,
        \Magento\ImportExport\Model\ResourceModel\Import\Data $importData,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\ImportExport\Model\ResourceModel\Helper $resourceHelper,
        \Magento\Framework\Stdlib\StringUtils $string,
        ProcessingErrorAggregatorInterface $errorAggregator,
        \Magento\Review\Model\ReviewFactory $reviewFactory,
        \Magento\Review\Model\ResourceModel\Review $reviewResource,
        \Magento\Store\Model\StoreManager $storeManager,
        \Magento\Catalog\Model\ProductFactory $productCollectionFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Review\Model\ResourceModel\Review\Collection $reviewModel
    )
    {
        $this->jsonHelper = $jsonHelper;
        $this->_importExportData = $importExportData;
        $this->_resourceHelper = $resourceHelper;
        $this->_dataSourceModel = $importData;
        $this->_resource = $resource;
        $this->_connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $this->errorAggregator = $errorAggregator;
        $this->reviewFactory = $reviewFactory;
        $this->reviewResource = $reviewResource;
        $this->storeID = $storeManager->getStore()->getId();
        $this->productCollectionFactory = $productCollectionFactory;
        $this->messageManager = $messageManager;
        $this->reviewModel = $reviewModel ;
    }

    public function getValidColumnNames()
    {
        return $this->validColumnNames;
    }

    /**
     * Entity type code getter.
     *
     * @return string
     */
    public function getEntityTypeCode()
    {
        return 'review_importer';
    }

    /**
     * Row validation.
     *
     * @param array $rowData
     * @param int $rowNum
     * @return bool
     */
    public function validateRow(array $params, $rowNum)
    {
        if (isset($this->_validatedRows[$rowNum])) {
            return !$this->getErrorAggregator()->isRowInvalid($rowNum);
        }
        /*Test Product_Id co trong model khong*/
        $productFactory = $this->productCollectionFactory->create()
            ->getCollection()
            ->addFieldToFilter('entity_id', $params['product_id']);
        if (count($productFactory) == 0) {
            $this->addRowError(ValidatorInterface::ERROR_PRODUCT_ID_INVALID, $rowNum);
        }
        /*Test Email*/
        if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+\.[A-Za-z]{2,6}$/", $params['email'])) {
            $this->addRowError(ValidatorInterface::ERROR_EMAIL_INVALID, $rowNum);
        }
        return !$this->getErrorAggregator()->isRowInvalid($rowNum);
    }

    /**
     * Create Advanced message data from raw data.
     *
     * @throws \Exception
     * @return bool Result of operation.
     */
    protected function _importData()
    {
        if (\Magento\ImportExport\Model\Import::BEHAVIOR_DELETE == $this->getBehavior()) {
            $this->deleteProductReview();
        } elseif (\Magento\ImportExport\Model\Import::BEHAVIOR_REPLACE == $this->getBehavior()) {
            $this->replaceProductReview();
        } elseif (\Magento\ImportExport\Model\Import::BEHAVIOR_APPEND == $this->getBehavior()) {
            $this->saveProductReview();
        }

        return true;
    }

    public function deleteProductReview()
    {
        $this->saveAndDeleteEntity();
        return $this;
    }

    public function saveProductReview()
    {
        $this->saveAndReplaceEntity();
        return $this;
    }

    public function replaceProductReview()
    {
       $this->DeleteEntity();
       $this->ReplaceEntity();
       return $this;
    }

    /**
     * Save Message
     *
     * @return $this
     */

    /**
     * Save and replace data message
     *
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function saveAndReplaceEntity()
    {
        $review = $this->reviewFactory->create();
        $reviewModel = $this->reviewModel ;
        $temp = 0;
        $behavior = $this->getBehavior();
        $listTitle = [];
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            foreach ($bunch as $rowNum => $rowData) {

                /*if (\Magento\ImportExport\Model\Import::BEHAVIOR_REPLACE== $behavior) {
                    if ($this->validateRow($rowData, $rowNum)) {
                        $source = $review->getCollection()->addFieldToFilter('title', $rowData['title'])->getData();
                        foreach ($source as $key => $value) {
                            $review->load($value['review_id']);
                            $review->delete();
                            $review->save();
                        }
                        $data = $review->setEntityId($review->getEntityIdByCode(\Magento\Review\Model\Review::ENTITY_PRODUCT_CODE))
                            ->setEntityPkValue($rowData['product_id'])
                            ->setStatusId($rowData['status'])
                            ->setCreatedAt($rowData['create_at'])
                            ->setData('nickname', $rowData['nickname'])
                            ->setData('title', $rowData['title'])
                            ->setData('detail', $rowData['detail'])
                            ->setData('store_id', $rowData['store_id'])
                            ->setStores([$this->storeID]);
                        $this->reviewResource->save($data);
                        $review->unsetData(); // de xoa Data trong Review['Data'] sau moi lan them !!!
                    }
                }*/

                if (\Magento\ImportExport\Model\Import::BEHAVIOR_APPEND == $behavior) {
                    if ($this->validateRow($rowData, $rowNum)) {
                        $source = $review->setEntityId($review->getEntityIdByCode(\Magento\Review\Model\Review::ENTITY_PRODUCT_CODE))
                            ->setEntityPkValue($rowData['product_id'])
                            ->setStatusId($rowData['status'])
                            ->setCreatedAt($rowData['create_at'])
                            ->setData('nickname', $rowData['nickname'])
                            ->setData('title', $rowData['title'])
                            ->setData('detail', $rowData['detail'])
                            ->setData('store_id', $rowData['store_id'])
                            ->setStores([$this->storeID]);
                            $this->reviewResource->save($source);
                        $review->unsetData(); // de xoa Data trong Review['Data'] sau moi lan them !!!
                        $temp++;
                    }
                }
            }
        }
        return $this;
    }

    protected function ReplaceEntity()
    {
        $review = $this->reviewFactory->create();
        $reviewModel = $this->reviewModel ;
        $temp = 0;
        $behavior = $this->getBehavior();
        $listTitle = [];
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            foreach ($bunch as $rowNum => $rowData) {

                /*if (\Magento\ImportExport\Model\Import::BEHAVIOR_REPLACE== $behavior) {
                    if ($this->validateRow($rowData, $rowNum)) {
                        $source = $review->getCollection()->addFieldToFilter('title', $rowData['title'])->getData();
                        foreach ($source as $key => $value) {
                            $review->load($value['review_id']);
                            $review->delete();
                            $review->save();
                        }
                        $data = $review->setEntityId($review->getEntityIdByCode(\Magento\Review\Model\Review::ENTITY_PRODUCT_CODE))
                            ->setEntityPkValue($rowData['product_id'])
                            ->setStatusId($rowData['status'])
                            ->setCreatedAt($rowData['create_at'])
                            ->setData('nickname', $rowData['nickname'])
                            ->setData('title', $rowData['title'])
                            ->setData('detail', $rowData['detail'])
                            ->setData('store_id', $rowData['store_id'])
                            ->setStores([$this->storeID]);
                        $this->reviewResource->save($data);
                        $review->unsetData(); // de xoa Data trong Review['Data'] sau moi lan them !!!
                    }
                }*/

                    if ($this->validateRow($rowData, $rowNum)) {
                        $source = $review->setEntityId($review->getEntityIdByCode(\Magento\Review\Model\Review::ENTITY_PRODUCT_CODE))
                            ->setEntityPkValue($rowData['product_id'])
                            ->setStatusId($rowData['status'])
                            ->setCreatedAt($rowData['create_at'])
                            ->setData('nickname', $rowData['nickname'])
                            ->setData('title', $rowData['title'])
                            ->setData('detail', $rowData['detail'])
                            ->setData('store_id', $rowData['store_id'])
                            ->setStores([$this->storeID]);
                        $this->reviewResource->save($source);
                        $review->unsetData(); // de xoa Data trong Review['Data'] sau moi lan them !!!
                        $temp++;
                    }
            }
        }
        return $this;
    }


    protected function saveAndDeleteEntity()
    {
        $review = $this->reviewFactory->create();
        $reviewModel = $this->reviewModel ;
        $temp = 0;
        $behavior = $this->getBehavior();
        $listTitle = [];
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            foreach ($bunch as $rowNum => $rowData) {
                if (\Magento\ImportExport\Model\Import::BEHAVIOR_DELETE == $behavior) {
                    if ($this->validateRow($rowData, $rowNum)) {
                        $source = $review->getCollection()->addFieldToFilter('title', $rowData['title'])->getData();
                        foreach ($source as $key=>$value)
                        {
                            $review->load($value['review_id']);
                            $review->delete();
                            $review->save();
                        }
                    }
                }
            }
        }
        return $this;
    }
    protected function DeleteEntity()
    {
        $review = $this->reviewFactory->create();
        $reviewModel = $this->reviewModel ;
        $temp = 0;
        $behavior = $this->getBehavior();
        $listTitle = [];
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            foreach ($bunch as $rowNum => $rowData) {
                    if ($this->validateRow($rowData, $rowNum)) {
                        $source = $review->getCollection()->addFieldToFilter('title', $rowData['title'])->getData();
                        foreach ($source as $key=>$value)
                        {
                            $review->load($value['review_id']);
                            $review->delete();
                            $review->save();
                        }
                    }
            }
        }
        return $this;
    }
    /*protected function saveAndReplaceEntityReplate()
    {
        $review = $this->reviewFactory->create();
        $temp = 0;
        $behavior = $this->getBehavior();
        $listTitle = [];
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            foreach ($bunch as $rowNum => $rowData) {
                    if ($this->validateRow($rowData, $rowNum)) {
                        $source = $review->getCollection()->addFieldToFilter('title', $rowData['title'])->getData();
                        foreach ($source as $key => $value) {
                            $review->load($value['review_id']);
                            $review->delete();
                            $review->save();
                        }
                    $data = $review->setEntityId($review->getEntityIdByCode(\Magento\Review\Model\Review::ENTITY_PRODUCT_CODE))
                        ->setEntityPkValue($rowData['product_id'])
                        ->setStatusId($rowData['status'])
                        ->setCreatedAt($rowData['create_at'])
                        ->setData('nickname', $rowData['nickname'])
                        ->setData('title', $rowData['title'])
                        ->setData('detail', $rowData['detail'])
                        ->setData('store_id', $rowData['store_id'])
                        ->setStores([$this->storeID]);
                    $this->reviewResource->save($data);
                    $review->unsetData(); // de xoa Data trong Review['Data'] sau moi lan them !!!
                    $temp++;
                }
            }
        }
        return $this;
    }*/


    public function validateDataImport($params, $rowNum)
    {

        $Temp = true;
        foreach ($params as $key => $data) {
            if ($data == "") {
                $params[$key] = null;
            }
        }

        /*if ($this->emailValid($params['email']) == false)
        {
            $this->errorMessageTemplates(__('The Email %1 invalid. Please use other Email', $params['email']));
            $valid = false;
        }*/

        if (!isset($params['sku'],
            $params['email'],
            $params['nickname'],
            $params['detail'],
            $params['title'],
            $params['create_at'],
            $params['status'])) {
            $Temp = false;
            $this->addRowError(ValidatorInterface::ERROR_ROW, $rowNum);
        } else {

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
                $this->addRowError(ValidatorInterface::ERROR_PRODUCT_ID_INVALID, $rowNum);
                $Temp = false;
            }
        }
        return $Temp;
    }

    /**
     * Save message to customtable.
     *
     * @param array $priceData
     * @param string $table
     * @return $this
     */
    protected function saveEntityFinish(array $entityData)
    {
        if ($entityData) {
            foreach ($entityData as $entityRows) {
                $this->reviewResource->save($entityRows);
            }
        }
        return $this;
    }

    /*public function emailValid($string)
    {
        if (preg_match ("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+\.[A-Za-z]{2,6}$/", $string))
            return true;
        return false;
    }*/
}