<?php

$installer = $this;
$installer->startSetup();

/**
 * Create table 'tm_testimonials/data'
 */
if ($installer->getConnection()->isTableExists($installer->getTable('tm_testimonials/data')) != true) {
    $table = $installer->getConnection()
        ->newTable($installer->getTable('tm_testimonials/data'))
        ->addColumn('testimonial_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity'  => true,
            'unsigned'  => true,
            'nullable'  => false,
            'primary'   => true,
        ), 'Testimonial id')
        ->addColumn('status', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
            'default' => 1
        ), 'Testimonial status')
        ->addColumn('date', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
            'nullable'  => false
        ), 'Testimonial creation time')
        ->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 100, array(
            'nullable'  => false
        ), 'User name')
        ->addColumn('email', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable'  => false
        ), 'User email')
        ->addColumn('message', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
            'nullable' => false
        ), 'User message')
        ->addColumn('company', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(),
            'User company')
        ->addColumn('website', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(),
            'User website')
        ->addColumn('twitter', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(),
            'User twitter')
        ->addColumn('facebook', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(),
            'User facebook')
        ->addColumn('image', Varien_Db_Ddl_Table::TYPE_VARCHAR, 100, array(),
            'User image path')
        ->addColumn('rating', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(),
            'User rating')
        ->addColumn('widget', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
            'default' => 1
        ), 'Show testimonial in widget')
        ->setComment('Templates Master Testimonials Data Table');
    $installer->getConnection()->createTable($table);
}

/**
 * Create table 'tm_testimonials/store'
 */
if ($installer->getConnection()->isTableExists($installer->getTable('tm_testimonials/store')) != true) {
    $table = $installer->getConnection()
        ->newTable($installer->getTable('tm_testimonials/store'))
        ->addColumn('testimonial_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable' => false,
            'primary'  => true
            ), 'Testimonial ID')
        ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
            'unsigned' => true,
            'nullable' => false,
            'primary'  => true
            ), 'Store ID')
        ->addIndex($installer->getIdxName('tm_testimonials/store', array('store_id')),
            array('store_id'))
        ->addForeignKey(
            $installer->getFkName('tm_testimonials/store', 'testimonial_id', 'tm_testimonials/data', 'testimonial_id'),
            'testimonial_id',
            $installer->getTable('tm_testimonials/data'),
            'testimonial_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE,
            Varien_Db_Ddl_Table::ACTION_CASCADE)
        ->addForeignKey(
            $installer->getFkName('tm_testimonials/store', 'store_id', 'core/store', 'store_id'),
            'store_id',
            $installer->getTable('core/store'),
            'store_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE,
            Varien_Db_Ddl_Table::ACTION_CASCADE)
        ->setComment('Testimonial To Store Linkage Table');
    $installer->getConnection()->createTable($table);
}

$installer->endSetup();