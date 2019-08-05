<?php
namespace Magenest\Movie\Ui\Component\Listing\Column;

use \Magento\Sales\Api\OrderRepositoryInterface;
use \Magento\Framework\View\Element\UiComponent\ContextInterface;
use \Magento\Framework\View\Element\UiComponentFactory;
use \Magento\Ui\Component\Listing\Columns\Column;
use \Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Backend\Block\Widget\Grid as WidgetGrid;

class Rating extends Column
{
    public function prepareDataSource(array $dataSource)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
               $T=$item['rating'];

                $item['rating'] ='<div class="field-summary-rating"><div class="rating-box">
                                        <div class="rating" style="width:'.$T.'%;"></div>
                                    </div></div>';

                /* while($T >= 10)
                {
                    $item['rating'] =$item['rating']."<span style='color: red;'>&#9733;</span>";
                    $T=$T-10;
                    if($T > 0 and $T < 10)
                    {
                        $item['rating'] =$item['rating']."<span>&#9733;</span>";
                    }
                }*/
            }
        }

        return $dataSource;
    }
}