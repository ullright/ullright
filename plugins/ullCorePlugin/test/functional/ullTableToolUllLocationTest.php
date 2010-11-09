<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$s = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(), 
      array(
        'edit_delete',
        'name',
        'short_name',
        'city',
        'country'
      )
    );

$b = new ullTableToolTestBrowser(
	'UllLocation', 
	'Locations', 
	'Manage Locations', 
  2, 
  $s, 
  $configuration
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array('name' => 'Test', 'city' => 'Test Stadt', 'country' => array('DE', 'Germany'));
$editValues = array('name' => 'Tester', 'city' => 'City', 'country' => array('AT', 'Austria'));

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();


