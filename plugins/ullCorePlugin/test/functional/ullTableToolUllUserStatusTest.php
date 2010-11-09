<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$s = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(),
      array(
        'edit_delete',      
        'name_translation_en',
        'is_active',
        'is_absent'
      )
    );

$b = new ullTableToolTestBrowser(
	'UllUserStatus', 
	'User status', 
	'Manage User status', 
  5, 
  $s, 
  $configuration
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array('name_translation_en' => 'Test', 'is_active' => array(true, 'Checkbox_checked'), 'is_absent' => array(false, 'Checkbox_unchecked'));
$editValues = array('name_translation_en' => 'Test2', 'is_active' => array(false, 'Checkbox_unchecked'), 'is_absent' => array(false, 'Checkbox_unchecked'));

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();


