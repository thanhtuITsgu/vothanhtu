<?php
namespace Magenest\ImportCategory\Model\Import\CustomImport;
interface RowValidatorInterface extends \Magento\Framework\Validator\ValidatorInterface
{
    const ERROR_INVALID_TITLE= 'InvalidValueTITLE';
    const ERROR_MESSAGE_IS_EMPTY = 'EmptyMessage';
    const ERROR_NAME_INVALID = 'NAME CATEGORY INVALID';
    const ERROR_ROW = 'Missing column';
    const SUCCESS= 'NUMBER ROW IS IMPORTED';
    const ERROR_EMAIL_INVALID = 'EMAIL INVALID';
    /**
     * Initialize validator
     *
     * @return $this
     */
    public function init($context);
}