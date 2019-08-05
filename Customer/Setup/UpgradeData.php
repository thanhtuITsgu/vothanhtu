<?php

namespace Magenest\Customer\Setup;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * Customer setup factory
     *
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;

    /**
     * EAV setup factory
     *
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * Constructor
     *
     * @param EavSetupFactory $eavSetupFactory
     * @param CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        CustomerSetupFactory $customerSetupFactory
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        /**
         * run this code if the module version stored in database is less than 1.0.1
         * i.e. the code is run while upgrading the module from version 1.0.0 to 1.0.1
         *
         * you can write the version_compare function in the following way as well:
         * if(version_compare($context->getVersion(), '1.0.1', '<')) {
         *
         * the syntax is only different
         * output is the same
         */
        if (version_compare($context->getVersion(), '2.1.1') < 0) {

            $attributeCode = 'Avatar';

            $customerSetup->addAttribute(
                \Magento\Customer\Model\Customer::ENTITY,
                $attributeCode,
                [
                    'type' => 'text', // backend type
                    'label' => 'Avatar',
                    'input' => 'image', // frontend input
                    'backend' => \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend::class,
                    'required' => false,
                    'visible' => true,
                    'sort_order' => 200,
                    'position' => 300,
                    'system' => false,
                    'visible_on_front'=>1,
                    'is_used_in_grid'=>1 //Hien trong INdex/cusotmer backend
                ]
            );

            // show the attribute in the following forms
            $attribute = $customerSetup
                ->getEavConfig()
                ->getAttribute(
                    \Magento\Customer\Model\Customer::ENTITY,
                    $attributeCode
                )
                ->addData(
                    ['used_in_forms' => [
                        'adminhtml_customer',
                        'adminhtml_checkout',
                        'customer_account_create',
                        'customer_account_edit'
                    ]
                    ]);

            $attribute->save();
        }

        /*if(version_compare($context->getVersion(), '1.0.2', '<')) {

            $attributeCode = 'my_customer_date';

            // add/update frontend_model to the attribute
            $customerSetup->updateAttribute(
                \Magento\Customer\Model\Customer::ENTITY, // customer entity code
                $attributeCode,
                'frontend_model',
                \Magento\Eav\Model\Entity\Attribute\Frontend\Datetime::class
            );

            // update the label of the attribute
            $customerSetup->updateAttribute(
                \Magento\Customer\Model\Customer::ENTITY, // customer entity code
                $attributeCode,
                'frontend_label',
                'My Custom Date Modified'
            );
        }*/

       /* if(version_compare($context->getVersion(), '2.0.4', '<')) {

            $attributeCode = 'Avatar';

            // remove customer attribute
            $customerSetup->removeAttribute(
                \Magento\Customer\Model\Customer::ENTITY,
                $attributeCode // attribute code to remove
            );
        }*/

        $setup->endSetup();
    }
}