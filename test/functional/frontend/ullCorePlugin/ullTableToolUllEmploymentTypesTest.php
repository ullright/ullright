<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTableToolTestBrowser(
	'UllEmploymentType', 
	'Employment types', 
	'Manage Employment types', 
  4, 
  'getDgsUllTableToolUllEmploymentTypesList', 
  $configuration,
  'name'
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array('name_translation_en' => 'Foo');
$editValues = array('name_translation_en' => 'Bar');

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();


