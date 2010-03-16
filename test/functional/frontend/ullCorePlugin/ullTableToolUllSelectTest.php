<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$createValues = array('label_translation_en' => 'Test Select box');
$editValues = array('label_translation_en' => 'Test Select box 2');

$b = new ullTableToolTestBrowser(
	'UllSelect', 
	'Select boxes', 
	'Manage Select boxes', 
  $createValues, 
  $editValues, 
  2, 
  'getDgsUllTableToolUllSelectList', 
  $configuration,
  'label'
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();
$b->executeTest();


