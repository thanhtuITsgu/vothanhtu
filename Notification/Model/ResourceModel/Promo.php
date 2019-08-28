<?php
namespace Magenest\Notification\Model\ResourceModel;
class Promo extends
    \Magento\Framework\Model\ResourceModel\Db\AbstractDb {
    public function _construct() {
        $this->_init('promo_notification',
            'entity_id');
    }
}