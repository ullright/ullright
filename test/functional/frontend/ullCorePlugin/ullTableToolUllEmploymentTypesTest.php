<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$createValues = array('name_translation_en' => 'Foo');
$editValues = array('name_translation_en' => 'Bar');

$b = new ullTableToolTestBrowser(
	'UllEmploymentType', 
	'Employment types', 
	'Manage Employment types', 
  $createValues, 
  $editValues, 
  4, 
  'getDgsUllTableToolUllEmploymentTypesList', 
  $configuration,
  'name'
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();
$b->executeTest();


