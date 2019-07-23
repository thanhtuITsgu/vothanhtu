<?php
namespace Magenest\Movie\Block\Adminhtml\Form\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class ResetButton  implements ButtonProviderInterface {
    public function getButtonData()
    {
        return [
            'label' => __('Reset Page'),
            'on_click' => 'javascript: location.reload();', 'class' => 'reset', 'sort_order' => 30 ];
    }
}
