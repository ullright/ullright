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
  ->diag('create with missing title and invalid email')
  ->click('Trouble ticket tool')
  ->click('All entries')
  ->click('Create')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'create')
  ->isRequestParameter('app', 'trouble_ticket')
  ->checkResponseElement('table tr', 4) // number of displayed fields
  ->checkResponseElement('ul.tag-cloud a ', 'ull_flow_tag1')
  ->checkResponseElement('body', '!/Progress/')
  ->checkResponseElement('input#fields_memory_comment', true)
  ->setField('fields[my_email]', 'foobar')
  ->setField('fields[memory_comment]', 'My memory comment')
  ->setField('fields[column_tags]', 'my_test_tag')
  ->click('Save only')
;

$b
  ->diag('check validation errors, correct them and click "save_only"')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('app', 'trouble_ticket')
  ->checkResponseElement('tr > td + td + td > ul > li', 'Required.')
  ->checkResponseElement('tr + tr + tr > td + td + td > ul > li', 'Invalid.')
  ->setField('fields[my_title]', 'This is my original shiny little title')
  ->setField('fields[my_datetime]', "2001-01-01 01:01:01")    
  ->setField('fields[my_email]', 'bender@ull.at')  
  ->click('Save only')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'edit')
  ->checkResponseElement('tr > td + td > input[value="This is my original shiny little title"]', true)
  ->checkResponseElement('tr + tr + tr > td + td > input[value="bender@ull.at"]', true)
  ->checkResponseElement('tr + tr + tr + tr > td + td > input[value="my_test_tag"]', true)
  ->checkResponseElement('h3', 'Progress')
  ->checkResponseElement('ul.ull_flow_memories > li', '/Created[\s]+by[\s]+Master[\s]+Admin[\s]+at/')
  ->checkResponseElement('ul.ull_flow_memories > li > ul > li', '/Comment: My memory comment/')
  ->setField('fields[my_title]', 'This is my shiny little title')  
  ->click('Save and close')
  ->isRedirected()
  ->followRedirect()  
  ->isStatusCode(200)    
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->checkResponseElement('table > tbody > tr', 3) // number of rows
  ->checkResponseElement('tbody > tr > td + td + td + td', 'This is my shiny little title')  
;

$b
  ->diag('edit -> save only')
  // doesn't work (yet -> sf1.2)
//  ->click('edit')
  ->get('ullFlow/edit/doc/5')
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('doc', 5)
  ->checkResponseElement('ul.ull_flow_memories > li', '/Created[\s]+by[\s]+Master[\s]+Admin[\s]+at/')
  ->checkResponseElement('ul.ull_flow_memories > li + li', '/Edited[\s]+by[\s]+Master[\s]+Admin[\s]+at/')
  ->setField('fields[my_title]', 'This is my shiny little edited title')

  ->click('Save and close')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)    
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('app', 'trouble_ticket')
  ->checkResponseElement('table > tbody > tr', 3) // number of rows
  ->checkResponseElement('tbody > tr > td + td + td + td', 'This is my shiny little edited title')
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
  ->checkResponseElement('tbody > tr > td + td + td + td', 'This is my shiny little edited title')
  ->checkResponseElement('tbody > tr > td + td + td + td + td + td', 'Helpdesk (Group)')
;  

$b
  ->diag('index: click on created tag')
  ->get('ullFlow/index')
  ->click('my_test_tag')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->checkResponseElement('table > tbody > tr', 1) // number of rows
  ->checkResponseElement('tbody > tr > td + td + td + td', 'This is my shiny little edited title')
;

//TODO: test mandatory comment

