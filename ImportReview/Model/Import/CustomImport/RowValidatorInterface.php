<?php
namespace Magenest\ImportReview\Model\Import\CustomImport;
interface RowValidatorInterface extends \Magento\Framework\Validator\ValidatorInterface
{
    const ERROR_INVALID_TITLE= 'InvalidValueTITLE';
    const ERROR_MESSAGE_IS_EMPTY = 'EmptyMessage';
    const ERROR_PRODUCT_ID_INVALID = 'PRODUCT ID INVALID';
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