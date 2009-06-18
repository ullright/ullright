<?php

$app = 'frontend';
include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$browser = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$browser->setFixturesPath($path);
$browser->resetDatabase();

//a user without department
$newUser = new UllUser();
$newUser->first_name = 'Mistress';
$newUser->last_name = 'Modules';
$newUser->username = 'mistress_modules';
$newUser->separation_date = '2001-01-01';
$newUser->save();

//a user with a new department
$newUser = new UllUser();
$newUser->first_name = 'Head';
$newUser->last_name = 'Programmer of Modules';
$newUser->username = 'head_programmer';
$newUser->separation_date = '2002-02-02';

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

//range search from
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
    ->checkElement($dgsUser->get(1, 'first_name'), 'Head')
    ->checkElement($dgsUser->get(1, 'username'), 'head_programmer')
        ->checkElement($dgsUser->getFullRowSelector(), 1)
  ->end()
;
