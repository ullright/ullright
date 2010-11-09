<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$selector = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(),
      array(
        'edit_delete',      
        'name',
        'short_name'
      )
    );

$b = new ullTableToolTestBrowser(
	'UllDepartment', 
	'Departments', 
	'Manage Departments', 
  1, 
  $selector, 
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


