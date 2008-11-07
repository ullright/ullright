<?php

$app = 'frontend';
include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullFlowTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();


$b
  ->diag('ullFlow Home')
  ->get('ullFlow/index')
  ->loginAsHelpdeskUser()
;

$b
  ->diag('combinend list access rights as helpdesk user')
  ->click('List')
  ->diag('list - content')
  ->checkResponseElement('table > tbody > tr', 2) // number of rows
  // read access because user is member of "Trouble ticket tool - global read access" group 
  ->checkResponseElement('tbody > tr > td + td + td + td', 'AAA My second trouble ticket')
  // read access because user is member of HelpdeskGroup to which the doc is assigned
  ->checkResponseElement('tbody > tr + tr > td + td + td + td', 'My first trouble ticket')
;

$b
  ->diag('app specific list access rights as helpdesk user')
  ->click('Workflows')
  ->click('Trouble ticket tool')
  ->click('List')
  ->diag('list - content')
  ->checkResponseElement('table > tbody > tr', 2) // number of rows
  // read access because user is member of "Trouble ticket tool - global read access" group 
  ->checkResponseElement('tbody > tr > td + td + td + td', 'AAA My second trouble ticket')
  // read access because user is member of HelpdeskGroup to which the doc is assigned
  ->checkResponseElement('tbody > tr + tr > td + td + td + td', 'My first trouble ticket')
;