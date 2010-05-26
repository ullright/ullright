<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$dgsList = $b->getDgsUllWikiList();

// create additional wiki doc
$wiki = new UllWiki();
$wiki->subject = 'Testdoc readable for logged in users';
$wiki->body = 'This is a testdoc readable for logged in users';
$wiki->UllWikiAccessLevel = Doctrine::getTable('UllWikiAccessLevel')->findOneBySlug('logged_in_readable');
$wiki->save();


// not logged in
$b
  ->diag('list - not logged in')
  ->get('ullWiki/list')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'list')
  ->checkResponseElement($dgsList->getFullRowSelector(), 1) // number of rows
  ->checkResponseElement($dgsList->get(1, 'subject'), 'Testdoc')
;

$b
  ->diag('show: check access to a public document')
  ->click('Testdoc')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'show')
  ->responseContains('This is a test document')  
;

$b
  ->diag('show: check access to a restricted document')
  ->get('ullWiki/show/docid/2')
  ->isRedirected()
  ->followRedirect()
  ->responseContains('Log in')
;

$b
  ->diag('create - not logged in -> display login')
  ->get('ullWiki/create')
  ->isRedirected()
  ->followRedirect()
  ->responseContains('Log in')
;

$b
  ->diag('edit - not logged in -> display login')
  ->get('ullWiki/edit/docid/1')
  ->isRedirected()
  ->followRedirect()
  ->responseContains('Log in')
;

// logged in as Masteradmin
$b
  ->diag('list as masteradmin')
  ->get('ullAdmin/index')
  ->loginAsAdmin()
  ->get('ullWiki/list/order/subject/order_dir/asc')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'list')
  ->checkResponseElement($dgsList->getFullRowSelector(), 3) // number of rows
  ->checkResponseElement($dgsList->get(1, 'subject'), 'Another Testdoc')
  ->checkResponseElement($dgsList->get(2, 'subject'), 'Testdoc')
  ->checkResponseElement($dgsList->get(3, 'subject'), 'Testdoc readable for logged in users')
;

$b
  ->diag('show as masteradmin: check access to a public document')
  ->click('Testdoc')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'show')
  ->responseContains('This is a test document')  
;

$b
  ->diag('show as masteradmin: check access to a restricted document')
  ->back()
  ->click('Another Testdoc')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'show')
  ->responseContains('This is a yet another test document')  
;

$b
  ->diag('create as masteradmin')
  ->get('ullWiki/create')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'create')
  ->checkResponseElement('input#fields_subject', true)
;

$b
  ->diag('edit as masteradmin')
  ->get('ullWiki/edit/docid/2')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'edit')
  ->checkResponseElement('input[id="fields_subject"][value="Another Testdoc"]', true)
;


//logged in as privileged user
// "testuser" is member of the wiki-admins, therefore he can read both the public and the private doc, and edit both
$b
  ->diag('list as wiki-admin')
  ->get('ullUser/logout')
  ->get('ullAdmin/index')
  ->loginAsTestUser()
  ->get('ullWiki/list/order/subject/order_dir/asc')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'list')
  ->checkResponseElement($dgsList->getFullRowSelector(), 3) // number of rows
  ->checkResponseElement($dgsList->get(1, 'subject'), 'Another Testdoc')
  ->checkResponseElement($dgsList->get(2, 'subject'), 'Testdoc')
  ->checkResponseElement($dgsList->get(3, 'subject'), 'Testdoc readable for logged in users')
;

$b
  ->diag('show as wiki-admin: check access to a public document')
  ->click('Testdoc')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'show')
  ->responseContains('This is a test document')  
;

$b
  ->diag('show as wiki-admin: check access to a restricted document')
  ->back()
  ->click('Another Testdoc')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'show')
  ->responseContains('This is a yet another test document')  
;

$b
  ->diag('show as wiki-admin: check access to document readable for logged in users')
  ->back()
  ->click('Testdoc readable for logged in users')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'show')
  ->responseContains('This is a testdoc readable for logged in users')  
;

$b
  ->diag('create as wiki-admin')
  ->get('ullWiki/create')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'create')
  ->checkResponseElement('input#fields_subject', true)
;

$b
  ->diag('edit as wiki-admin: public readable doc')
  ->get('ullWiki/edit/docid/1')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'edit')
  ->checkResponseElement('input[id="fields_subject"][value="Testdoc"]', true)
;

$b
  ->diag('edit as wiki-admin: restricted doc')
  ->get('ullWiki/edit/docid/2')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'edit')
  ->checkResponseElement('input[id="fields_subject"][value="Another Testdoc"]', true)
;

$b
  ->diag('edit as wiki-admin: readable for logged in users doc')
  ->get('ullWiki/edit/docid/3')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'edit')
  ->checkResponseElement('input[id="fields_subject"][value="Testdoc readable for logged in users"]', true)
;

// as unprivileged logged in user
// delete testuser's Wiki-Admins membership to degrade him to an unpriviledged user
Doctrine::getTable('UllEntityGroup')->findOneByUllGroupId(
  Doctrine::getTable('UllGroup')->findOneByDisplayName('WikiAdmins')->id
)->delete();

$b
  ->diag('list as test_user')
  ->get('ullWiki/list/order/subject/order_dir/asc')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'list')
  ->checkResponseElement($dgsList->getFullRowSelector(), 2) // number of rows
  ->checkResponseElement($dgsList->get(1, 'subject'), 'Testdoc')
  ->checkResponseElement($dgsList->get(2, 'subject'), 'Testdoc readable for logged in users')
;

$b
  ->diag('show as test_user: check access to a public document')
  ->click('Testdoc')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'show')
  ->responseContains('This is a test document')  
;

$b
  ->diag('show as test_user: check access to document readable for logged in users')
  ->back()
  ->click('Testdoc readable for logged in users')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'show')
  ->responseContains('This is a testdoc readable for logged in users')  
;

$b
  ->diag('show: check access to a restricted document')
  ->get('ullWiki/show/docid/2')
  ->responseContains('No Access') 
;

$b
  ->diag('create as test_user')
  ->get('ullWiki/create')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'create')
  ->checkResponseElement('input#fields_subject', true)
;

$b
  ->diag('edit as test_user: public readable doc')
  ->get('ullWiki/edit/docid/1')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'edit')
  ->checkResponseElement('input[id="fields_subject"][value="Testdoc"]', true)
;

$b
  ->diag('edit as test_user: restricted doc')
  ->get('ullWiki/edit/docid/2')
  ->responseContains('No Access')   
;

$b
  ->diag('edit as test_user: readable for logged in users doc')
  ->get('ullWiki/edit/docid/3')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'edit')
  ->checkResponseElement('input[id="fields_subject"][value="Testdoc readable for logged in users"]', true)
;