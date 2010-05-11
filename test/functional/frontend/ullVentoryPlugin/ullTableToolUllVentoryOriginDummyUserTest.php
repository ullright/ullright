<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTableToolTestBrowser(
	'UllVentoryOriginDummyUser', 
	'Origin users', 
	'Manage Origin users', 
  2, 
  'getDgsUllTableToolUllVentoryOriginDummyUser', 
  $configuration
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array('display_name_translation_en' => 'Test', 'username' => 'test');
$editValues = array('display_name_translation_en' => 'Tester', 'username' => 'tester');

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();


