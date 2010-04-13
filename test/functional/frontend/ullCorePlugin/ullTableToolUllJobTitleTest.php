<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTableToolTestBrowser(
	'UllJobTitle', 
	'Job titles', 
	'Manage Job titles', 
  2, 
  'getDgsUllTableToolUllJobTitleList', 
  $configuration
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array('name' => 'Foo');
$editValues = array('name' => 'Bar');

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();


