<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\Notification\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        //Install new database table
        $table = $installer->getConnection()->newTable(
            $installer->getTable('promo_notification')
        )->addColumn(
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null, [
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary' => true
        ],
            'Entity Id'
        )->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            64,
            ['nullable' => false],
            'Name'
        )->addColumn(
            'status',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255, [
            'nullable' => false],
            'Status'
        )->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null, [
            'nullable' => true,
            'default' =>
                \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT
        ],
            'Created at'
        )->addColumn(
            'short_description',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k', [
            'unsigned' => true,
            'nullable' => false
        ],
            'Short Description'
        )->addColumn(
            'redirect_url',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k', [
            'unsigned' => true,
            'nullable' => false
        ],
            'Redirect Url'
        );
        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}
