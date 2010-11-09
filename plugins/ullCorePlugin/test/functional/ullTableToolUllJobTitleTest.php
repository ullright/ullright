<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$s = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(),
      array(
        'edit_delete',      
        'name'
      )
    );

$b = new ullTableToolTestBrowser(
	'UllJobTitle', 
	'Job titles', 
	'Manage Job titles', 
  2, 
  $s, 
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


