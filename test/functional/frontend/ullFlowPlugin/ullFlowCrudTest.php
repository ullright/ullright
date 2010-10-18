<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$dgsEditTT = $b->getDgsUllFlowEditTroubleTicket();
$dgsEditMem = $b->getDgsUllFlowMemory();
$dgsList = $b->getDgsUllFlowListGeneric();
$dgsListTT = $b->getDgsUllFlowListTroubleTicket();


$b
  ->diag('ullFlow Home')
  ->get('ullAdmin/index')
  ->loginAsAdmin()
  ->get('ullFlow/index')
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
  ->checkResponseElement($dgsEditTT->getFullRowSelector(), 9) // number of displayed fields
  ->checkResponseElement($dgsEditTT->get('priority', 'value') . ' > select > option[selected="selected"]', 'Normal')
  ->checkResponseElement('ul.tag-cloud a ', 'ull_flow_tag1')
  ->checkResponseElement('body', '!/Progress/')
  ->checkResponseElement('input#fields_memory_comment', true)
  ->setField('fields[my_email]', 'foobar')
  ->setField('fields[memory_comment]', 'My memory comment')
  ->setField('fields[my_priority]', 2)
  ->setField('fields[my_tags]', 'my_test_tag')
  ->setField('fields[my_information_update]', 'mew, die katze')
;

$b
  ->diag('check validation errors, correct them and click "save_only"')
  ->click('Save only')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('app', 'trouble_ticket')
  ->checkResponseElement($dgsEditTT->get('subject', 'error') . ' > ul > li', 'Required.')
  ->checkResponseElement($dgsEditTT->get('email', 'error') . ' > ul > li', 'Invalid.')
  ->checkResponseElement($dgsEditTT->get('priority', 'value') . ' select > option[selected="selected"]', 'High')
  ->checkResponseElement($dgsEditTT->get('project', 'error') . ' > ul > li', 'Required.')
  ->setField('fields[my_subject]', 'This is my original shiny little subject')
  ->setField('fields[my_due_date]', "2001-01-01 12:12:12")    
  ->setField('fields[my_email]', 'bender@ull.at')
  ->setField('fields[my_project]', 1)
;
  
$b->diag('check values and click "save_only"')  
  ->click('Save only')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'edit')
  ->checkResponseElement($dgsEditTT->get('subject', 'value') . ' > input[value="This is my original shiny little subject"]', true)
  ->checkResponseElement($dgsEditTT->get('email', 'value') . ' > input[value="bender@ull.at"]', true)
  ->checkResponseElement($dgsEditTT->get('priority', 'value') . ' > select > option[selected="selected"]', 'High')
  ->checkResponseElement($dgsEditTT->get('tags', 'value') . ' > input[value="my_test_tag"]', true)
  ->responseContains('Progress')
  ->checkResponseElement($dgsEditMem->get(1), '/Edited[\s]+by[\s]+Master[\s]+Admin/')  
  ->checkResponseElement($dgsEditMem->get(1) . ' > ul.ull_memory_comment > li', '/My memory comment/')
  ->setField('fields[my_subject]', 'This is my shiny little subject')
;

$b->diag('check list')
  ->click('Save and return to list')
  ->isRedirected()
  ->followRedirect()  
  ->isStatusCode(200)    
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->checkResponseElement($dgsListTT->getFullRowSelector(), 3) // number of rows
  ->checkResponseElement($dgsListTT->get(1, 'subject'), 'This is my shiny little subject')  
  ->checkResponseElement($dgsListTT->get(1, 'priority'), 'High')
;

$b
  ->diag('edit -> save only')
  // doesn't work (yet -> sf1.2)
//  ->click('edit')
  ->get('ullFlow/edit/doc/5')
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('doc', 5)
  ->checkResponseElement($dgsEditMem->getFullRowSelector(), 4) // number of memory entries
  ->checkResponseElement($dgsEditTT->get('subject', 'value') . ' > input[value="This is my shiny little subject"]', true)
  ->setField('fields[my_subject]', 'This is my shiny little edited subject')

  ->click('Save and return to list')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)    
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('app', 'trouble_ticket')
  ->checkResponseElement($dgsListTT->getFullRowSelector(), 3) // number of rows
  ->checkResponseElement($dgsListTT->get(1, 'subject'), 'This is my shiny little edited subject')
  ->checkResponseElement($dgsListTT->get(2, 'subject'), 'AAA My second trouble ticket')
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
  ->checkResponseElement($dgsListTT->getFullRowSelector(), 3) // number of rows
  ->checkResponseElement($dgsListTT->get(1, 'subject'), 'This is my shiny little edited subject')
  ->checkResponseElement($dgsListTT->get(1, 'assigned_to'), 'Helpdesk')
;  

$b
  ->diag('index: click on created tag')
  ->get('ullFlow/index')
  ->click('my_test_tag')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->checkResponseElement($dgsList->getFullRowSelector(), 1) // number of rows
  ->checkResponseElement($dgsList->get(1, 'subject'), 'This is my shiny little edited subject')
;

$b
  ->diag('create values with tags in it (check output escaping)')
  ->get('ullFlow/list/app/trouble_ticket')
  ->click('Create')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'create')
  ->isRequestParameter('app', 'trouble_ticket')
  ->setField('fields[my_subject]', 'tag: <i>italy</i>')
  ->setField('fields[my_project]', 1)
  ->click('Save and return to list')
  ->isRedirected()
  ->followRedirect()  
  ->isStatusCode(200)    
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->checkResponseElement($dgsListTT->get(1, 'subject') . ' > b > a > i', false)
;

$b
  // We had an issue with widget output esacping here...
  ->diag('Test subject toString() with special chars (e.g. german umlauts)')
  ->get('ullFlow/list/app/trouble_ticket')
  ->click('Create')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'create')
  ->isRequestParameter('app', 'trouble_ticket')
  ->setField('fields[my_subject]', 'Beste Grüße!')
  ->setField('fields[my_project]', 1)
  ->click('Save only')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'edit')  
  ->checkResponseElement('#ull_flow_edit_header > h1', 'Trouble ticket "Beste Grüße!"')
  ->click('Save and return to list')
  ->isRedirected()
  ->followRedirect()  
  ->isStatusCode(200)    
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->checkResponseElement($dgsListTT->get(1, 'subject') . ' > b > a', 'Beste Grüße!')
;


$b->resetDatabase();
$b
  ->diag('delete')
  ->get('ullFlow/list')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->checkResponseElement($dgsListTT->getFullRowSelector(), 4) // number of rows
  
  ->click('Delete')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->checkResponseElement($dgsList->getFullRowSelector(), 3) // number of rows
;
