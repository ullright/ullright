<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$createValues = array('name' => 'Test Company', 'short_name' => 'TC');
$editValues = array('name' => 'New Company', 'short_name' => 'NC');

$b = new ullTableToolTestBrowser(
	'UllCompany', 
	'Companies', 
	'Manage Companies', 
  $createValues, 
  $editValues, 
  1, 
  'getDgsUllTableToolUllCompanyList', 
  $configuration
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();
$b->executeTest();


