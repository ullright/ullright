<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$s = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(),
      array(
        'edit_delete',      
        'label_translation_en'
      )
    );


$b = new ullTableToolTestBrowser(
	'UllSelect', 
	'Select boxes', 
	'Manage Select boxes', 
  2, 
  $s, 
  $configuration,
  array('order' => 'label')
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array('label_translation_en' => 'Test Select box');
$editValues = array('label_translation_en' => 'Test Select box 2');

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();


