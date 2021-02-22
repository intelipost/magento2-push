<?php
/*
 * @package     Intelipost_Push
 * @copyright   Copyright (c) Intelipost
 * @author      Alex Restani <alex.restani@intelipost.com.br>
 */
namespace Intelipost\Push\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
 
class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
            $setup->getConnection()->addColumn(
                $setup->getTable('sales_order_grid'),
                'intelipost_quote',
                [
                    'type' => Table::TYPE_TEXT,
                    'comment' => 'Intelipost Quote Information'
                ]
            );
         
        if (!$setup->getConnection()->isTableExists($setup->getTable('intelipost_invoice'))) {

            $table = $setup->getConnection()->newTable(
                $setup->getTable('intelipost_invoice')
            )->addColumn(
                'ID',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Id'
            )->addColumn(
                'invoice_number',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Invoice Number'
            )->addColumn(
                'order_number',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Order Number'
            )->addColumn(
                'invoice_series',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Invoice Series'
            )->addColumn(
                'invoice_key',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Invoice Key'
            )->addColumn(
                'invoice_date',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT], //data
                'Invoice Date'
            )->addColumn(
                'invoice_total_value',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Invoice Total Value'
            )->addColumn(
                'invoice_products_value',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Invoice Products Value'
            )->addColumn(
                'invoice_cfop',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Invoice CFOP'
            )->setComment(
                'Intelipost Invoice Table'
            );

            $setup->getConnection()->createTable($table);
        }
         
        $setup->endSetup();
    }
}
