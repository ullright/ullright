<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();


$b
  ->diag('Edit doc')
  ->get('ullAdmin/index')
  ->loginAsAdmin()
  ->get('ullFlow/edit/doc/1')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('doc', '1')
  ->responseContains('Testdoc');
;  

$b
  ->diag('Manage wiki links')
  ->click('Manage wiki links')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'wikiLink')
  ->isRequestParameter('doc', '1')   
  ->isRequestParameter('column', 'my_wiki_link')
  ->checkResponseElement('table.ull_flow_upload > tbody > tr', 1)
  ->checkResponseElement('table.ull_flow_upload > tbody > tr > td > a', 'Testdoc')  
;

$b
  ->diag('Link to a new wiki document')
  ->click('Link to new wiki document')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'create')
  ->isRequestParameter('return_var', 'ull_wiki_doc_id')
  ->setField('fields[subject]', 'The uplift mofo party plan')
;
  
$b
  ->diag('Save wiki doc and return')
  ->click('Save and return to list')
  ->isRedirected()
  ->followRedirect()
;

$b
  ->diag('Check for newely created link')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'wikiLink')
  ->isRequestParameter('doc', '1')   
  ->isRequestParameter('column', 'my_wiki_link')
  ->isRequestParameter('ull_wiki_doc_id', '3')
  ->checkResponseElement('table.ull_flow_upload > tbody > tr', 2)
  ->checkResponseElement('table.ull_flow_upload > tbody > tr > td > a', 'Testdoc')    
  ->checkResponseElement('table.ull_flow_upload > tbody > tr + tr > td > a', 'The uplift mofo party plan')
;  

$b
  ->diag('Link to an existing wiki document')
  ->click('Link to existing wiki document')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'index')
  ->isRequestParameter('return_var', 'ull_wiki_doc_id')
  ->setField('filter[search]', 'Another')
  ->click('Search_16x16')
  ->isRedirected()
  ->followRedirect()
  ->click('Another Testdoc')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'show')
  ->isRequestParameter('return_var', 'ull_wiki_doc_id')
  ->isRequestParameter('docid', '2')
  ->responseContains('Another Testdoc')
;

$b
  ->diag('Select document and return')
  ->click('Link to this document')
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'wikiLink')
  ->isRequestParameter('doc', '1')   
  ->isRequestParameter('column', 'my_wiki_link')
  ->isRequestParameter('ull_wiki_doc_id', '2')
  ->checkResponseElement('table.ull_flow_upload > tbody > tr', 3)
  ->checkResponseElement('table.ull_flow_upload > tbody > tr > td > a', 'Testdoc')    
  ->checkResponseElement('table.ull_flow_upload > tbody > tr + tr > td > a', 'The uplift mofo party plan')
  ->checkResponseElement('table.ull_flow_upload > tbody > tr + tr + tr > td > a', 'Another Testdoc')
; 

