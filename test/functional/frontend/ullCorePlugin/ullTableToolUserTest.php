<?php

$app = 'frontend';
include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$b
  ->diag('login as admin')
	->get('ullTableTool/edit/table/UllUser/id/3')
  ->loginAsAdmin()
  ->isStatusCode(200)   
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('table', 'UllUser')
  ->isRequestParameter('id', '3')
  ->responseContains('ullAdmin')
;

$b
  ->diag('set no password')
  ->setField('fields[first_name]', 'Testasius')
  ->click('Save')
  ->isRedirected()
  ->followRedirect()  
  ->isStatusCode(200)    
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('table', 'UllUser')
  ->responseContains('Testasius')
;

$b
  ->diag('login as testuser to check unchanged password')
  ->click('Log out')
  ->get('ullFlow/index')
  ->loginAsTestUser()
;