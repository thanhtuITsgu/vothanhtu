<?php

namespace Magenest\ImportCategory\Model\Import;

use Magenest\ImportCategory\Model\Import\CategoryImport\RowValidatorInterface as ValidatorInterface;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\ImportExport\Model\Import\AbstractSource;
use Magento\ImportExport\Model\Import as ImportExport;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingError;

class CategoryImport extends \Magento\ImportExport\Model\Import\Entity\AbstractEntity
{
    const id = 'id';
    const parent_name = 'parent_name';
    const name = 'name';
    const description = 'description';
    const all_children = 'all_children';
    const available_sort_by = 'available_sort_by';
    const children = 'children';
    const children_count = 'children_count';
    const custom_apply_to_products = 'custom_apply_to_products';
    const custom_design = 'custom_design';
    const custom_design_from = 'custom_design_from';
    const custom_design_to = 'custom_design_to';
    const custom_layout_update = 'custom_layout_update';
    const default_sort_by = 'default_sort_by';
    const display_mode = 'display_mode';
    const filter_price_range = 'filter_price_range';
    const image = 'image';
    const is_anchor = 'is_anchor';
    const landing_page = 'landing_page';
    const level = 'level';
    const meta_description = 'meta_description';
    const meta_keywords = 'meta_keywords';
    const meta_title = 'meta_title';
    const page_layout = 'page_layout';
    const url_key = 'url_key';
    const url_path = 'url_path';
    const is_active = 'is_active';
    const position = 'position';
    const include_in_menu = 'include_in_menu';
    const test = 'test';
    const abcdez = 'abcdez';
    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = [
        ValidatorInterface::ERROR_MESSAGE_IS_EMPTY => 'Message is empty',
        ValidatorInterface::ERROR_NAME_INVALID,
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
        self::parent_name,
        self::name,
        self::description,
        self::all_children,
        self::available_sort_by,
        self::children,
        self::children_count,
        self::custom_apply_to_products,
        self::custom_design,
        self::custom_design_from,
        self::custom_design_to,
        self::custom_layout_update,
        self::default_sort_by,
        self::display_mode,
        self::filter_price_range,
        self::image,
        self::is_anchor,
        self::landing_page,
        self::level,
        self::meta_description,
        self::meta_keywords,
        self::meta_title,
        self::page_layout,
        self::url_key,
        self::url_path,
        self::is_active,
        self::position,
        self::include_in_menu,
        self::test,
        self::abcdez,
    ];
    /**
     * Need to log in import history
     *
     * @var bool
     */
    protected $logInHistory = true;
    protected $_validators = [];
    protected $categoryFactory;
    protected $categoryModel;
    protected $categoryResource;
    protected $storeManager;
    protected $storeID;
    protected $productCollectionFactory;
    protected $messageManager;
    protected $_repository;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_connection;
    protected $_resource;

    private $serializer;

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
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Model\ResourceModel\Category $categoryResource,
        \Magento\Store\Model\StoreManager $storeManager,
        \Magento\Catalog\Model\ProductFactory $productCollectionFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Catalog\Model\ResourceModel\Category\Collection $categoryModel,
        \Magento\Catalog\Api\CategoryRepositoryInterface $repository
    )
    {
        $this->jsonHelper = $jsonHelper;
        $this->_importExportData = $importExportData;
        $this->_resourceHelper = $resourceHelper;
        $this->_dataSourceModel = $importData;
        $this->_resource = $resource;
        $this->_connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $this->errorAggregator = $errorAggregator;
        $this->categoryFactory = $categoryFactory;
        $this->categoryResource = $categoryResource;
        $this->storeID = $storeManager->getStore()->getId();
        $this->productCollectionFactory = $productCollectionFactory;
        $this->messageManager = $messageManager;
        $this->categoryModel = $categoryModel;
        $this->_repository = $repository;
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
        return 'category_import';
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
        /*Test Name category co trong model category khong*/
        /*  $productFactory = $this->categoryFactory->create()
              ->getCollection()
              ->addAttributeToFilter('name', $params['name']);
          if (count($productFactory) != 0) {
              $this->addRowError(ValidatorInterface::ERROR_NAME_INVALID, $rowNum);
          }*/
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
        $this->saveAndDeleteEntity();
        $this->saveAndReplaceEntity();
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
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            foreach ($bunch as $rowNum => $rowData) {
                if ($this->validateRow($rowData, $rowNum)) {
                    {
                        $colection = $this->categoryFactory->create()->getCollection()->addAttributeToFilter('name', $rowData['parent_name']);
                        $parent_id = $colection->getFirstItem()->getId();
                        $data = ['data' => ["parent_id" => $parent_id]];
                        foreach ($rowData as $key => $value) {
                            if ($key != 'id' && $key != 'parent_name') {
                                $data['data'][$key] = $value;
                            }
                        }
                        $category = $this->categoryFactory->create($data);
                        $this->_repository->save($category);
                    }
                }
            }
        }
        return $this;
    }

    protected function saveAndDeleteEntity()
    {
        $review = $this->categoryFactory->create();
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            foreach ($bunch as $rowNum => $rowData) {
                $source = $review->getCollection()->addAttributeToFilter('name', $rowData['name'])->getData();
                if ($source != 0) {
                    foreach ($source as $key => $value) {
                        $review->load($value['entity_id']);
                        $review->delete();
                    }
                }
            }
        }
        return $this;
    }

    protected function _getSource()
    {
        if (!$this->_source) {
            throw new LocalizedException(__('Please specify a source.'));
        }
        return $this->_source;
    }

    private function getSerializer()
    {
        if (null === $this->serializer) {
            $this->serializer = ObjectManager::getInstance()->get(Json::class);
        }
        return $this->serializer;
    }

    protected function _saveValidatedBunches()
    {
        $source = $this->_getSource();
        $currentDataSize = 0;
        $bunchRows = [];
        $startNewBunch = false;
        $nextRowBackup = [];
        $maxDataSize = $this->_resourceHelper->getMaxDataSize();
        $bunchSize = $this->_importExportData->getBunchSize();
        $skuSet = [];

        $source->rewind();
        $this->_dataSourceModel->cleanBunches();

        while ($source->valid() || $bunchRows) {
            if ($startNewBunch || !$source->valid()) {
                $this->_dataSourceModel->saveBunch($this->getEntityTypeCode(), $this->getBehavior(), $bunchRows);

                $bunchRows = $nextRowBackup;
                $currentDataSize = strlen($this->getSerializer()->serialize($bunchRows));
                $startNewBunch = false;
                $nextRowBackup = [];
            }
            if ($source->valid()) {
                try {
                    $rowData = $source->current();
                    if (array_key_exists('sku', $rowData)) {
                        $skuSet[$rowData['sku']] = true;
                    }
                } catch (\InvalidArgumentException $e) {
                    $this->addRowError($e->getMessage(), $this->_processedRowsCount);
                    $this->_processedRowsCount++;
                    $source->next();
                    continue;
                }

                $this->_processedRowsCount++;

                if ($this->validateRow($rowData, $source->key())) {
                    // add row to bunch for save
                    $rowData = $this->_prepareRowForDb($rowData);
                    $rowSize = strlen($this->jsonHelper->jsonEncode($rowData));

                    $isBunchSizeExceeded = $bunchSize > 0 && count($bunchRows) >= $bunchSize;

                    if ($currentDataSize + $rowSize >= $maxDataSize || $isBunchSizeExceeded) {
                        $startNewBunch = true;
                        $nextRowBackup = [$source->key() => $rowData];
                    } else {
                        $bunchRows[$source->key()] = $rowData;
                        $currentDataSize += $rowSize;
                    }
                }
                $source->next();
            }
        }
        $this->_processedEntitiesCount = (count($skuSet)) ?: $this->_processedRowsCount;

        return $this;
    }
}