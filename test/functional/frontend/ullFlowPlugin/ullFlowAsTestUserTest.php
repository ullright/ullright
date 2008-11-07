<?php

$app = 'frontend';
include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();


$b
  ->diag('ullFlow Home')
  ->get('ullFlow/index')
  ->loginAsTestUser()
;

$b
  ->diag('list access rights as test user')
  ->click('List')
;

$b
  ->diag('list - content')
  ->checkResponseElement('table > tbody > tr', 3) // number of rows
  // read access because user is member of TestGroup
  ->checkResponseElement('tbody > tr > td + td + td + td', 'AAA My second thing todo')
  // read access because doc is assigned to the user
  ->checkResponseElement('tbody > tr + tr > td + td + td + td', 'My first thing todo')
  // read access because the doc was once (=UllFlowMemory) assigned to TestGroup, which the user is member of
  ->checkResponseElement('tbody > tr + tr + tr > td + td + td + td', 'My first trouble ticket')
;

