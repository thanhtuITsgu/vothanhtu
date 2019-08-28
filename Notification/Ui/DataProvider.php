<?php
namespace Magenest\Notification\Ui;

use Magenest\Notification\Model\ResourceModel\Promo\Collection;
use Magenest\Notification\Model\Promo;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $collection;
    protected $_loadedData;
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        Collection $contactCollectionFactory,
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        $this->request = $request;
        $this->collection = $contactCollectionFactory->create();
        $this->name = $name;
        $this->primaryFieldName = $primaryFieldName;
        $this->requestFieldName = $requestFieldName;
        $this->meta = $meta;
        $this->data = $data;
    }

    public function getData()
    {
        if(isset($this->_loadedData)) {
            return $this->_loadedData;
        }
       /* $id=$this->request->getParam('id');
        $items = $this->collection->addFieldToFilter('entity_id',array('eq'=>$id));
        $this->_loadedData = [];*/
        $items = $this->collection->getItems();

        foreach($items as $contact)
        {
            $this->_loadedData[$contact->getId()] = $contact->getData();
        }

        return $this->_loadedData;
    }

}
