<?php

$app = 'frontend';
include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();


$testUserId = Doctrine::getTable('UllUser')->findOneByUsername('test_user')->id;

$b
  ->diag('login as admin')
  ->get('ullTableTool/edit/table/UllUser/id/' . $testUserId)
  ->loginAsAdmin()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('table', 'UllUser')
  ->isRequestParameter('id', $testUserId)
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
  ->get('ullAdmin/index')
  ->loginAsTestUser()
  ->followRedirect()
;

$b
  ->diag('login as admin again')
  ->click('Log out')
  ->get('ullTableTool/edit/table/UllUser/id/' . $testUserId)
  ->loginAsAdmin()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('table', 'UllUser')
  ->isRequestParameter('id', $testUserId)
  ->responseContains('ullAdmin')
;

$b
  ->diag('set new password for testuser (error in confirmation)')
  ->setField('fields[password]', 'newpass')
  ->setField('fields[password_confirmation]', 'ssapwen')
  ->click('Save')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('table', 'UllUser')
  ->responseContains('Please enter the same password twice')
;

$b
  ->diag('set new password for testuser')
  ->setField('fields[password]', 'newpass')
  ->setField('fields[password_confirmation]', 'newpass')
  ->click('Save')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('table', 'UllUser')
;

$b
  ->diag('login as testuser to check changed password')
  ->click('Log out')
  ->get('ullAdmin/index')
  ->loginAsTestUser('newpass')
;
