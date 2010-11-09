<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

    $s = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(),
      array(
        'edit_delete',      
        'label_translation_en',
        'ull_select_id',
        'sequence'
      )
    );

$b = new ullTableToolTestBrowser(
	'UllSelectChild', 
	'Select box entries', 
	'Manage Select box entries', 
  3, 
  $s, 
  $configuration,
  array('order' => 'label')
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array('label_translation_en' => 'Test', 'ull_select_id' => array('1', 'My test select box'), 'sequence' => array('3000', '3000'));
$editValues = array('label_translation_en' => 'Test2', 'ull_select_id' => array('2', 'My test select box 2'), 'sequence' => array('4000', '4000'));

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();


