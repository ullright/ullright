<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$browser = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$browser->setFixturesPath($path);
$browser->resetDatabase();

//a new user without new department, but a new group
$newUser = new UllUser();
$newUser->first_name = 'Mistress';
$newUser->last_name = 'Modules';
$newUser->username = 'mistress_modules';

$newGroup = new UllGroup();
$newGroup->id = 815;
$newGroup->display_name = 'Group of Mistresses';
$newGroup->save();
$newUser->UllGroup[] = $newGroup;
$newUser->save();

//a new user with a new department, but not in the new group
$newUser = new UllUser();
$newUser->first_name = 'Head';
$newUser->last_name = 'Programmer of Modules';
$newUser->username = 'head_programmer';

$department = new UllDepartment();
$department->id = 33;
$department->name = 'Department of Programmers';
$department->save();
$newUser->UllDepartment = $department;
$newUser->save();

$dgsUser = $browser->getDgsUllUserList();

//login and open advanced search
$browser->diag('Open advanced search, with login');
$browser->navigateToSearch(true);

//boolean search
$browser->diag('Boolean search');
$browser
  ->call('/ullUser/search', 'POST', array (
  'fields' => 
  array (
    'columnSelect' => 'is_show_mobile_number_in_phonebook',
  ),
  'addSubmit' => 'Hinzufügen',
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
    'boolean_0_8' => 'unchecked',
  ),
  'searchSubmit' => 'Suche',
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
    ->checkElement($dgsUser->getFullRowSelector(), 1)
  ->end()
;

//reset search
$browser->navigateToSearch();
$browser->resetSearch();

//foreign search
$browser->diag('Foreign search');

$browser
  ->call('/ullUser/search', 'POST', array (
  'fields' => 
  array (
    'foreign_0_1' => '33',
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

//relation search
$browser->diag('Relation search');

$browser
  ->call('/ullUser/search', 'POST', array (
  'fields' => 
  array (
    'foreign_0_4' => '815', //group of mistresses
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
    ->checkElement($dgsUser->getFullRowSelector(), 1)
  ->end()
;
