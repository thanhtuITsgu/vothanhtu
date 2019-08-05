<?php

namespace Magenest\Movie\Observer;
class Textfield implements \Magento\Framework\Event\ObserverInterface
{
    protected $_configInterface;

    public function __construct(
        \Magento\Framework\App\Config\ConfigResource\ConfigInterface $configInterface
    )
    {
        $this->_configInterface = $configInterface;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->_configInterface
            ->saveConfig('Movie/movie/textfield', 'Pong', 'default', 0);
    }
}