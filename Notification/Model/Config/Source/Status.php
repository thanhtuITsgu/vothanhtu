<?php
namespace Magenest\Notification\Model\Config\Source;
class Status implements
    \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            [
                'value' => null,
                'label' => __('--Please Select--')
            ],
            [
                'label' => __('Disable'),
                'value' => 'Disable'
            ],
            [
                'label' => __('Enable'),
                'value' => 'Enable'
            ],
        ];
    }
}