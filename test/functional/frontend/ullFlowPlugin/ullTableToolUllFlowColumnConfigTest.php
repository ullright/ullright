<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTableToolTestBrowser(
	'UllFlowColumnConfig', 
	'Columns', 
	'Manage Columns', 
  12, 
  'getDgsUllTableToolUllFlowColumnConfig', 
  $configuration,
  'label'
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array(
	'label_translation_en' => 'Test', 
	'ull_flow_app_id' => array('2', 'Todo list'),
	'sequence' => array('3000', '3000'),
  'ull_column_type_id' => array('2', 'String'),
	'options' => 'test',
  'is_enabled' => array(true, 'Checkbox_checked'),
  'is_mandatory' => array(true, 'Checkbox_checked'),
  'is_subject' => array(true, 'Checkbox_checked'),
  'is_priority' => array(true, 'Checkbox_checked'),
  'is_project' => array(true, 'Checkbox_checked'),
  'default_value' => 'foo'
);

$editValues = array(
	'label_translation_en' => 'Test two', 
	'ull_flow_app_id' => array('1', 'Trouble ticket tool'),
	'sequence' => array('400', '400'),
	'ull_column_type_id' => array('18', 'MAC address'),
	'options' => 'test',
  'is_enabled' => array(true, 'Checkbox_checked'),
  'is_mandatory' => array(false, 'Checkbox_unchecked'),
  'is_subject' => array(true, 'Checkbox_checked'),
  'is_priority' => array(false, 'Checkbox_unchecked'),
  'is_project' => array(false, 'Checkbox_unchecked'),
  'default_value' => 'foo'
);

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();


