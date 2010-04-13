<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTableToolTestBrowser(
	'UllCompany', 
	'Companies', 
	'Manage Companies', 
  1, 
  'getDgsUllTableToolUllCompanyList', 
  $configuration
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array('name' => 'Test Company', 'short_name' => 'TC');
$editValues = array('name' => 'New Company', 'short_name' => 'NC');

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();


