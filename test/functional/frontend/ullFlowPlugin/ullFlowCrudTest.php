<?php

$app = 'frontend';
include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();


$b
  ->diag('ullFlow Home')
  ->get('ullFlow/index')
  ->loginAsAdmin()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'index')
;  

$b
  ->diag('create with missing subject and invalid email')
  ->click('Trouble ticket tool')
  ->click('All entries')
  ->click('Create')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'create')
  ->isRequestParameter('app', 'trouble_ticket')
  ->checkResponseElement('table tr', 7) // number of displayed fields
  ->checkResponseElement('tr + tr + tr + tr > td + td > select > option[selected="selected"]', 'Normal')
  ->checkResponseElement('ul.tag-cloud a ', 'ull_flow_tag1')
  ->checkResponseElement('body', '!/Progress/')
  ->checkResponseElement('input#fields_memory_comment', true)
  ->setField('fields[my_email]', 'foobar')
  ->setField('fields[memory_comment]', 'My memory comment')
  ->setField('fields[column_priority]', 2)
  ->setField('fields[column_tags]', 'my_test_tag')
;

$b
  ->diag('check validation errors, correct them and click "save_only"')
  ->click('Save only')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('app', 'trouble_ticket')
  ->checkResponseElement('tr > td + td + td > ul > li', 'Required.')
  ->checkResponseElement('tr + tr + tr > td + td + td > ul > li', 'Invalid.')
  ->checkResponseElement('tr + tr + tr + tr > td + td > select > option[selected="selected"]', 'High')
  ->setField('fields[my_subject]', 'This is my original shiny little subject')
  ->setField('fields[my_datetime]', "2001-01-01 01:01:01")    
  ->setField('fields[my_email]', 'bender@ull.at')
;
  
$b->diag('check values and click "save_close"')  
  ->click('Save only')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'edit')
  ->checkResponseElement('tr > td + td > input[value="This is my original shiny little subject"]', true)
  ->checkResponseElement('tr + tr + tr > td + td > input[value="bender@ull.at"]', true)
  ->checkResponseElement('tr + tr + tr + tr > td + td > select > option[selected="selected"]', 'High')  
  ->checkResponseElement('tr + tr + tr + tr + tr > td + td > input[value="my_test_tag"]', true)
  ->responseContains('Progress')
  ->checkResponseElement('div#ull_flow_memories > ul > ul > li', '/Created[\s]+by[\s]+Master[\s]+Admin/')
  ->checkResponseElement('div#ull_flow_memories > ul > ul > li + li > ul', '/My memory comment/')
  ->setField('fields[my_subject]', 'This is my shiny little subject')
;

$b->diag('check list')
  ->click('Save and close')
  ->isRedirected()
  ->followRedirect()  
  ->isStatusCode(200)    
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->checkResponseElement('table > tbody > tr', 3) // number of rows
  ->checkResponseElement('tbody > tr > td + td + td + td', 'This is my shiny little subject')  
  ->checkResponseElement('tbody > tr > td + td + td + td + td + td', 'High')
;

$b
  ->diag('edit -> save only')
  // doesn't work (yet -> sf1.2)
//  ->click('edit')
  ->get('ullFlow/edit/doc/5')
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('doc', 5)
  ->checkResponseElement('div#ull_flow_memories > ul > ul > li', '/Edited[\s]+by[\s]+Master[\s]+Admin/')
  ->checkResponseElement('div#ull_flow_memories > ul > ul > li + li', '/Created[\s]+by[\s]+Master[\s]+Admin/')
  ->setField('fields[my_subject]', 'This is my shiny little edited subject')

  ->click('Save and close')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)    
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('app', 'trouble_ticket')
  ->checkResponseElement('table > tbody > tr', 3) // number of rows
  ->checkResponseElement('tbody > tr > td + td + td + td', 'This is my shiny little edited subject')
  ->checkResponseElement('tbody > tr + tr > td + td + td + td', 'AAA My second trouble ticket')
;   

$b
  ->diag('edit -> send')
  ->get('ullFlow/edit/doc/5')
  ->click('Send')

  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)    
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('app', 'trouble_ticket')
  ->checkResponseElement('table > tbody > tr', 3) // number of rows
  ->checkResponseElement('tbody > tr > td + td + td + td', 'This is my shiny little edited subject')
  ->checkResponseElement('tbody > tr > td + td + td + td + td + td + td', 'Helpdesk (Group)')
;  

$b
  ->diag('index: click on created tag')
  ->get('ullFlow/index')
  ->click('my_test_tag')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->checkResponseElement('table > tbody > tr', 1) // number of rows
  ->checkResponseElement('tbody > tr > td + td + td + td', 'This is my shiny little edited subject')
;


$b->resetDatabase();
$b
  ->diag('delete')
  ->get('ullFlow/list')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->checkResponseElement('table > tbody > tr', 4) // number of rows
  
  ->click('Delete')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->checkResponseElement('table > tbody > tr', 3) // number of rows
;
