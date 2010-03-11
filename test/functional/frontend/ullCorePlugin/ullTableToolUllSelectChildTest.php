<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$createValues = array('label_translation_en' => 'Test', 'ull_select_id' => array('1', 'My test select box'), 'sequence' => array('3000', '3000'));
$editValues = array('label_translation_en' => 'Test2', 'ull_select_id' => array('2', 'My test select box 2'), 'sequence' => array('4000', '4000'));

$b = new ullTableToolTestBrowser(
	'UllSelectChild', 
	'Select box entries', 
	'Manage Select box entries', 
  $createValues, 
  $editValues, 
  3, 
  'getDgsUllTableToolUllSelectChildList', 
  $configuration,
  'label'
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();
$b->executeTest();


