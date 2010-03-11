<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$createValues = array('name_translation_en' => 'Test', 'is_active' => array(true, 'Checkbox_checked'), 'is_absent' => array(false, 'Checkbox_unchecked'));
$editValues = array('name_translation_en' => 'Test2', 'is_active' => array(false, 'Checkbox_unchecked'), 'is_absent' => array(false, 'Checkbox_unchecked'));

$b = new ullTableToolTestBrowser(
	'UllUserStatus', 
	'User status', 
	'Manage User status', 
  $createValues, 
  $editValues, 
  5, 
  'getDgsUllTableToolUserStatusList', 
  $configuration
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();
$b->executeTest();


