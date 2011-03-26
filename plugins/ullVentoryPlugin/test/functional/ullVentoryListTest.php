<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$dgsList = $b->getDgsUllVentoryList();

$b
  ->info('Inventory index')
	->get('ullVentory/index')
	->loginAsAdmin()
	->isStatusCode(200)
	->isRequestParameter('module', 'ullVentory')
	->isRequestParameter('action', 'index')
	->responseContains('Inventory Home')
;

$b
  ->diag('Inventory list')
  ->click('All items modified today')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'list')
  ->checkResponseElement($dgsList->getFullRowSelector(), 2) // number of rows
  ->checkResponseElement($dgsList->get(1, 'model'), 'MFC-440CN')
  ->checkResponseElement($dgsList->get(2, 'model'), 'MacBook')
;

$b
  ->diag('Toggle inventory taking')
  ->checkResponseElement($dgsList->get(1, 'toggle_inventory_taking') . ' img[src*="notok_16x16.png"]', true)
  ->click('Not yet audited during latest inventory taking')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'list')
  ->checkResponseElement($dgsList->get(1, 'toggle_inventory_taking') . ' img[src*="ok_16x16.png"]', true)
  ->click('Audited during latest inventory taking')
  ->isRedirected()
  ->followRedirect() 
  ->isStatusCode(200) 
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'list')
  ->checkResponseElement($dgsList->get(1, 'toggle_inventory_taking') . ' img[src*="notok_16x16.png"]', true)  
;

$b
  ->diag('Index quicksearch for an inventory number')
  ->get('ullVentory/index')
  ->setField('filter[search]', '1701') //1701 is an inventory number
  ->click('Search_16x16')
  ->isRedirected()
  ->followRedirect()
  ->isRedirected()
  ->followRedirect()  
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('inventory_number', '1701')
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
  ->isRequestParameter('filter', array('ull_entity_id' => $testUserId))
  ->checkResponseElement('#content h3', 'Items of Test User')
  ->checkResponseElement($dgsList->getFullRowSelector(), 2) // number of rows
  ->checkResponseElement($dgsList->get(1, 'inventory_number'), '1702')
  ->checkResponseElement($dgsList->get(2, 'inventory_number'), '1701')
  
;  

$b
  ->diag('Now create an entry and check if the owner is passed correctly')
  ->click('Enlist new item')
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
  ->checkResponseElement('#content h3', 'Item of user: Test User')
;  
  

