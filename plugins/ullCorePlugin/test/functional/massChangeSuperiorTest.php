<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

//because a user is not allowed to be his own superior,
//we need a third user for these tests
$newUser = new UllUser();
$newUser->save();
$newUserId = $newUser->id;

$b
  ->diag('login as testuser, call ullUser/massChangeSuperior')
  ->get('ullUser/massChangeSuperior')
  ->loginAsTestUser()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'massChangeSuperior')
  ->responseContains('Superior mass change')
  ->responseContains('Superior until now')
  ->responseContains('Future superior')
;

$b
  ->diag('set invalid superior')
  ->setField('fields[old_superior]', 2)
  ->setField('fields[new_superior]', 0)
  ->click('Save')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'massChangeSuperior')
  ->responseContains('Invalid.')
;

$b
  ->diag('test required')
  ->setField('fields[old_superior]', 2)
  ->setField('fields[new_superior]', '')
  ->click('Save')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'massChangeSuperior')
  ->responseContains('Required.')
;

$b
  ->diag('set the same current and replacing superior')
  ->setField('fields[old_superior]', 2)
  ->setField('fields[new_superior]', 2)
  ->click('Save')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'massChangeSuperior')
  ->responseContains('The future superior must be different from the superior until now.')
;

$b
  ->diag('set a current superior with no subordinates')
  ->get('ullUser/massChangeSuperior')
  ->isStatusCode(200)
  ->setField('fields[old_superior]', 2)
  ->setField('fields[new_superior]', 1)
  ->click('Save')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'massChangeSuperior')
  ->responseContains('There are no subordinated users for the given superior.')
;

$b
  ->diag('set valid superiors')
  ->get('ullUser/massChangeSuperior')
  ->isStatusCode(200)
  ->setField('fields[old_superior]', 1)
  ->setField('fields[new_superior]', $newUserId)
  ->click('Save')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'massChangeSuperior')
  ->responseContains('The superior was successfully replaced for one user.')
;
