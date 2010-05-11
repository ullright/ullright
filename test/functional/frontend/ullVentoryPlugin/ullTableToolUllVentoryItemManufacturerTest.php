<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTableToolTestBrowser(
	'UllVentoryItemManufacturer', 
	'Item manufacturers', 
	'Manage Item manufacturers', 
  2, 
  'getDgsUllTableToolUllVentoryItemManufacturer', 
  $configuration
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array('name' => 'Test');
$editValues = array('name' => 'Tester');

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();


