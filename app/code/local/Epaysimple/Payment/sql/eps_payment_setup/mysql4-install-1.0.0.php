<?php
$installer = $this;
/* @var $installer Mage_Customer_Model_Entity_Setup */
$installer->startSetup();
$installer->getConnection()->addColumn($installer->getTable('sales/quote_payment'), 'epay_bank', array('type' => Varien_Db_Ddl_Table::TYPE_TEXT, 'length' => 30, 'nullable' => false, 'default' => '', 'comment' => 'E-Payment bank select'));
$installer->getConnection()->addColumn($installer->getTable('sales/order_payment'), 'epay_bank', array('type' => Varien_Db_Ddl_Table::TYPE_TEXT, 'length' => 30, 'nullable' => false, 'default' => '', 'comment' => 'E-Payment bank select'));
$installer->endSetup();