<?php

$app = 'frontend';
include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$dgsList = $b->getDgsUllVentoryList();

$b
  ->info('Inventory index')
	->get('ullVentory/index')
	->isStatusCode(200)
	->isRequestParameter('module', 'ullVentory')
	->isRequestParameter('action', 'index')
	->responseContains('Inventory Home')
;

$b
  ->diag('Inventory list')
  ->click('Items changed today')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'list')
  ->checkResponseElement($dgsList->getFullRowSelector(), 2) // number of rows
  ->checkResponseElement($dgsList->get(1, 'model'), 'MacBook')  
  ->checkResponseElement($dgsList->get(2, 'model'), 'MFC-440CN')
;