<?php

namespace Magenest\ReviewImport\Block\Adminhtml;

class ImportContainer extends \Magento\Backend\Block\Widget\Form\Container {

    protected function _construct()
    {
        parent::_construct();

        /**
         * Notes
         * See Magento\Backend\Block\Widget\Form::_buildFormClassName()
         * This will build reference to the form block to $this->_blockGroup\Block\$this->_controller\Edit\Form
         * Edit form needs to be manually created
         *
         * $this->_blockGroup should be the module namespace
         * $this->_controller will be the folder location. Use {foldername_foldername2} if on multi folders
         */
        $this->_objectId = 'entity_id';
        $this->_blockGroup = 'Magenest_ReviewImport';
        $this->_controller = 'adminhtml'; // For folder levels use {foldername2_foldername2}

        parent::_construct();

        $this->buttonList->update('save', 'label', __('Import Review'));

    }

    /**
     * Get header text
     *
     * @return \Magento\Framework\Phrase
     */
}