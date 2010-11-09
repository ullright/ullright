<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$s = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(),
      array(
        'edit_delete', 
        'display_name_translation_en',
        'username'
      )
    );

$b = new ullTableToolTestBrowser(
	'UllVentoryStatusDummyUser', 
	'Status users', 
	'Manage Status users', 
  5, 
  $s, 
  $configuration
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array('display_name_translation_en' => 'Test', 'username' => 'test');
$editValues = array('display_name_translation_en' => 'Tester', 'username' => 'tester');

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();


