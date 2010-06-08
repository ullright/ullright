<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$browser = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$browser->setFixturesPath($path);
//we do not reset the db here because we don't access
//the model in this test

//login and open advanced search
$browser->diag('Open advanced search, with login');
$browser->navigateToSearch(true);

$browser->diag('Add/remove criterion test');

$browser
  ->with('request')->begin()
    ->isParameter('module', 'ullUser')
    ->isParameter('action', 'search')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement('#fields_standard_0_8', false)
    ->checkElement('form#searchForm label', 8)
  ->end()
;

$browser
  ->call('/ullUser/search', 'POST', array (
  'fields' => 
  array (
    'columnSelect' => 'last_name',
  ),
  'addSubmit' => 'Add',
))
  ->with('request')->begin()
    ->isParameter('module', 'ullUser')
    ->isParameter('action', 'search')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement('#fields_standard_0_8', true)
    ->checkElement('form#searchForm label[for="fields_standard_0_8"]', 'Last name')
    ->checkElement('form#searchForm label:contains("Last name")', 1)
    ->checkElement('form#searchForm label', 9)
  ->end()
;

$browser
  ->call('/ullUser/search', 'POST', array (
  'fields' => 
  array (
    'columnSelect' => 'last_name',
  ),
  'addSubmit' => 'Add',
))
  ->with('request')->begin()
    ->isParameter('module', 'ullUser')
    ->isParameter('action', 'search')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement('#fields_standard_0_8', true)
    ->checkElement('#fields_standard_1_8', true)
    ->checkElement('form#searchForm label[for="fields_standard_0_8"]', 'Last name')
    ->checkElement('form#searchForm label[for="fields_standard_1_8"]', 'Last name')
    ->checkElement('form#searchForm label:contains("Last name")', 2)
    ->checkElement('form#searchForm label', 10)
    ->checkElement('form#searchForm label:contains("Department")', true)
    ->checkElement('#fields_foreign_0_1', true)
  ->end()
;

$browser
  ->call('/ullUser/search', 'POST', array (
  'fields' => 
  array (
  ),
  'removeSubmit_foreign_0_1_x' => '8',
  'removeSubmit_foreign_0_1_y' => '10',
))
 
  ->with('request')->begin()
    ->isParameter('module', 'ullUser')
    ->isParameter('action', 'search')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement('#fields_foreign_0_1', false)
    ->checkElement('form#searchForm label', 9)
    ->checkElement('form#searchForm label:contains("Department")', false)
    ->checkElement('form#searchForm label:contains("Job title")', true)
    ->checkElement('#fields_foreign_0_3', true)
  ->end()
;

$browser
  ->call('/ullUser/search', 'POST', array (
  'fields' => 
  array (
  ),
  'removeSubmit_foreign_0_6_x' => '10',
  'removeSubmit_foreign_0_6_y' => '9',
))
 
  ->with('request')->begin()
    ->isParameter('module', 'ullUser')
    ->isParameter('action', 'search')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement('#fields_foreign_0_6', false)
    ->checkElement('form#searchForm label', 8)
    ->checkElement('form#searchForm label:contains("Job title")', false)
    ->checkElement('#fields_rangeDateFrom_0_9', false)
    ->checkElement('#fields_rangeDateTo_0_9', false)
  ->end()
;

$browser
  ->call('/ullUser/search', 'POST', array (
  'fields' => 
  array (
    'columnSelect' => 'deactivation_date',
  ),
  'addSubmit' => 'Add',
))
 
  ->with('request')->begin()
    ->isParameter('module', 'ullUser')
    ->isParameter('action', 'search')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement('#fields_rangeDateFrom_0_9', true)
    ->checkElement('#fields_rangeDateTo_0_9', true)
    ->checkElement('form#searchForm label', 9)
    ->checkElement('form#searchForm label:contains("Deactivation date")', true)
  ->end()
;

$browser
  ->call('/ullUser/search', 'POST', array (
  'fields' => 
  array (
  ),
  'removeSubmit_standard_1_8_x' => '10',
  'removeSubmit_standard_1_8_y' => '9',
))
 
  ->with('request')->begin()
    ->isParameter('module', 'ullUser')
    ->isParameter('action', 'search')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement('#fields_standard_0_8', true)
    ->checkElement('#fields_standard_1_8', false)
    ->checkElement('form#searchForm label', 8)
    ->checkElement('form#searchForm label:contains("Last name")', 1)

  ->end()
;

