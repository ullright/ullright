<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$createValues = array('name_translation_en' => 'test', 'description_translation_en' => 'description', 'is_active' => array(true, 'Checkbox_checked'), 'is_routine' => array(false, 'Checkbox_unchecked'));
$editValues = array('name_translation_en' => 'test2', 'description_translation_en' => 'description2', 'is_active' => array(true, 'Checkbox_checked'), 'is_routine' => array(true, 'Checkbox_checked'));

$b = new ullTableToolTestBrowser(
	'UllProject', 
	'Projects', 
	'Manage Projects', 
  $createValues, 
  $editValues, 
  2, 
  'getDgsUllTableToolUllProjectList', 
  $configuration,
  'name'
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();
$b->executeTest();


