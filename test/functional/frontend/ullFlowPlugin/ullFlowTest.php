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
	->loginAsAdmin()
	->isStatusCode(200)
	->isRequestParameter('module', 'ullFlow')
	->isRequestParameter('action', 'index')
;	
  
