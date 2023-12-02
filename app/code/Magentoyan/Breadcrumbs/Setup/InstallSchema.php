<?php

//Author Ali Majidian

namespace Magentoyan\Breadcrumbs\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface {

    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context) {
        $installer = $setup;
        $installer->startSetup();

        

        $table = $installer->getConnection()
                ->newTable($installer->getTable('magentoyan_breadcrumbs'))
                ->addColumn(
                        'id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true], 'ID PK'
                )
                ->addColumn(
                        'entity_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['unsigned' => true, 'nullable' => false, 'unique' => true, 'default' => '0'], 'Category Id'
                )
                
                ->addColumn(
                        'position', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['unsigned' => true, 'nullable' => false, 'default' => '0'], 'Position'
                )
                
                ->addColumn(
                        'enable', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['unsigned' => true, 'nullable' => false, 'default' => '0'], 'Enable'
                )
                ->addIndex(
                        $installer->getIdxName('magentoyan_breadcrumbs', ['entity_id']), ['entity_id']
                )
                ->addForeignKey(
                $installer->getFkName('magentoyan_breadcrumbs', 'entity_id', 'catalog_category_entity', 'entity_id'), 'entity_id', $installer->getTable('catalog_category_entity'), 'entity_id', \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                )

        ;
        
        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}
