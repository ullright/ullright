<?php

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
    'columnSelect' => 'isVirtual.display-size',
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
    'columnSelect' => 'isVirtual.wired-network-speed',
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
    ->with('response')->begin()
    ->isRedirected(1)
    ->isStatusCode(302)
  ->end()
  
  ->followRedirect()
  ->with('request')->begin()
    ->isParameter('module', 'ullVentory')
    ->isParameter('action', 'edit')
    ->isParameter('inventory_number', '1701')
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
    'columnSelect' => 'isVirtual.wired-network-speed',
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
    ->with('response')->begin()
    ->isRedirected(1)
    ->isStatusCode(302)
  ->end()
  
  ->followRedirect()
  ->with('request')->begin()
    ->isParameter('module', 'ullVentory')
    ->isParameter('action', 'edit')
    ->isParameter('inventory_number', '1702')
  ->end()
;


