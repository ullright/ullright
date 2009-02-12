<?php

$app = 'frontend';
include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$b
  ->diag('login as testuser, call ullUser/massChangeSuperior')
  ->get('ullUser/massChangeSuperior')
  ->loginAsTestUser()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'massChangeSuperior')
  ->responseContains('Superior mass change')
  ->responseContains('Current superior')
  ->responseContains('Replacing superior')
;

$b
  ->diag('set the same current and replacing superior')
  ->setField('fields[old_superior]', 2)
  ->setField('fields[new_superior]', 2)
  ->click('Save superior change')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'massChangeSuperiorSave')
  ->responseContains('The replacing superior must be different from the old superior.')
;

$b
  ->diag('set a current superior with no subordinates')
  ->get('ullUser/massChangeSuperior')
  ->isStatusCode(200)
  ->setField('fields[old_superior]', 2)
  ->setField('fields[new_superior]', 1)
  ->click('Save superior change')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'massChangeSuperiorSave')
  ->responseContains('There are no subordinated users for the given superior.')
;

$b
  ->diag('set valid superiors')
  ->get('ullUser/massChangeSuperior')
  ->isStatusCode(200)
  ->setField('fields[old_superior]', 1)
  ->setField('fields[new_superior]', 2)
  ->click('Save superior change')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'massChangeSuperiorSave')
  ->responseContains('The superior was successfully replaced for one user.')
;
