<?php
//php symfony test:all
//php symfony test:functional frontend ullWiki


$app = 'frontend';
include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

//deactivated because we temp. removed the global search box

//$b
//  ->diag('Wiki search box test')
//  ->get('/')
//  ->isStatusCode(200)
//  ->isRequestParameter('module', 'myModule')
//  ->isRequestParameter('action', 'index')
//  ->checkResponseElement('#filter_search', true)
//  #->checkResponseElement('img[src="/ullWikiThemeNGPlugin/images/action_icons/search_16x16.png"]', true)
//  ->setField('filter[search]', 'Another')
//  ->click('search_header')
//  ->isStatusCode(200)
//  ->isRequestParameter('module', 'ullWiki')
//  ->isRequestParameter('action', 'list')
//  ->checkResponseElement('table > tbody > tr', 1)
//  ->checkResponseElement('tr > td + td + td', 'Another Testdoc')
//  ;

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
  ->setField('filter[search]', 'This is a test document')
  ->setField('filter[fulltext]', true)
  ->click('Search_16x16')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'list')
  ->checkResponseElement('table > tbody > tr', 1)
  ->checkResponseElement('tr > td + td + td', 'Testdoc')
;

$b
  ->diag('index: click on tag')
  ->get('ullWiki/index')
  ->click('ull_wiki_tag2')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('filter[search]', 'ull_wiki_tag2')
  ->checkResponseElement('table > tbody > tr', 1)
  ->checkResponseElement('tr > td + td + td', 'Testdoc')  
;

$b
  ->diag('create')
  ->get('ullWiki/list')
  ->click('Create')
  ->loginAsAdmin()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'create')
  ->isRequestParameter('docid', null)
  ->setField('fields[subject]', 'My new test subject')
  ->setField('fields[body]', '<b>My body</b>')
  ->setField('fields[duplicate_tags_for_search', 'testtag')
;

$b  
  ->diag('save only')
  ->click('Save only')
  ->isRedirected()
  ->followRedirect()
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('docid', 3)
  //why doesn't this work?
//  ->checkResponseElement('input#ull_wiki_subject[value="My new test subject"]', true)
  ->checkResponseElement('input#fields_subject', true)
//  ->dumpDie()
;
  
$b  
  ->diag('save and show')
  ->click('Save and show')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'show')
  ->responseContains('My new test subject')
;

$b
  ->diag('list')
  ->click('Result list')
//  ->get('ullWiki/list')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'list')
  ->click('My new test subject')
  ->responseContains('My new test subject')
;

$b
  ->diag('update (save and show, with malicious javascript)')
  ->click('Edit') 
  ->responseContains('My new test subject')
  ->setField('fields[subject]', 'My new test subject, updated')
  ->setField('fields[body]', '<b>body</b><script language="javascript" type="text/javascript">'.
                              'alert(\'Meeeeew!\');</script><pre><b>bold</b></pre>')
  ->click('Save and show')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'show')
  ->responseContains('<b>body</b><pre><b>bold</b></pre>')
;


$b
  ->diag('update (save and show)')
  ->click('Edit')
  ->responseContains('My new test subject')
  ->setField('fields[subject]', 'My new test subject, updated')
  ->setField('fields[body]', '<b>My body, updated</b>')
  ->click('Save and show')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'show')
  ->responseContains('<b>My body, updated</b>')
;

$b
  ->diag('update (Save and return to list)')
  ->click('Edit')
  ->responseContains('My new test subject')
  ->setField('fields[subject]', 'My new test subject, updated again')
  ->setField('fields[body]', '<b>My body, updated again</b>')
  ->click('Save and return to list')
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
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->checkResponseElement('table > tbody > tr', 1)
  ->responseContains('My new test subject, updated')
;

$b
  ->diag('search (for invalid keyword)')
  ->get('ullWiki/list')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'list')
  ->post('ullWiki/list', Array('filter[search]' => 'invalid'))
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->checkResponseElement('table', false)
  ->responseContains('No results found.')
;

$b
  ->diag('search (for tag)')
  ->get('ullWiki/list')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'list')
  ->post('ullWiki/list', Array('filter[search]' => 'testtag'))
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->responseContains('My new test subject, updated')
  ->checkResponseElement('table > tbody > tr', 1)
;

$b
  ->diag('sorting')
  ->get('ullWiki/list')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'list')
  ->checkResponseElement('table > tbody > tr', 3)
  ->checkResponseElement('tr > td + td + td', 'My new test subject, updated again')
  ->checkResponseElement('tr + tr > td + td + td', 'Testdoc')
  ->checkResponseElement('tr + tr + tr > td + td + td', 'Another Testdoc')

  ->click('ID')
  ->checkResponseElement('tr > td + td + td', 'Testdoc')
  ->checkResponseElement('tr + tr > td + td + td', 'Another Testdoc')
  ->checkResponseElement('tr + tr + tr > td + td + td', 'My new test subject, updated again')
  
  ->click('Subject')
  ->checkResponseElement('tr > td + td + td', 'Another Testdoc')
  ->checkResponseElement('tr + tr > td + td + td', 'My new test subject, updated again')
  ->checkResponseElement('tr + tr + tr > td + td + td', 'Testdoc')
  
  ->click('Updated at')
  ->checkResponseElement('tr > td + td + td', 'Testdoc')
  ->checkResponseElement('tr + tr > td + td + td', 'Another Testdoc')
  ->checkResponseElement('tr + tr + tr > td + td + td', 'My new test subject, updated again')
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
  ->checkResponseElement('table > tbody > tr', 2)
  ->checkResponseElement('body', '!/My new test subject/')
;

$b
  ->diag('create values with tags in it (check output escaping)')
  ->get('ullWiki/index')
  ->click('New entries')
  ->click('Create')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'create')
  ->setField('fields[subject]', 'tag: <i>italy</i>')
  ->click('Save and return to list')
  ->isRedirected()
  ->followRedirect()  

  ->isStatusCode(200)    
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'list')
  ->checkResponseElement('tr > td + td + td > b > a > i', false)
;
