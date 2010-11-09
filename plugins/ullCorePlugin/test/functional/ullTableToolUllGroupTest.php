<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$s = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(),
      array(
        'edit_delete',      
        'display_name',
        'email'
      )
    );

$b = new ullTableToolTestBrowser(
	'UllGroup', 
	'Groups', 
	'Manage Groups', 
  5, 
  $s, 
  $configuration
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array('display_name' => 'Test Group', 'email' => array('test@group.at', 'test@group.at'));
$editValues = array('display_name' => 'Test Group 2', 'email' => array('test2@group.at', 'test2@group.at'));

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();


