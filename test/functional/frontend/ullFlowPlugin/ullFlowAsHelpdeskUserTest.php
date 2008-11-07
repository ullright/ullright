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
  ->diag('list access rights as helpdesk user')
  ->click('List')
;

$b
  ->diag('list - content')
  ->checkResponseElement('table > thead > tr > th', 7) // number of columns
  // read access because user is member of "Trouble ticket tool - global read access" group 
  ->checkResponseElement('tbody > tr > td + td + td + td', 'AAA My second trouble ticket')
  // read access because user is member of HelpdeskGroup to which the doc is assigned
  ->checkResponseElement('tbody > tr + tr > td + td + td + td', 'My first trouble ticket')
;

