<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$s = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(),
      array(
        'edit_delete', 
        'ull_ventory_item_type_id',
        'ull_ventory_item_attribute_id',
        'is_mandatory',
        'is_presetable'
      )
    );

$b = new ullTableToolTestBrowser(
	'UllVentoryItemTypeAttribute', 
	'Attributes per item type', 
	'Manage Attributes per item type', 
  5, 
  $s, 
  $configuration,
  array('order' => 'created_at', 'desc' => true)
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array(
  'ull_ventory_item_type_id' => array(Doctrine::getTable('UllVentoryItemType')->findOneBySlug('notebook')->id, 'Notebook'),
  'ull_ventory_item_attribute_id' => array(Doctrine::getTable('UllVentoryItemAttribute')->findOneBySlug('display-size')->id, 'Display size'),
  'is_mandatory' => array(true, 'Checkbox_checked'),
  'is_presetable' => array(true, 'Checkbox_checked')
);
$editValues = array(
  'ull_ventory_item_type_id' => array(Doctrine::getTable('UllVentoryItemType')->findOneBySlug('printer')->id, 'Printer'),
  'ull_ventory_item_attribute_id' => array(Doctrine::getTable('UllVentoryItemAttribute')->findOneBySlug('delivery-date')->id, 'Delivery date'),
  'is_mandatory' => array(false, 'Checkbox_unchecked'),
  'is_presetable' => array(true, 'Checkbox_checked')
);

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();


