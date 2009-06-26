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

$b
  ->diag('Index quicksearch')
  ->get('ullVentory/index')
  ->setField('filter[search]', '1701') //1701 is an inventory number
  ->click('Search_16x16')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('filter[search]', '1701')
  ->checkResponseElement($dgsList->getFullRowSelector(), 1) // number of rows
  ->checkResponseElement($dgsList->get(1, 'inventory_number'), '1701')
;    

$testUserId = Doctrine::getTable('UllUser')->findOneByUsername('test_user')->id;

$b
  ->diag('Index owner select')
  ->get('ullVentory/index')
  ->setField('filter[ull_entity_id]', $testUserId)
  ->click('Search_16x16')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('filter[ull_entity_id]', $testUserId)
  ->checkResponseElement('h3', 'Items of Test User')
  ->checkResponseElement($dgsList->getFullRowSelector(), 2) // number of rows
  ->checkResponseElement($dgsList->get(1, 'inventory_number'), '1701')
  ->checkResponseElement($dgsList->get(2, 'inventory_number'), '1702')
;  

$b
  ->diag('Now create an entry and check if the owner is passed correctly')
  ->click('Enlist new item')
  ->loginAsAdmin()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'create')
  ->isRequestParameter('entity', 'test_user')
  ->setField('fields[type]', 'notebook')
  ->click('Apply')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'createWithType')
  ->isRequestParameter('entity', 'test_user')
  ->checkResponseElement('h3', 'Item of Test User')
;  
  

