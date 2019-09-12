<?php

namespace Magenest\ImportCategory\Model\Import;

use Magenest\ImportCategory\Model\Import\CustomImport\RowValidatorInterface as ValidatorInterface;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;

class CustomImport extends \Magento\ImportExport\Model\Import\Entity\AbstractEntity
{
    const id = 'id';
    const parent_id = 'parent_id';
    const parent_name = 'parent_name';
    const name = 'name';
    const sku = 'sku';
    const description = 'description';

    const all_children= 'all_children';
    const available_sort_by= 'available_sort_by';
    const children= 'children';
    const children_count= 'children_count';
    const custom_apply_to_products= 'custom_apply_to_products';

    const custom_design= 'custom_design';
    const custom_design_from= 'custom_design_from';
    const custom_design_to= 'custom_design_to';
    const custom_layout_update= 'custom_layout_update';
    const default_sort_by= 'default_sort_by';

    const display_mode= 'display_mode';
    const filter_price_range= 'filter_price_range';
    const image= 'image';
    const is_anchor= 'is_anchor';
    const landing_page= 'landing_page';

    const level= 'level';
    const meta_description= 'meta_description';
    const meta_keywords= 'meta_keywords';
    const meta_title= 'meta_title';
    const page_layout= 'page_layout';

    const path= 'path';
    const path_in_store= 'path_in_store';
    const url_key= 'url_key';
    const url_path= 'url_path';





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
        self::parent_id,
        self::name,
        self::sku,
        self::parent_name,
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

        self::path,
        self::path_in_store,
        self::url_key,
        self::url_path,
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
        $this->categoryModel = $categoryModel ;
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
                        if($rowNum==0) {
                            $colection = $this->categoryFactory->create()->getCollection()->addAttributeToFilter('name',$rowData['name'])->setPageSize(1);
                            if ($colection->getData() == null) {
                                $data = $this->categoryFactory->create()->getCollection()->addAttributeToFilter('name','Bag')->setPageSize(1);
                                $parent_id_old = $data->getFirstItem()->getId();
                                $data = ['data' => ["parent_id" => $parent_id_old,
                                    'name' => $rowData['name'],
                                    'description' => $rowData['description'],
                                    "is_active" => true,
                                    "position" => 10,
                                    "include_in_menu" => true,
                                    "all_children" => $rowData['all_children'],
                                    "available_sort_by" => $rowData['available_sort_by'],
                                    "children" => $rowData['children'],
                                    "children_count" => $rowData['children_count'],
                                    "custom_apply_to_products" => $rowData['custom_apply_to_products'],
                                    "custom_design" => $rowData['custom_design'],
                                    "custom_design_from" => $rowData['custom_design_from'],
                                    "custom_design_to" => $rowData['custom_design_to'],
                                    "custom_layout_update" => $rowData['custom_layout_update'],
                                    "default_sort_by" => $rowData['default_sort_by'],
                                    "display_mode" => $rowData['display_mode'],
                                    "filter_price_range" => $rowData['filter_price_range'],
                                    "image" => $rowData['image'],
                                    "is_anchor" => $rowData['is_anchor'],
                                    "landing_page" => $rowData['landing_page'],
                                    "level" => $rowData['level'],
                                    "meta_description" => $rowData['meta_description'],
                                    "meta_keywords" => $rowData['meta_keywords'],
                                    "meta_title" => $rowData['meta_title'],
                                    "page_layout" => $rowData['page_layout'],
                                    "path" => $rowData['path'],
                                    "path_in_store" => $rowData['path_in_store'],
                                    "url_key" => $rowData['url_key'],
                                    "url_path" => $rowData['url_path'],
                                ]];
                                $category=$this->categoryFactory->create($data);
                                $this->_repository->save($category);
                            }
                        }else {
                            $colection = $this->categoryFactory->create()->getCollection()->addAttributeToFilter('name',$rowData['parent_name'])->setPageSize(1);
                            $parent_id = $colection->getFirstItem()->getId();
                            $data = ['data' => ["parent_id" => $parent_id,
                                'name' => $rowData['name'],
                                'description' => $rowData['description'],
                                "is_active" => true,
                                "position" => 10,
                                "include_in_menu" => true,
                                "all_children" => $rowData['all_children'],
                                "available_sort_by" => $rowData['available_sort_by'],
                                "children" => $rowData['children'],
                                "children_count" => $rowData['children_count'],
                                "custom_apply_to_products" => $rowData['custom_apply_to_products'],
                                "custom_design" => $rowData['custom_design'],
                                "custom_design_from" => $rowData['custom_design_from'],
                                "custom_design_to" => $rowData['custom_design_to'],
                                "custom_layout_update" => $rowData['custom_layout_update'],
                                "default_sort_by" => $rowData['default_sort_by'],
                                "display_mode" => $rowData['display_mode'],
                                "filter_price_range" => $rowData['filter_price_range'],
                                "image" => $rowData['image'],
                                "is_anchor" => $rowData['is_anchor'],
                                "landing_page" => $rowData['landing_page'],
                                "level" => $rowData['level'],
                                "meta_description" => $rowData['meta_description'],
                                "meta_keywords" => $rowData['meta_keywords'],
                                "meta_title" => $rowData['meta_title'],
                                "page_layout" => $rowData['page_layout'],
                                "path" => $rowData['path'],
                                "path_in_store" => $rowData['path_in_store'],
                                "url_key" => $rowData['url_key'],
                                "url_path" => $rowData['url_path'],
                            ]];
                            $category=$this->categoryFactory->create($data);
                            $this->_repository->save($category);
                        }
                    }
            }
        }
        return $this;
    }

  /*  protected function ReplaceEntity()
    {
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            foreach ($bunch as $rowNum => $rowData) {
                if ($this->validateRow($rowData, $rowNum)) {
                    if($rowNum==0) {
                        $colection = $this->categoryFactory->create()->getCollection()->addAttributeToFilter('name',$rowData['name'])->setPageSize(1);
                        if ($colection->getData() == null) {
                            $data = $this->categoryFactory->create()->getCollection()->addAttributeToFilter('name','Bag')->setPageSize(1);
                            $parent_id_old = $data->getFirstItem()->getId();
                            $data = ['data' => ["parent_id" => $parent_id_old,
                                'name' => $rowData['name'],
                                'description' =>$rowData['description'],
                                "is_active" => true,
                                "position" => 10,
                                "include_in_menu" => true,
                            ]];
                            $category=$this->categoryFactory->create($data);
                            $this->_repository->save($category);
                        }
                    }else {
                        $colection = $this->categoryFactory->create()->getCollection()->addAttributeToFilter('name',$rowData['parent_name'])->setPageSize(1);
                        $parent_id = $colection->getFirstItem()->getId();
                        $data = ['data' => ["parent_id" => $parent_id,
                            'name' => $rowData['name'],
                            'description' =>$rowData['description'],
                            "is_active" => true,
                            "position" => 10,
                            "include_in_menu" => true,
                        ]];
                        $category=$this->categoryFactory->create($data);
                        $this->_repository->save($category);
                    }
                }
            }
        }
        return $this;
    }*/

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
}