<?php

$app = 'frontend';
include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$browser = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$browser->setFixturesPath($path);
$browser->resetDatabase();

$newUser = new UllUser();
$newUser->first_name = 'Mistress';
$newUser->last_name = 'Modules';
$newUser->username = 'mistress_modules';
$newUser->separation_date = '2001-01-01';
$newUser->save();

$newUser = new UllUser();
$newUser->first_name = 'Head';
$newUser->last_name = 'Programmer of Modules';
$newUser->username = 'head_programmer';
$newUser->separation_date = '2002-02-02';
$newUser->save();

$dgsUser = $browser->getDgsUllUserList();

//login and open advanced search
$browser->diag('Open advanced search, with login');
$browser->navigateToSearch(true);

//range search from
$browser->diag('Range search - from - inclusion of boundary date');

$browser
  ->call('/ullUser/search', 'POST', array (
  'fields' => 
  array (
    'columnSelect' => 'separation_date',
  ),
  'addSubmit' => 'Add',
))
  ->with('request')->begin()
    ->isParameter('module', 'ullUser')
    ->isParameter('action', 'search')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
  ->end()
;

$browser
  ->call('/ullUser/search', 'POST', array (
  'fields' => 
  array (
    'rangeTo_0_8' => '',
    'rangeFrom_0_8' => '02/02/2002',
    'columnSelect' => 'id',
  ),
  'searchSubmit' => 'Search',
))
  ->with('request')->begin()
    ->isParameter('module', 'ullUser')
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
    ->isParameter('module', 'ullUser')
    ->isParameter('action', 'list')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement($dgsUser->get(1, 'first_name'), 'Master')
    ->checkElement($dgsUser->get(1, 'username'), 'admin')
    ->checkElement($dgsUser->get(2, 'first_name'), 'Head')
    ->checkElement($dgsUser->get(2, 'username'), 'head_programmer')
    ->checkElement($dgsUser->getFullRowSelector(), 2)
  ->end()
;

//reset search
$browser->navigateToSearch();
$browser->resetSearch();

//range search to
$browser->diag('Range search - to - inclusion of boundary date');

$browser
  ->call('/ullUser/search', 'POST', array (
  'fields' => 
  array (
    'columnSelect' => 'separation_date',
  ),
  'addSubmit' => 'Add',
))
  ->with('request')->begin()
    ->isParameter('module', 'ullUser')
    ->isParameter('action', 'search')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
  ->end()
;

$browser
  ->call('/ullUser/search', 'POST', array (
  'fields' => 
  array (
    'rangeFrom_0_8' => '',
    'rangeTo_0_8' => '02/02/2002',
  ),
  'searchSubmit' => 'Search',
))
  ->with('request')->begin()
    ->isParameter('module', 'ullUser')
    ->isParameter('action', 'search')
  ->end()
;

$browser->followRedirect();

$browser
  ->with('request')->begin()
    ->isParameter('module', 'ullUser')
    ->isParameter('action', 'list')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement($dgsUser->get(1, 'first_name'), 'Mistress')
    ->checkElement($dgsUser->get(1, 'username'), 'mistress_modules')
    ->checkElement($dgsUser->get(2, 'first_name'), 'Head')
    ->checkElement($dgsUser->get(2, 'username'), 'head_programmer')
    ->checkElement($dgsUser->getFullRowSelector(), 2)
  ->end()
;

//reset search
$browser->navigateToSearch();
$browser->resetSearch();

//range search from and to
$browser->diag('Range search - from and to');

$browser
  ->call('/ullUser/search', 'POST', array (
  'fields' => 
  array (
    'columnSelect' => 'separation_date',
  ),
  'addSubmit' => 'Add',
))
  ->with('request')->begin()
    ->isParameter('module', 'ullUser')
    ->isParameter('action', 'search')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
  ->end()
;

$browser
  ->call('/ullUser/search', 'POST', array (
  'fields' => 
  array (
    'rangeFrom_0_8' => '01/01/2002',
    'rangeTo_0_8' => '01/01/2005',
  ),
  'searchSubmit' => 'Search',
))
  ->with('request')->begin()
    ->isParameter('module', 'ullUser')
    ->isParameter('action', 'search')
  ->end()
;

$browser->followRedirect();

$browser
  ->with('request')->begin()
    ->isParameter('module', 'ullUser')
    ->isParameter('action', 'list')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement($dgsUser->get(1, 'first_name'), 'Head')
    ->checkElement($dgsUser->get(1, 'username'), 'head_programmer')
    ->checkElement($dgsUser->getFullRowSelector(), 1)
  ->end()
;

//reset search
$browser->navigateToSearch();
$browser->resetSearch();

//same search with NOT
$browser->diag('Same range search - with NOT');

$browser
  ->call('/ullUser/search', 'POST', array (
  'fields' => 
  array (
    'columnSelect' => 'separation_date',
  ),
  'addSubmit' => 'Add',
))
  ->with('request')->begin()
    ->isParameter('module', 'ullUser')
    ->isParameter('action', 'search')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
  ->end()
;

$browser
  ->call('/ullUser/search', 'POST', array (
  'fields' => 
  array (
    'not_rangeFrom_0_8' => '',
    'rangeFrom_0_8' => '01/01/2002',
    'rangeTo_0_8' => '01/01/2005',
  ),
  'searchSubmit' => 'Search',
))
  ->with('request')->begin()
    ->isParameter('module', 'ullUser')
    ->isParameter('action', 'search')
  ->end()
;

$browser->followRedirect();

$browser
  ->with('request')->begin()
    ->isParameter('module', 'ullUser')
    ->isParameter('action', 'list')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement($dgsUser->get(1, 'first_name'), 'Master')
    ->checkElement($dgsUser->get(1, 'username'), 'admin')
    ->checkElement($dgsUser->get(2, 'first_name'), 'Mistress')
    ->checkElement($dgsUser->get(2, 'username'), 'mistress_modules')
    ->checkElement($dgsUser->getFullRowSelector(), 2)
  ->end()
;
