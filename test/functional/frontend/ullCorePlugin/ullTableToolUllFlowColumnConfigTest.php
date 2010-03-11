<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$createValues = array(
	'label_translation_en' => 'Test', 
	'ull_flow_app_id' => array('1', 'Todo list'),
	'sequence' => array('3000', '3000'),
  'ull_column_type_id' => array('2', 'String'),
	'options' => 'test',
  'enable'
);
$editValues = array('label_translation_en' => 'Test2', 'ull_select_id' => array('2', 'My test select box 2'), 'sequence' => array('4000', '4000'));

$b = new ullTableToolTestBrowser(
	'UllFlowColumnConfig', 
	'Columns', 
	'Manage Columns', 
  $createValues, 
  $editValues, 
  11, 
  'getDgsUllTableToolUllFlowColumnConfig', 
  $configuration,
  'label'
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();
$b->executeTest();


