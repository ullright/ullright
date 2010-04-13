<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTableToolTestBrowser(
	'UllTimePeriod', 
	'Periods', 
	'Manage Periods', 
  4, 
  'getDgsUllTableToolUllTimePeriodList', 
  $configuration,
  'name'
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array('name_translation_en' => 'test', 'from_date' => array('05/01/2009', '05/01/2009'), 'to_date' => array('05/31/2009', '05/31/2009'));
$editValues = array('name_translation_en' => 'test2', 'from_date' => array('07/01/2009', '07/01/2009'), 'to_date' => array('07/31/2009', '07/31/2009'));

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();


