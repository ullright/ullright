<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTableToolTestBrowser(
	'UllVentoryItemType', 
	'Item types', 
	'Manage Item types', 
  2, 
  'getDgsUllTableToolUllVentoryItemTypes', 
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


