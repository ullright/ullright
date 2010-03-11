<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$createValues = array('name' => 'Foo');
$editValues = array('name' => 'Bar');

$b = new ullTableToolTestBrowser(
	'UllJobTitle', 
	'Job titles', 
	'Manage Job titles', 
  $createValues, 
  $editValues, 
  2, 
  'getDgsUllTableToolUllJobTitleList', 
  $configuration
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();
$b->executeTest();


