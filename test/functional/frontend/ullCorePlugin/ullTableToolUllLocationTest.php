<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$createValues = array('name' => 'Test', 'city' => 'Test Stadt', 'country' => array('DE', 'Germany'));
$editValues = array('name' => 'Tester', 'city' => 'City', 'country' => array('AT', 'Austria'));

$b = new ullTableToolTestBrowser(
	'UllLocation', 
	'Locations', 
	'Manage Locations', 
  $createValues, 
  $editValues, 
  2, 
  'getDgsUllTableToolUllLocationList', 
  $configuration
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();
$b->executeTest();


