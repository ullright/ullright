<?php

$app = 'frontend';
include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$browser = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$browser->setFixturesPath($path);
$browser->resetDatabase();

$dgsUser = $browser->getDgsUllVentoryList();

//login to user search (redirect required in login-helpers - workaround)
$browser->navigateToSearch(true);

$browser->diag('Open advanced ullVentoryItem search');
$browser
  ->call('/ullVentory/search', 'GET', array())
  ->with('request')->begin()
    ->isParameter('module', 'ullVentory')
    ->isParameter('action', 'search')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
  ->end()
;

$browser
  ->with('request')->begin()
    ->isParameter('module', 'ullVentory')
    ->isParameter('action', 'search')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
  ->end()
;

$browser
  ->call('/ullVentory/search', 'POST', array (
  'fields' => 
  array (
    'columnSelect' => 'isVirtual.display_size',
  ),
  'fields_foreign_0_0_filter' => '',
  'addSubmit' => 'Hinzufügen',
))
  ->with('request')->begin()
    ->isParameter('module', 'ullVentory')
    ->isParameter('action', 'search')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
  ->end()
;

$browser
  ->call('/ullVentory/search', 'POST', array (
  'fields' => 
  array (
    'columnSelect' => 'isVirtual.wired_network_speed',
  ),
  'fields_foreign_0_0_filter' => '',
  'addSubmit' => 'Hinzufügen',
))
  ->with('request')->begin()
    ->isParameter('module', 'ullVentory')
    ->isParameter('action', 'search')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
  ->end()
;

$browser
  ->call('/ullVentory/search', 'POST', array (
  'fields' => 
  array (
    'rangeFrom_0_5' => '13',
    'rangeTo_0_5' => '',
    'rangeFrom_0_6' => '1000',
    'rangeTo_0_6' => '',
  ),
  'fields_foreign_0_0_filter' => '',
  'searchSubmit' => 'Suche',
))
  ->with('request')->begin()
    ->isParameter('module', 'ullVentory')
    ->isParameter('action', 'search')
  ->end()
;
$browser
  ->with('response')->begin()
    ->isRedirected(1)
    ->isStatusCode(302)
  ->end()
  ->followRedirect()
;

$browser
  ->with('request')->begin()
    ->isParameter('module', 'ullVentory')
    ->isParameter('action', 'list')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement($dgsUser->get(1, 'inventory_number'), '1701')
    ->checkElement($dgsUser->get(1, 'type'), 'Notebook')
    ->checkElement($dgsUser->get(1, 'manufacturer'), 'Apple')
    ->checkElement($dgsUser->get(1, 'model'), 'MacBook')
    ->checkElement($dgsUser->getFullRowSelector(), 1)
  ->end()
;

$browser
  ->call('/ullVentory/search', 'GET', array())
  ->with('request')->begin()
    ->isParameter('module', 'ullVentory')
    ->isParameter('action', 'search')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
  ->end()
;

$browser->resetSearch('ullVentory');

$browser
  ->call('/ullVentory/search', 'POST', array (
  'fields' => 
  array (
    'columnSelect' => 'isVirtual.wired_network_speed',
  ),
  'fields_foreign_0_0_filter' => '',
  'addSubmit' => 'Add',
))
  ->with('request')->begin()
    ->isParameter('module', 'ullVentory')
    ->isParameter('action', 'search')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
  ->end()
;

$browser
  ->call('/ullVentory/search', 'POST', array (
  'fields' => 
  array (
    'foreign_0_2' => '201',
    'rangeFrom_0_5' => '100',
    'rangeTo_0_5' => '1000',
  ),
  'fields_foreign_0_0_filter' => '',
  'searchSubmit' => 'Search',
))
  ->with('request')->begin()
    ->isParameter('module', 'ullVentory')
    ->isParameter('action', 'search')
  ->end()
;
$browser->followRedirect();

$browser
  ->with('request')->begin()
    ->isParameter('module', 'ullVentory')
    ->isParameter('action', 'list')
  ->end()
  ->with('response')->begin()
    ->checkElement($dgsUser->get(1, 'inventory_number'), '1702')
    ->checkElement($dgsUser->get(1, 'type'), 'Printer')
    ->checkElement($dgsUser->get(1, 'manufacturer'), 'Brother')
    ->checkElement($dgsUser->get(1, 'model'), 'MFC-440CN')
    ->isStatusCode(200)
    
  ->end()
;


