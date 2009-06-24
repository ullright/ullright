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
$newUser->save();

$newUser = new UllUser();
$newUser->first_name = 'Head';
$newUser->last_name = 'Programmer of Modules';
$newUser->username = 'head_programmer';
$newUser->save();

$dgsUser = $browser->getDgsUllUserList();

//login and open advanced search
$browser->diag('Open advanced search, with login');
$browser->navigateToSearch(true);

//we need to add the last name field
//no checks here because the addremovecriteria test does them anyway
$browser
  ->call('/ullUser/search', 'POST', array (
  'fields' => 
  array (
    'columnSelect' => 'last_name',
  ),
  'addSubmit' => 'Add',
))->with('response')->begin()->isStatusCode(200)->end();

//exact and fuzzy search
$browser->diag('Fuzzy search');

$browser
  ->call('/ullUser/search', 'POST', array (
  'fields' => 
  array (
    'standard_0_8' => 'prog modul',
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
    ->isParameter('module', 'ullTableTool')
    ->isParameter('action', 'list')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement($dgsUser->get(1, 'last_name'), 'Programmer of Modules')
    ->checkElement($dgsUser->get(1, 'username'), 'head_programmer')
    ->checkElement($dgsUser->getFullRowSelector(), 1)
  ->end()
;

//navigate to search
$browser->navigateToSearch();

$browser->diag('Exact search - no results');

$browser
  ->call('/ullUser/search', 'POST', array (
  'fields' => 
  array (
    'standard_0_8' => '"prog modul"',
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
    ->isParameter('module', 'ullTableTool')
    ->isParameter('action', 'list')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
    //there should be no results
    ->checkElement($dgsUser->getFullRowSelector(), 0)
  ->end()
;

//navigate to search
$browser->navigateToSearch();

$browser->diag('Exact search - one result');

$browser
  ->call('/ullUser/search', 'POST', array (
  'fields' => 
  array (
    'standard_0_8' => '"Programmer"',
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
    ->isParameter('module', 'ullTableTool')
    ->isParameter('action', 'list')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement($dgsUser->get(1, 'last_name'), 'Programmer of Modules')
    ->checkElement($dgsUser->get(1, 'username'), 'head_programmer')
    ->checkElement($dgsUser->getFullRowSelector(), 1)
  ->end()
;

//reset search
$browser->navigateToSearch();
$browser->resetSearch();

//simple OR test
$browser->diag('OR search with two first names');

$browser
  ->call('/ullUser/search', 'POST', array (
  'fields' => 
  array (
    'columnSelect' => 'first_name',
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
    'columnSelect' => 'first_name',
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
    'standard_0_8' => 'mis',
    'standard_1_8' => 'master',
  ),
  'searchSubmit' => 'Suche',
))
  ->with('request')->begin()
    ->isParameter('module', 'ullUser')
    ->isParameter('action', 'search')
  ->end()
;

$browser->followRedirect();

$browser
  ->with('request')->begin()
    ->isParameter('module', 'ullTableTool')
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

//reset search
$browser->navigateToSearch();
$browser->resetSearch();

//simple OR test with a NOT
$browser->diag('OR search with two first names, second name with NOT');

$browser
  ->call('/ullUser/search', 'POST', array (
  'fields' => 
  array (
    'columnSelect' => 'first_name',
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
    'columnSelect' => 'first_name',
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
    'standard_0_8' => 'mis',
    'not_standard_1_8' => '',
    'standard_1_8' => 'master',
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
    ->isParameter('module', 'ullTableTool')
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
