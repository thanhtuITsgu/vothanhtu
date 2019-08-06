<?php
namespace Magenest\Sales\Ui\Component\Listing\Column;

use \Magento\Sales\Api\OrderRepositoryInterface;
use \Magento\Framework\View\Element\UiComponent\ContextInterface;
use \Magento\Framework\View\Element\UiComponentFactory;
use \Magento\Ui\Component\Listing\Columns\Column;
use \Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Backend\Block\Widget\Grid as WidgetGrid;

class Oddeven extends Column
{
    protected $_orderRepository;
    protected $_searchCriteria;

    public function __construct(ContextInterface $context, UiComponentFactory $uiComponentFactory, OrderRepositoryInterface $orderRepository, SearchCriteriaBuilder $criteria, array $components = [], array $data = [])
    {
        $this->_orderRepository = $orderRepository;
        $this->_searchCriteria  = $criteria;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {

            foreach ($dataSource['data']['items'] as & $item) {
                $message = null;
                $class = null;
                if($item['entity_id']%2==0){
                    $message = 'Even';
                    $class ='grid-severity-minor';
                }
                else{
                    $message = 'Odd';
                    $class ='grid-severity-notice';
                }
                $item['oddeven'] ="<span class='" . $class . "'><span>" . $message . "</span></span>";
            }
        }

        return $dataSource;
    }
}