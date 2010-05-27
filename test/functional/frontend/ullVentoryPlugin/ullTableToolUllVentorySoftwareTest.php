<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$s = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(),
      array(
        'edit_delete', 
        'name',
      )
    );

$b = new ullTableToolTestBrowser(
	'UllVentorySoftware', 
	'Software', 
	'Manage Software', 
  2, 
  $s, 
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


