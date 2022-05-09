<?php
namespace Webjump\BraspagPagador\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 * @codeCoverageIgnore
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '2.0.1') < 0) {
            $this->upgradeTwoZeroOne($setup, $context);
        }

        if (version_compare($context->getVersion(), '2.0.2') < 0) {
            $this->upgradeTwoZeroTwo($setup, $context);
        }

        if (version_compare($context->getVersion(), '3.5.2') < 0) {
            $this->upgradeThreeFiveTwo($setup, $context);
        }

        if (version_compare($context->getVersion(), '3.5.3') < 0) {
            $this->upgradeThreeFiveThree($setup, $context);
        }

        if (version_compare($context->getVersion(), '3.5.4') < 0) {
            $this->upgradeThreeFiveFour($setup, $context);
        }

        if (version_compare($context->getVersion(), '3.10.0') < 0) {
            $this->upgradeThreeTenZero($setup, $context);
        }

        if (version_compare($context->getVersion(), '3.13.0') < 0) {
            $this->upgradeThreeThirteenZero($setup, $context);
        }

        if (version_compare($context->getVersion(), '3.16.2') < 0) {
            $this->upgradeThreeSixteenTwo($setup, $context);
        }

        if (version_compare($context->getVersion(), '3.16.4') < 0) {
            $this->upgradeThreeSixteenFour($setup, $context);
        }

        $setup->endSetup();
    }

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    protected function upgradeTwoZeroOne(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {

        $table = $setup->getConnection()->newTable(
            $setup->getTable('webjump_braspagpagador_cardtoken')
        )->addColumn(
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true],
            'Entity ID'
        )->addColumn(
            'alias',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Alias'
        )->addColumn(
            'token',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Token'
        )->addColumn(
            'customer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true],
            'Customer Id'
        )->addColumn(
            'store_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'default' => '0'],
            'Store Id'
        )->addColumn(
            'active',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'default' => 0],
            'Active'
        )->addForeignKey(
            $setup->getFkName('webjump_braspagpagador_cardtoken', 'store_id', 'store', 'store_id'),
            'store_id',
            $setup->getTable('store'),
            'store_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addForeignKey(
            $setup->getFkName('webjump_braspagpagador_cardtoken', 'customer_id', 'customer_entity', 'entity_id'),
            'customer_id',
            $setup->getTable('customer_entity'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'Webjump Braspag Pagador'
        );

        $setup->getConnection()->createTable($table);
    }

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    protected function upgradeTwoZeroTwo(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable('webjump_braspagpagador_cardtoken'),
            'provider',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length'    => 60,
                'nullable' => false,
                'comment' => 'Provider'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('webjump_braspagpagador_cardtoken'),
            'brand',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length'    => 60,
                'nullable' => false,
                'comment' => 'Brands'
            ]
        );
    }

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    protected function upgradeThreeFiveTwo(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable('webjump_braspagpagador_cardtoken'),
            'method',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 60,
                'comment' => 'Payment method'
            ]
        );

        $setup->getConnection()->addIndex(
            $setup->getTable('webjump_braspagpagador_cardtoken'),
            $setup->getIdxName('webjump_braspagpagador_cardtoken', ['customer_id', 'method']),
            ['customer_id', 'method'],
            \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
        );
    }

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    protected function upgradeThreeFiveThree(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->getConnection()->changeColumn(
            $setup->getTable('sales_order_payment'),
            'last_trans_id',
            'last_trans_id',
            [
                'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length'   => 36,
                'nullable' => true,
                'comment'  => 'Last Trans Id'
            ]
        );
    }

    /**
     * @param SchemaSetupInterface $setup
     */
    protected function upgradeThreeFiveFour(SchemaSetupInterface $setup) {
        $connection = $setup->getConnection();

        $setup->startSetup();
        $tableName = $setup->getTable('core_config_data');

        $connection->query("UPDATE  $tableName  SET value = replace(value,'Redecard','Rede') WHERE path like '%payment/braspag_pagador%' and value like '%Redecard%';");
    }

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    protected function upgradeThreeFiveFive(SchemaSetupInterface $setup, ModuleContextInterface $context) {

        if ($setup->getConnection()->isTableExists($setup->getTable('webjump_braspagpagador_cardtoken'))) {

            $setup->getConnection()->dropForeignKey(
                $setup->getTable('webjump_braspagpagador_cardtoken'),
                $setup->getFkName(
                    'webjump_braspagpagador_cardtoken',
                    'customer_id',
                    'customer_entity',
                    'entity_id'
                )
            );

            $setup->getConnection()->dropIndex(
                $setup->getTable('webjump_braspagpagador_cardtoken'),
                $setup->getIdxName(
                    'webjump_braspagpagador_cardtoken',
                    ['customer_id', 'method'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                )
            );

            $setup->getConnection()->addForeignKey(
                $setup->getFkName(
                    'webjump_braspagpagador_cardtoken',
                    'customer_id',
                    'customer_entity',
                    'entity_id'
                ),
                $setup->getTable('webjump_braspagpagador_cardtoken'),
                'customer_id',
                $setup->getTable('customer_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            );

            $setup->getConnection()->addIndex(
                $setup->getTable('webjump_braspagpagador_cardtoken'),
                $setup->getIdxName(
                    'webjump_braspagpagador_cardtoken',
                    ['customer_id', 'method'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX
                ),
                ['customer_id', 'method'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX
            );

            $setup->getConnection()->addColumn(
                $setup->getTable('webjump_braspagpagador_cardtoken'),
                'mask',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'comment' => 'Payment method Mask'
                ]
            );
        }
    }

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    protected function upgradeThreeTenZero(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $splitTableName = $setup->getTable('braspag_paymentsplit_split');
        $splitItemTableName = $setup->getTable('braspag_paymentsplit_split_item');

        $setup->getConnection()->dropTable($splitTableName);
        $setup->getConnection()->dropTable($splitItemTableName);

        $splitTable = $setup->getConnection()->newTable($splitTableName)
            ->addColumn('entity_id', \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT, null,
                array(
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true,
                ), 'Entity Id')
            ->addColumn('subordinate_merchant_id', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 36, array(
                'nullable' => false,
            ),
                'Subordinate Merchant Id')
            ->addColumn('store_merchant_id', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 36, array(
                'nullable' => true,
            ),
                'Store Merchant Id')
            ->addColumn('sales_quote_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null,
                array(
                    'unsigned' => true,
                    'nullable' => false,
                ), 'Sales Quote Id')
            ->addColumn('sales_order_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null,
                array(
                    'unsigned' => true,
                    'nullable' => false,
                ), 'Sales Order Id')
            ->addColumn('mdr_applied', \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL, '12,4', array(
                'nullable' => true,
                'default' => '0',
            ),
                'MDR Applied')
            ->addColumn('tax_applied', \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL, '12,4',
                array(
                    'nullable' => true,
                    'default' => '0',
                ), 'Tax Applied')
            ->addColumn('total_amount', \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL, '12,4',
                array(
                    'nullable' => true,
                    'default' => '0',
                ), 'Total Amount')
            ->addColumn('store_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null,
                array(
                    'unsigned' => true,
                    'nullable' => false,
                ), 'Store Id')
            ->addColumn('created_at', \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP, null,
                array(
                    'nullable' => false,
                    'default'  => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT
                ), 'Created At')
            ->addColumn('updated_at', \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP, null,
                array(
                    'nullable'  => true
                ), 'Updated At')
            ->addIndex(
                $setup->getIdxName($splitTableName, ['sales_quote_id', 'store_merchant_id']),
                ['sales_quote_id', 'store_merchant_id'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX
            )
            ->addIndex(
                $setup->getIdxName($splitTableName, ['sales_quote_id']),
                ['sales_quote_id'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX
            )
            ->addIndex(
                $setup->getIdxName($splitTableName, ['sales_order_id']),
                ['sales_order_id'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX
            )
            ->setComment('Braspag Payment Split');

        $setup->getConnection()->createTable($splitTable);

        $splitItemTable = $setup->getConnection()
            ->newTable($splitItemTableName)
            ->addColumn('split_item_id', \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT, null,
                array(
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true,
                ), 'Split Item Id')
            ->addColumn('split_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null,
                array(
                    'unsigned' => true,
                    'nullable' => false,
                    'default' => '0',
                ), 'Split Id')
            ->addColumn('sales_quote_item_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null,
                array(
                    'unsigned' => true,
                    'nullable' => false,
                    'default' => '0',
                ), 'Sales Quote Item Id')
            ->addColumn('sales_order_item_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null,
                array(
                    'unsigned' => true,
                    'nullable' => false,
                    'default' => '0',
                ), 'Sales Order Item Id')
            ->addColumn('created_at', \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP, null,
                array(
                    'nullable' => false,
                    'default'  => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT
                ), 'Created At')
            ->addColumn('updated_at', \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP, null,
                array(
                    'nullable'  => true
                ), 'Updated At')
            ->addIndex(
                $setup->getIdxName($splitTableName, ['split_id', 'sales_quote_item_id']),
                ['split_id', 'sales_quote_item_id'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX
            )
            ->addIndex(
                $setup->getIdxName($splitTableName, ['sales_quote_item_id', 'sales_order_item_id']),
                ['sales_quote_item_id', 'sales_order_item_id'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX
            )
            ->addIndex(
                $setup->getIdxName($splitTableName, ['sales_quote_item_id']),
                ['sales_quote_item_id'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX
            )
            ->addIndex(
                $setup->getIdxName($splitTableName, ['sales_order_item_id']),
                ['sales_order_item_id'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX
            )
            ->setComment('Braspag Payment Split Item');

        $setup->getConnection()->createTable($splitItemTable);
    }

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    protected function upgradeThreeThirteenZero(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        if ($setup->getConnection()->isTableExists($setup->getTable('braspag_paymentsplit_split'))) {

            $setup->getConnection()->addColumn(
                $setup->getTable('braspag_paymentsplit_split'),
                'locked',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                    'length' => 255,
                    'comment' => 'Locked'
                ]
            );

        }
    }

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    protected function upgradeThreeSixteenTwo(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        if ($setup->getConnection()->isTableExists($setup->getTable('braspag_paymentsplit_split'))) {

            $setup->getConnection()->dropColumn(
                $setup->getTable('braspag_paymentsplit_split'),
                'locked'
            );
        }
    }

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    protected function upgradeThreeSixteenFour(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        if ($setup->getConnection()->isTableExists($setup->getTable('braspag_paymentsplit_split'))) {

            $setup->getConnection()->addColumn(
                $setup->getTable('braspag_paymentsplit_split'),
                'sales_order_increment_id',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 32,
                    'comment' => 'Sales Order Increment Id'
                ]
            );
        }
    }
}
