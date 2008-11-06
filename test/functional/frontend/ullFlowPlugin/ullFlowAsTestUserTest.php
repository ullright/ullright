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
  ->checkResponseElement('tbody > tr > td + td + td + td', 'My first thing todo')
;

