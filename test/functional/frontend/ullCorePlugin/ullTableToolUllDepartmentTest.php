<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTableToolTestBrowser(
	'UllDepartment', 
	'Departments', 
	'Manage Departments', 
  1, 
  'getDgsUllTableToolDepartmentList', 
  $configuration
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array('name' => 'Test Department', 'short_name' => 'TD');
$editValues = array('name' => 'New Department', 'short_name' => 'ND');

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();


