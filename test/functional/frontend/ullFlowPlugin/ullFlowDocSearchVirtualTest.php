<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$browser = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$browser->setFixturesPath($path);
$browser->resetDatabase();

$flowDoc = Doctrine::getTable('UllFlowDoc')->find(1);
$flowDoc->subject = 'searchSubject';
$flowDoc->save();
$flowDoc->setValueByColumn('my_information_update', 'test testSearchStringtest test');

$dgsUser = $browser->getDgsUllFlowListTroubleTicket();

//login to user search (redirect required in login-helpers - workaround)
$browser->navigateToSearch(true);

//open specific app search
$browser->diag('Open advanced flow search');
$browser
  ->call('/ullFlow', 'GET', array())
  ->with('request')->begin()
    ->isParameter('module', 'ullFlow')
    ->isParameter('action', 'index')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
  ->end()
;

$browser
  ->with('response')->begin()
    ->contains('Trouble ticket tool')
    ->click('Trouble ticket tool', array (
  'app' => 'trouble_ticket',
))
  ->end()
;

$browser
  ->with('response')->begin()
    ->contains('Advanced search')
    ->click('Advanced search', array())
  ->end()
;

$browser
  ->with('request')->begin()
    ->isParameter('module', 'ullFlow')
    ->isParameter('action', 'search')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
  ->end()
;
//we need to add the last name field
//no checks here because the addremovecriteria test does them anyway
$browser->diag('add field');
$browser
  ->call('/ullFlow/search/app/trouble_ticket', 'POST', array (
  'fields' => 
  array (
    'columnSelect' => 'isVirtual.my_information_update',
  ),
  'addSubmit' => 'Add',
  'app' => 'trouble_ticket',
))
  ->with('request')->begin()
    ->isParameter('module', 'ullFlow')
    ->isParameter('action', 'search')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
  ->end()
;

$browser->diag('standard search');
$browser
  ->call('/ullFlow/search/app/trouble_ticket', 'POST', array (
  'fields' => 
  array (
    'standard_0_5' => 'searchString', //ignore case testing!
  ),
  'searchSubmit' => 'Search',
  'app' => 'trouble_ticket',
))
  ->with('request')->begin()
    ->isParameter('module', 'ullFlow')
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
    ->isParameter('module', 'ullFlow')
    ->isParameter('action', 'list')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement($dgsUser->get(1, 'id'), '1')
    ->checkElement($dgsUser->get(1, 'subject'), 'searchSubject')
    ->checkElement($dgsUser->getFullRowSelector(), 1)
  ->end()
;