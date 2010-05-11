<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTableToolTestBrowser(
	'UllVentoryStatusDummyUser', 
	'Status users', 
	'Manage Status users', 
  5, 
  'getDgsUllTableToolUllVentoryStatusDummyUser', 
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


