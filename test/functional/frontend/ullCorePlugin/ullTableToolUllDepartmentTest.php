<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$createValues = array('name' => 'Test Department', 'short_name' => 'TD');
$editValues = array('name' => 'New Department', 'short_name' => 'ND');

$b = new ullTableToolTestBrowser(
	'UllDepartment', 
	'Departments', 
	'Manage Departments', 
  $createValues, 
  $editValues, 
  1, 
  'getDgsUllTableToolDepartmentList', 
  $configuration
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();
$b->executeTest();


