<?php
namespace Magenest\KnockoutJs\Controller\Search;
class Index extends \Magento\Framework\App\Action\Action {
public function execute() {
$this->_view->loadLayout();
$this->_view->renderLayout();
}
}
?>