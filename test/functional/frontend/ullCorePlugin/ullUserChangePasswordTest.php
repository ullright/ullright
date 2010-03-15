<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();


$testUserId = Doctrine::getTable('UllUser')->findOneByUsername('test_user')->id;

$b
  ->diag('login as admin')
  ->get('ullUser/edit/id/' . $testUserId)
  ->loginAsAdmin()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('id', $testUserId)
  ->responseContains('ullAdmin')
  ->checkResponseElement('input[id="fields_password"][value="********"]', true)
  ->checkResponseElement('input[id="fields_password_confirmation"][value="********"]', true)
;

$b
  ->diag('set no password and change username')
  ->setField('fields[first_name]', 'TestMaster')
  ->setField('fields[username]', 'test_master')
  ->click('Save and return to list')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'list')
  ->responseContains('TestMaster')
;

$b
  ->diag('login as testuser to check unchanged password')
  ->click('Log out')
  ->get('ullAdmin/index')
  ->loginAs('test_master')
  ->responseContains('Logged in as ' . ull_link_entity_popup('test_master', $testUserId))
  ->responseContains('Admin Home')
;

$b
  ->diag('login as admin again')
  ->click('Log out')
  ->get('ullUser/edit/id/' . $testUserId)
  ->loginAsAdmin()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('id', $testUserId)
  ->responseContains('ullAdmin')
;

$b
  ->diag('set new password for testuser (error in confirmation)')
  ->setField('fields[password]', 'newpass')
  ->setField('fields[password_confirmation]', 'ssapwen')
  ->click('Save and return to list')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'edit')
  ->responseContains('Please enter the same password twice')
;

$b
  ->diag('set new password for testuser')
  ->setField('fields[password]', 'newpass')
  ->setField('fields[password_confirmation]', 'newpass')
  ->click('Save and return to list')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'list')
;

$b
  ->diag('login as testuser to check changed password')
  ->click('Log out')
  ->get('ullAdmin/index')
  ->loginAs('test_master', 'newpass')
;

$b
  ->diag('test removal of password')
  ->click('Log out')
  ->get('ullUser/edit/id/' . $testUserId)
  ->loginAsAdmin()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('id', $testUserId)
  ->responseContains('ullAdmin')
  ->setField('fields[password]', '')
  ->setField('fields[password_confirmation]', '')
  ->click('Save and return to list')
;

$b->test()->is(Doctrine::getTable('UllUser')->findOneByUsername('test_master')->password, null, 'Password is now null');

