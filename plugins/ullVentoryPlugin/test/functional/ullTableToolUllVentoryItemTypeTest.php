<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$s = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(),
      array(
        'edit_delete', 
        'name_translation_en',
        'has_software',
      )
    );

$b = new ullTableToolTestBrowser(
	'UllVentoryItemType', 
	'Item types', 
	'Manage Item types', 
  2, 
  $s, 
  $configuration
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array('name_translation_en' => 'Test', 'has_software' => array(true, 'Checkbox_checked'));
$editValues = array('name_translation_en' => 'Tester', 'has_software' => array(false, 'Checkbox_unchecked'));

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();


