<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$createValues = array('display_name' => 'Test Group', 'email' => array('test@group.at', 'test@group.at'));
$editValues = array('display_name' => 'Test Group 2', 'email' => array('test2@group.at', 'test2@group.at'));

$b = new ullTableToolTestBrowser(
	'UllGroup', 
	'Groups', 
	'Manage Groups', 
  $createValues, 
  $editValues, 
  5, 
  'getDgsUllTableToolUllGroupList', 
  $configuration
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();
$b->executeTest();


