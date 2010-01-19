<?php
$app='frontend';
include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$testUserId = Doctrine::getTable('UllUser')->findOneByUsername('test_user')->id;

$b
  ->diag('login as admin and view test user')
  ->get('ullTableTool/edit/table/UllUser/id/' . $testUserId)
  ->loginAsAdmin()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('table', 'UllUser')
  ->isRequestParameter('id', $testUserId)
  ->responseContains('ullAdmin')
  ->responseContains('test_user')
  ->responseContains('test.user@example.com')
;

$b
  ->diag('set fields and save as scheduled update')
  ->setField('fields[username]', 'software_tester')
  ->setField('fields[email]', 'softwaretester@example.com')
  ->setField('fields[scheduled_update_date]', '08/15/2036') //should be 'future' enough :)
  ->click('Save and return to list')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('table', 'UllUser')
  ->checkResponseElement('body', '!/software_tester/')
  ->checkResponseElement('body', '!/softwaretester@example.com/')
;

$b
  ->diag('save a second scheduled update')
  ->get('ullTableTool/edit/table/UllUser/id/' . $testUserId)
  ->isStatusCode(200)
  ->setField('fields[username]', 'software_tester2')
  ->setField('fields[email]', 'softwaretester2@example.com')
  ->setField('fields[scheduled_update_date]', '08/15/2036')
  ->click('Save and return to list')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
;

$b
  ->diag('view test user again, check scheduled update')
  ->get('ullTableTool/edit/table/UllUser/id/' . $testUserId)
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('table', 'UllUser')
  ->isRequestParameter('id', $testUserId)
  ->checkResponseElement('input[id="fields_username"][value="test_user"]', true)
  ->checkResponseElement('input[id="fields_email"][value="test.user@example.com"]', true)
  ->checkResponseElement('div#edit_future_versions', '/08\/15\/2036/')
  ->checkResponseElement('div#edit_future_versions', '/software_tester/')
  ->checkResponseElement('div#edit_future_versions', '/softwaretester@example.com/')
;

$b
  ->diag('make regular update, check changes in list')
  ->setField('fields[email]', 'st@example.com')
  ->setField('fields[comment]', 'Test comment')
  ->click('Save and return to list')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('table', 'UllUser')
  ->checkResponseElement('body', '!/software_tester/')
  ->checkResponseElement('body', '/st@example.com/')
;

$b
  ->diag('view test user again, check fields again')
  ->get('ullTableTool/edit/table/UllUser/id/' . $testUserId)
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('table', 'UllUser')
  ->isRequestParameter('id', $testUserId)
  ->checkResponseElement('input[id="fields_username"][value="test_user"]', true)
  ->checkResponseElement('input[id="fields_email"][value="st@example.com"]', true)
  ->checkResponseElement('div#edit_future_versions', '/08\/15\/2036/')
  ->checkResponseElement('div#edit_future_versions', '/software_tester/')
  ->checkResponseElement('div#edit_future_versions', '/softwaretester@example.com/')
;

$b
  ->diag('delete second scheduled update')
  ->get('ullTableTool/edit/table/UllUser/id/' . $testUserId)
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('table', 'UllUser')
  ->isRequestParameter('id', $testUserId)
  ->click('Delete', array(), array('position' => 2))
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('table', 'UllUser')
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
  ->get('ullTableTool/edit/table/UllUser/id/' . $testUserId)
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('table', 'UllUser')
  ->isRequestParameter('id', $testUserId)
  ->checkResponseElement('input[id="fields_username"][value="software_tester"]', true)
  ->checkResponseElement('input[id="fields_email"][value="softwaretester@example.com"]', true)
  ->checkResponseElement('div#edit_versions > div.edit_container', '/Version 3/')
  ->checkResponseElement('div#edit_versions > div.edit_container', '/Scheduled update by/')
  ->checkResponseElement('div#edit_versions > div.edit_container', '/software_tester/')
  ->checkResponseElement('div#edit_versions > div.edit_container', '/softwaretester@example.com/')
  ->checkResponseElement('div#edit_future_versions', false) //no future versions
;
