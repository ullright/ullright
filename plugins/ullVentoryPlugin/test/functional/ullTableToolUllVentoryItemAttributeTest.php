<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$s = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(),
      array(
        'edit_delete', 
        'name_translation_en',
        'ull_column_type_id',
        'options'
      )
    );

$b = new ullTableToolTestBrowser(
	'UllVentoryItemAttribute', 
	'Item attributes', 
	'Manage Item attributes', 
  4, 
  $s, 
  $configuration
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array(
  'name_translation_en' => 'Test', 
  'ull_column_type_id' => array(Doctrine::getTable('UllColumnType')->findOneByLabel('PhoneNumber')->id, 'PhoneNumber'),
  'options' => 'Tester'
);
$editValues = array(
  'name_translation_en' => 'Tester', 
  'ull_column_type_id' => array(Doctrine::getTable('UllColumnType')->findOneByLabel('String')->id, 'String'),
  'options' => 'Test'
);

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();


