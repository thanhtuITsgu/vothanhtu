<?php

namespace Magenest\Location\Block;

use Magento\Framework\View\Element\Template;

class Link extends Template
{
    public function getLabel()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->create('Magento\Customer\Model\Session');
        if ($customerSession->isLoggedIn()) {
            $customerId = $customerSession->getId();
            /*$customer = $objectManager->create('Magento\Customer\Model\ResourceModel\Address\Collection')
                ->addAttributeToFilter('parent_id',array('eq'=>$customerId));*/
            $customer = $customerSession->getCustomer();
            $shippingAddress = $customer->getDefaultShippingAddress();
            $city = $shippingAddress->getCity();
            $region = $shippingAddress->getRegion();
            $street = $shippingAddress->getStreet()[0];
            if ($city == "" or $region == "" or $street == "")
                return __('Let create address in My Account');
            else    return __('Bạn đang ở :' . $street . ', ' . $region . ', ' . $city);

        } else {
            $cookieManager = $objectManager->create('Magento\Framework\Stdlib\CookieManagerInterface');
            $CITY = $cookieManager->getCookie(\Magenest\Location\Controller\Save\Save::City);
            $DISTRICT = $cookieManager->getCookie(\Magenest\Location\Controller\Save\Save::District);
            $VILLAGE = $cookieManager->getCookie(\Magenest\Location\Controller\Save\Save::Village);
            if ($CITY == "" or $DISTRICT == "" or $VILLAGE == "")
                return __('Test-Link');
            else    return __('Bạn đang ở :' . $VILLAGE . ', ' . $DISTRICT . ', ' . $CITY);

        }
    }

    public function getCt()
    {
        $url = "https://thongtindoanhnghiep.co/api/city";
        $file = file_get_contents($url);
        $responses = json_decode($file, true);
        return $responses;
    }

    /*public function getDistrict()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id == "") {
            $id = 1;
        }
        $url = "https://thongtindoanhnghiep.co/api/city/" . "$id" . "/district";
        $file2 = file_get_contents($url);
        $responsess = json_decode($file2, true);
        return $responsess;
    }*/
}