<?php
namespace Magenest\Movie\Model\ResourceModel\Movie_Actor;
/**
 * Subscription Collection
 */
class Collection extends
    \Magento\Framework\Model\ResourceModel\Db\Collection\
    AbstractCollection {
    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct() {
        $this->_init('Magenest\Movie\Model\Movie_Actor',
            'Magenest\Movie\Model\ResourceModel\Movie_Actor');
    }
}