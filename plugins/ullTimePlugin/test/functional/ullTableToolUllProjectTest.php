<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$s = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(),
      array(
        'edit_delete',      
        'name_translation_en',
        'description_translation_en',
        'is_active',
        'is_routine'
      )
    );

$b = new ullTableToolTestBrowser(
	'UllProject', 
	'Projects', 
	'Manage Projects', 
  2, 
  $s, 
  $configuration,
  array('order' => 'name')
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array('name_translation_en' => 'test', 'description_translation_en' => 'description', 'is_active' => array(true, 'Checkbox_checked'), 'is_routine' => array(false, 'Checkbox_unchecked'));
$editValues = array('name_translation_en' => 'test2', 'description_translation_en' => 'description2', 'is_active' => array(true, 'Checkbox_checked'), 'is_routine' => array(true, 'Checkbox_checked'));

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();


