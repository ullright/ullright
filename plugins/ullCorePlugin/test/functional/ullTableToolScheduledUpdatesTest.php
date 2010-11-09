<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$testUserId = Doctrine::getTable('UllUser')->findOneByUsername('test_user')->id;

$b
  ->diag('login as admin and view test user')
  ->get('ullUser/edit/id/' . $testUserId)
  ->loginAsAdmin()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('id', $testUserId)
  ->responseContains('ullAdmin')
  ->responseContains('test_user')
  ->responseContains('test.user@example.com')
;

$b
  ->diag('set fields and save as scheduled update')
  ->setField('fields[username]', 'software_tester')
  ->setField('fields[email]', 'softwaretester@example.com')
  ->setField('fields[comment]', 'TestComment')
  ->setField('fields[scheduled_update_date]', '08/15/2036') //should be 'future' enough :)
  ->click('Save and return to list')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'list')
  ->checkResponseElement('body', '!/software_tester/')
  ->checkResponseElement('body', '!/softwaretester@example.com/')
  ->checkResponseElement('body', '!/TestComment/')
;

$b
  ->diag('save a second scheduled update')
  ->get('ullUser/edit/id/' . $testUserId)
  ->isStatusCode(200)
  ->setField('fields[username]', 'software_2_tester')
  ->setField('fields[email]', 'software2tester@example.com')
  ->setField('fields[scheduled_update_date]', '08/15/2036')
  ->setField('fields[personnel_number]', '4712')
  ->click('Save and return to list')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
;

$b
  ->diag('view test user again, check scheduled update')
  ->get('ullUser/edit/id/' . $testUserId)
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('id', $testUserId)
  ->checkResponseElement('input[id="fields_username"][value="test_user"]', true)
  ->checkResponseElement('input[id="fields_email"][value="test.user@example.com"]', true)
  //second scheduled update
  ->checkResponseElement('div#edit_future_versions > div', '/08\/15\/2036/')
  ->checkResponseElement('div#edit_future_versions > div', '/software_2_tester/')
  ->checkResponseElement('div#edit_future_versions > div', '/software2tester@example.com/')
  ->checkResponseElement('div#edit_future_versions > div', '/4712/')
  ->checkResponseElement('div#edit_future_versions > div', '!/TestComment/')
  ->checkResponseElement('div#edit_future_versions > div', '!/software_tester/')
  ->checkResponseElement('div#edit_future_versions > div', '!/softwaretester@example.com/')
  //first scheduled update
  ->checkResponseElement('div#edit_future_versions > div + div', '/08\/15\/2036/')
  ->checkResponseElement('div#edit_future_versions > div + div', '!/software_2_tester/')
  ->checkResponseElement('div#edit_future_versions > div + div', '!/software2tester@example.com/')
  ->checkResponseElement('div#edit_future_versions > div + div', '!/4712/')
  ->checkResponseElement('div#edit_future_versions > div + div', '/TestComment/')
  ->checkResponseElement('div#edit_future_versions > div + div', '/software_tester/')
  ->checkResponseElement('div#edit_future_versions > div + div', '/softwaretester@example.com/')
;

$b
  ->diag('make regular update, check changes in list')
  ->setField('fields[email]', 'st@example.com')
  ->setField('fields[comment]', 'Test comment for testing')
  ->click('Save and return to list')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'list')
  ->checkResponseElement('body', '/st@example.com/')
  ->checkResponseElement('body', '/test_user/')
  ->checkResponseElement('body', '!/software_tester/')
  ->checkResponseElement('body', '!/software_2_tester/')
;

$b
  ->diag('view test user again, check fields for previous regular update')#
  //scheduled updates should not be applied already
  ->get('ullUser/edit/id/' . $testUserId)
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('id', $testUserId)
  ->checkResponseElement('input[id="fields_username"][value="test_user"]', true)
  ->checkResponseElement('input[id="fields_email"][value="st@example.com"]', true)
  ->checkResponseElement('textarea[id="fields_comment"]', 'Test comment for testing')
  ->checkResponseElement('div#edit_future_versions > div', '/08\/15\/2036/')
  ->checkResponseElement('div#edit_future_versions > div', '/software_2_tester/')
  ->checkResponseElement('div#edit_future_versions > div + div', '/software_tester/')
;

$b
  ->diag('delete second scheduled update')
  ->get('ullUser/edit/id/' . $testUserId)
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('id', $testUserId)
  ->click('Delete', array(), array('position' => 1))
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'edit')
;

$b
  ->diag('calling the ApplyScheduledUpdates task with now-option')
;

$applyUpdatesTask = new ApplyScheduledUpdatesTask(new sfEventDispatcher(), new sfFormatter());
$applyUpdatesTask->applyUpdates(array('now' => 'now',
                        'application' => $app,
                        'env' => 'test'));

$b
  ->diag('reload test user, check if scheduled update was applied')
  ->get('ullUser/edit/id/' . $testUserId)
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('id', $testUserId)
  ->checkResponseElement('input[id="fields_username"][value="software_tester"]', true)
  ->checkResponseElement('input[id="fields_email"][value="softwaretester@example.com"]', true)
  ->checkResponseElement('div#edit_versions > div.edit_container', '/Version 3/')
  ->checkResponseElement('div#edit_versions > div.edit_container', '/Scheduled update by/')
  ->checkResponseElement('div#edit_versions > div.edit_container', '/software_tester/')
  ->checkResponseElement('div#edit_versions > div.edit_container', '/softwaretester@example.com/')
  ->checkResponseElement('div#edit_versions > div.edit_container', '!/4712/')
  ->checkResponseElement('div#edit_versions > div.edit_container', '!/software_2_tester/')
  ->checkResponseElement('div#edit_versions > div.edit_container', '!/software2tester@example.com/')
  ->checkResponseElement('div#edit_future_versions', false) //no future versions
;
