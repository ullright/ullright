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
  ->checkResponseElement('table > thead > tr > th', 7) // number of columns
  ->checkResponseElement('tbody > tr > td + td + td + td', 'AAA My second thing todo')
  ->checkResponseElement('tbody > tr + tr > td + td + td + td', 'My first thing todo')
;

