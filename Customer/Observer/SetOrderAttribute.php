<?php
namespace Magenest\Customer\Observer;

class SetOrderAttribute implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    const STATUS_PENDING = 'Odd'; //const : khai bao hang so !
    const STATUS_APPROVED = 'Even';
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getOrder();
        $id=$order->getId();

        if ($id%2==0) {
            $order->setOddEven("Even");
        }
        else $order->setOddEven("Odd");
        return $this;
    }
}