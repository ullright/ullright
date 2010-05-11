<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTableToolTestBrowser(
	'UllVentoryItemModel', 
	'Item models', 
	'Manage Item models', 
  2, 
  'getDgsUllTableToolUllVentoryItemModel', 
  $configuration,
  'name'
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();


$createValues = array(
  'name' => 'Test', 
  'ull_ventory_item_manufacturer_id' => array(Doctrine::getTable('UllVentoryItemManufacturer')->findOneByName('Apple')->id, 'Apple'),
  'ull_ventory_item_type_id' => array(Doctrine::getTable('UllVentoryItemType')->findOneBySlug('notebook')->id, 'Notebook')
);
$editValues = array(
  'name' => 'Tester', 
  'ull_ventory_item_manufacturer_id' => array(Doctrine::getTable('UllVentoryItemManufacturer')->findOneByName('Brother')->id, 'Brother'),
  'ull_ventory_item_type_id' => array(Doctrine::getTable('UllVentoryItemType')->findOneBySlug('printer')->id, 'Printer')
);

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();


