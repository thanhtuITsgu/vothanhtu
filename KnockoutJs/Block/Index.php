<?php

namespace Magenest\KnockoutJs\Block;

use \Magento\Framework\View\Element\Template;
use \Magento\Framework\View\Element\Template\Context;

class Index extends Template
{

    public function __construct(Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    public function getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }

    public function getHeightData()
    {
        return $this->getHeight();
    }

    public function getWeightData()
    {
        return $this->getWeight();
    }
}