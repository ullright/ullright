<?php
//php symfony test:functional frontend ullWiki


$app = 'frontend';
include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

 
$b
  ->diag('Wiki Home')
	->get('ullWiki/index')
	->isStatusCode(200)
	->isRequestParameter('module', 'ullWiki')
	->isRequestParameter('action', 'index')
	->responseContains('Wiki Home')
;	
  
$b
  ->diag('create')
  ->click('Create')
  ->loginAsAdmin()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'create')
  ->isRequestParameter('docid', null)
#  ->responseContains('Create')
  ->setField('ull_wiki[subject]', 'My new test subject')
  ->setField('ull_wiki[body]', '<b>My body</b>')
  ->click('Save and show')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'show')
  ->responseContains('My new test subject')
  ->get('ullWiki/list')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'list')
  ->responseContains('My new test subject')
;

$b
  ->diag('list')
  ->get('ullWiki/list')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'list')
  ->click('My new test subject')
  ->responseContains('My new test subject')
;

$b
  ->diag('update')
  ->get('ullWiki/edit/docid/3')
  ->responseContains('My new test subject')
  ->setField('ull_wiki[subject]', 'My new test subject, updated')
  ->setField('ull_wiki[body]', '<b>My body, updated</b>')
  ->click('Save and show')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'show')
  ->click('My new test subject, updated')
  ->responseContains('<b>My body, updated</b>')
;

$b
  ->diag('search')
  ->get('ullWiki/list')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'list')
  ->post('ullWiki/list', Array('search' => 'updated'))
  ->isStatusCode(200)
  ->responseContains('My new test subject, updated')
;

$b
  ->diag('sorting')
  ->get('ullWiki/list')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'list')
  ->responseContains('3 results found.')
#  ->checkResponseElement('div.ullwiki_header > div > h3 > a', 'My new test subject, updated', array('position' => 0))
#  ->checkResponseElement('div.ullwiki_header > div > h3 > a', 'Testdoc', array('position' => 1))
#  ->checkResponseElement('div.ullwiki_header > div > h3 > a', 'Another Testdoc', array('position' => 2))

  ->click('DocId')
#  ->checkResponseElement('div.ullwiki_header > div > h3 > a', 'Testdoc', array('position' => 0))
#  ->checkResponseElement('div.ullwiki_header > div > h3 > a', 'Another Testdoc', array('position' => 1))
#  ->checkResponseElement('div.ullwiki_header > div > h3 > a', 'My new test subject, updated', array('position' => 2))
  
  ->click('Subject')
#  ->checkResponseElement('div.ullwiki_header > div > h3 > a', 'Another Testdoc', array('position' => 0))
#  ->checkResponseElement('div.ullwiki_header > div > h3 > a', 'My new test subject, updated', array('position' => 1))
#  ->checkResponseElement('div.ullwiki_header > div > h3 > a', 'Testdoc', array('position' => 2))
  
  ->click('Date ascending')
#  ->checkResponseElement('div.ullwiki_header > div > h3 > a', 'Testdoc', array('position' => 0))
#  ->checkResponseElement('div.ullwiki_header > div > h3 > a', 'Another Testdoc', array('position' => 1))
#  ->checkResponseElement('div.ullwiki_header > div > h3 > a', 'My new test subject, updated', array('position' => 2))
;

$b
  ->diag('delete')
  ->get('ullWiki/list')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'list')
  ->get('ullWiki/delete/docid/3')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'list')
  ->responseContains('2 results found.')
  ->checkResponseElement('body', '!/My new test subject/')
;
