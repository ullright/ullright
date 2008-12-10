<?php
//php symfony test:all
//php symfony test:functional frontend ullWiki


$app = 'frontend';
include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$b
  ->diag('Wiki search box test')
  ->get('/')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'myModule')
  ->isRequestParameter('action', 'index')
  ->checkResponseElement('#filter_search', true)
  #->checkResponseElement('img[src="/ullWikiThemeNGPlugin/images/action_icons/search_16x16.png"]', true)
  ->setField('filter[search]', 'Another')
  ->click('search_header')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'list')
  ->checkResponseElement('div.ullwiki.header', 1)
  ->checkResponseElement('div.ullwiki_header > div > h3 > a', 'Another Testdoc')
  ;

$b
  ->diag('Wiki Home')
	->get('ullWiki/index')
	->isStatusCode(200)
	->isRequestParameter('module', 'ullWiki')
	->isRequestParameter('action', 'index')
	->responseContains('Wiki Home')
;

$b
  ->diag('test wiki home searchbox')
  ->setField('filter[search]', 'yet')
  ->setField('filter[fulltext]', true)
  ->click('Search_16x16')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'list')
//  ->dumpDie()
  ->checkResponseElement('div.ullwiki.header', 1)
  ->checkResponseElement('div.ullwiki_header > div > h3 > a', 'Another Testdoc')
;

$b
  ->diag('index: click on tag')
  ->get('ullWiki/index')
  ->click('ull_wiki_tag2')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('filter[search]', 'ull_wiki_tag2')
  ->checkResponseElement('div.ullwiki.header', 1)
  ->checkResponseElement('div.ullwiki_header > div > h3 > a', 'Testdoc')
;

$b
  ->diag('create')
  ->click('Create')
  ->loginAsAdmin()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'create')
  ->isRequestParameter('docid', null)
  ->setField('ull_wiki[subject]', 'My new test subject')
  ->setField('ull_wiki[body]', '<b>My body</b>')
  ->setField('ull_wiki[duplicate_tags_for_search', 'testtag')
  ->diag('save only')
  ->click('Save only')
  ->isRedirected()
  ->followRedirect()
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('docid', 3)
  //why doesn't this work?
//  ->checkResponseElement('input#ull_wiki_subject[value="My new test subject"]', true)
  ->checkResponseElement('input#ull_wiki_subject', true)
//  ->dumpDie()
  ->diag('save and show')
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
  ->diag('update (save and show')
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
  ->diag('update (save and close)')
  ->get('ullWiki/edit/docid/3')
  ->responseContains('My new test subject')
  ->setField('ull_wiki[subject]', 'My new test subject, updated again')
  ->setField('ull_wiki[body]', '<b>My body, updated again</b>')
  ->click('Save and close')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'list')
  ->responseContains('My new test subject, updated again')
;




$b
  ->diag('search')
  ->get('ullWiki/list')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'list')
  ->post('ullWiki/list', Array('filter[search]' => 'updated'))
  ->isStatusCode(200)
  ->responseContains('My new test subject, updated')
  ->responseContains('1 result found.')
;

$b
  ->diag('search (for invalid keyword)')
  ->get('ullWiki/list')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'list')
  ->post('ullWiki/list', Array('filter[search]' => 'invalid'))
  ->isStatusCode(200)
  ->responseContains('No results found.')
;

$b
  ->diag('search (for tag)')
  ->get('ullWiki/list')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'list')
  ->post('ullWiki/list', Array('filter[search]' => 'testtag'))
  ->isStatusCode(200)
  ->responseContains('My new test subject, updated')
  ->responseContains('1 result found.')
;


$b
  ->diag('sorting')
  ->get('ullWiki/list')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'list')
  ->responseContains('3 results found.')
  ->checkResponseElement('div.ullwiki_header > div > h3 > a', 'My new test subject, updated again', array('position' => 0))
  ->checkResponseElement('div.ullwiki_header > div > h3 > a', 'Testdoc', array('position' => 1))
  ->checkResponseElement('div.ullwiki_header > div > h3 > a', 'Another Testdoc', array('position' => 2))

  ->click('DocId')
  ->checkResponseElement('div.ullwiki_header > div > h3 > a', 'Testdoc', array('position' => 0))
  ->checkResponseElement('div.ullwiki_header > div > h3 > a', 'Another Testdoc', array('position' => 1))
  ->checkResponseElement('div.ullwiki_header > div > h3 > a', 'My new test subject, updated again', array('position' => 2))
  
  ->click('Subject')
  ->checkResponseElement('div.ullwiki_header > div > h3 > a', 'Another Testdoc', array('position' => 0))
  ->checkResponseElement('div.ullwiki_header > div > h3 > a', 'My new test subject, updated again', array('position' => 1))
  ->checkResponseElement('div.ullwiki_header > div > h3 > a', 'Testdoc', array('position' => 2))
  
  ->click('Date ascending')
  ->checkResponseElement('div.ullwiki_header > div > h3 > a', 'Testdoc', array('position' => 0))
  ->checkResponseElement('div.ullwiki_header > div > h3 > a', 'Another Testdoc', array('position' => 1))
  ->checkResponseElement('div.ullwiki_header > div > h3 > a', 'My new test subject, updated again', array('position' => 2))
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
