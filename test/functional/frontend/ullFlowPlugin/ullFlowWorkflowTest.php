<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$dgsEditHead = $b->getDgsUllFlowHeader();
$dgsEditMem = $b->getDgsUllFlowMemory();
$dgsListTT = $b->getDgsUllFlowListTroubleTicket();

$b
  ->diag('ullFlow Home')
  ->get('ullAdmin/index')
  ->loginAsTestUser()
  ->get('ullFlow/index')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'index')
;  

$b
  ->diag('create and send as test_user')
  ->click('Trouble ticket tool')
  ->click('All entries')
  ->click('Create')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'create')
  ->isRequestParameter('app', 'trouble_ticket')
  ->setField('fields[my_subject]', 'Urgently use ullright')
  ->setField('fields[my_project]', 1)
  ->click('Send')
  ->isRedirected()
  ->followRedirect()
  ->click('Log out')
;

$b
  ->diag('login as helpdesk_user: check that entry has been created properly')
  ->get('ullAdmin/index')
  ->loginAs('helpdesk_user')
  ->get('ullFlow/index')
  ->click('Trouble ticket tool')
  ->click('All entries')
  ->click('Edit')  
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('doc', '5')
  ->checkResponseElement('#ull_flow_edit_header h1', 'Trouble ticket "Urgently use ullright"')
  ->checkResponseElement($dgsEditHead->get('created'), '/Created by[\s]+Test User/')
  ->checkResponseElement($dgsEditHead->get('status'), '/Last action:[\s]+Sent[\s]+by[\s]+Test User/')
  ->checkResponseElement($dgsEditHead->get('next'), '/Next one:[\s]+Helpdesk[\s]+\(Step[\s]+Helpdesk dispatcher \(Trouble ticket tool\)\)/') 
  ->checkResponseElement($dgsEditMem->getFullRowSelector(), 2) // number of memory entries
  ->checkResponseElement($dgsEditMem->get(1), '/Sent[\s]+by[\s]+Test User/')
  ->checkResponseElement($dgsEditMem->get(2), '/Created[\s]+by[\s]+Test User/')
;

$b
  ->diag('don\'t set the assigened to user and click assign')
  ->click('Assign')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('doc', '5')
  ->checkResponseElement('.edit_action_buttons_left ul ul.error_list > li', 'Required.')
;

$b
  ->diag('set assigned to "Helpdesk Admin User" and click assign')  
  ->setField('fields[ull_flow_action_assign_to_user_ull_entity]', Doctrine::getTable('UllUser')->findOneByDisplayName('Helpdesk Admin User')->id)
  ->click('Assign')
  ->isRedirected()
  ->followRedirect()
  ->click('Log out')  
;

$b
  ->diag('login as helpdesk_admin_user: check that entry has been updated properly')
  ->get('ullAdmin/index')
  ->loginAs('helpdesk_admin_user')
  ->get('ullFlow/index')
  ->click('Trouble ticket tool')
  ->click('All entries')
  ->click('Edit')  
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('doc', '5')
  ->checkResponseElement('#ull_flow_edit_header h1', 'Trouble ticket "Urgently use ullright"')
  ->checkResponseElement($dgsEditHead->get('created'), '/Created by[\s]+Test User/')
  ->checkResponseElement($dgsEditHead->get('status'), '/Last action:[\s]+Assigned to user[\s]+Helpdesk Admin User[\s]+by[\s]+Helpdesk User/')
  ->checkResponseElement($dgsEditHead->get('next'), '/Next one:[\s]+Helpdesk Admin User[\s]+\(Step[\s]+Troubleshooter \(Trouble ticket tool\)\)/') 
  ->checkResponseElement($dgsEditMem->getFullRowSelector(), 3) // number of memory entries
  ->checkResponseElement($dgsEditMem->get(1), '/Assigned to user[\s]+Helpdesk Admin User[\s]+by[\s]+Helpdesk User/')  
  ->checkResponseElement($dgsEditMem->get(2), '/Sent[\s]+by[\s]+Test User/')
  ->checkResponseElement($dgsEditMem->get(3), '/Created[\s]+by[\s]+Test User/')
  ->responseContains('Reject')
;

$b
  ->diag('click return')
  ->click('Return')
  ->isRedirected()
  ->followRedirect()
  ->click('Log out')  
;

$b
  ->diag('login as helpdesk_user: check that entry has been created properly')
  ->get('ullAdmin/index')
  ->loginAs('helpdesk_user')
  ->get('ullFlow/index')
  ->click('Trouble ticket tool')
  ->click('All entries')
  ->click('Edit')  
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('doc', '5')
  ->checkResponseElement('#ull_flow_edit_header h1', 'Trouble ticket "Urgently use ullright"')
  ->checkResponseElement($dgsEditHead->get('created'), '/Created by[\s]+Test User/')
  ->checkResponseElement($dgsEditHead->get('status'), '/Last action:[\s]+Returned[\s]+by[\s]+Helpdesk Admin User/')
  ->checkResponseElement($dgsEditHead->get('next'), '/Next one:[\s]+Helpdesk[\s]+\(Step[\s]+Helpdesk dispatcher \(Trouble ticket tool\)\)/') 
  ->checkResponseElement($dgsEditMem->getFullRowSelector(), 4) // number of memory entries
  ->checkResponseElement($dgsEditMem->get(1), '/Returned[\s]+by[\s]+Helpdesk Admin User/')
  ->checkResponseElement($dgsEditMem->get(2), '/Assigned to user[\s]+Helpdesk Admin User[\s]+by[\s]+Helpdesk User/')
  ->checkResponseElement($dgsEditMem->get(3), '/Sent[\s]+by[\s]+Test User/')
  ->checkResponseElement($dgsEditMem->get(4), '/Created[\s]+by[\s]+Test User/')
;

$b
  ->diag('again assign to Helpdesk Admin User')
  ->setField('fields[ull_flow_action_assign_to_user_ull_entity]', Doctrine::getTable('UllUser')->findOneByDisplayName('Helpdesk Admin User')->id)
  ->click('Assign')
  ->isRedirected()
  ->followRedirect()
  ->click('Log out')   
;

$b
  ->diag('again login as helpdesk_admin_user: check that entry has been updated properly')
  ->get('ullAdmin/index')
  ->loginAs('helpdesk_admin_user')
  ->get('ullFlow/index')
  ->click('Trouble ticket tool')
  ->click('All entries')
  ->click('Edit')  
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('doc', '5')
  ->checkResponseElement('#ull_flow_edit_header h1', 'Trouble ticket "Urgently use ullright"')
  ->checkResponseElement($dgsEditHead->get('created'), '/Created by[\s]+Test User/')
  ->checkResponseElement($dgsEditHead->get('status'), '/Last action:[\s]+Assigned to user[\s]+Helpdesk Admin User[\s]+by[\s]+Helpdesk User/')
  ->checkResponseElement($dgsEditHead->get('next'), '/Next one:[\s]+Helpdesk Admin User[\s]+\(Step[\s]+Troubleshooter \(Trouble ticket tool\)\)/') 
  ->checkResponseElement($dgsEditMem->getFullRowSelector(), 5) // number of memory entries
  ->checkResponseElement($dgsEditMem->get(1), '/Assigned to user[\s]+Helpdesk Admin User[\s]+by[\s]+Helpdesk User/')
  ->checkResponseElement($dgsEditMem->get(2), '/Returned[\s]+by[\s]+Helpdesk Admin User/')
  ->checkResponseElement($dgsEditMem->get(3), '/Assigned to user[\s]+Helpdesk Admin User[\s]+by[\s]+Helpdesk User/')
  ->checkResponseElement($dgsEditMem->get(4), '/Sent[\s]+by[\s]+Test User/')
  ->checkResponseElement($dgsEditMem->get(5), '/Created[\s]+by[\s]+Test User/')
;

$b
  ->diag('click reject without entering a comment')
  ->click('Reject')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('doc', '5')
  ->checkResponseElement('.edit_action_buttons_left > ul.error_list > li', 'Required.')
;  
  
$b
->diag('enter reject comment and reject')
  ->setField('fields[memory_comment]', 'Sooo sorry, but I worry!')
  ->click('Reject')  
  ->isRedirected()
  ->followRedirect()
  ->click('Log out')  
;


$b
  ->diag('login as helpdesk_user: check that entry has been updated properly')
  ->get('ullAdmin/index')
  ->loginAs('helpdesk_user')
  ->get('ullFlow/index')
  ->click('Trouble ticket tool')
  ->click('All entries')
  ->click('Edit')  
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('doc', '5')
  ->checkResponseElement('#ull_flow_edit_header h1', 'Trouble ticket "Urgently use ullright"')
  ->checkResponseElement($dgsEditHead->get('created'), '/Created by[\s]+Test User/')
  ->checkResponseElement($dgsEditHead->get('status'), '/Last action:[\s]+Rejected[\s]+by[\s]+Helpdesk Admin User/')
  ->checkResponseElement($dgsEditHead->get('next'), '/Next one:[\s]+Helpdesk[\s]+\(Step[\s]+Helpdesk dispatcher \(Trouble ticket tool\)\)/') 
  ->checkResponseElement($dgsEditMem->getFullRowSelector(), 7) // number of memory entries
  ->checkResponseElement($dgsEditMem->get(1), '/Rejected[\s]+by[\s]+Helpdesk Admin User/')
  ->checkResponseElement($dgsEditMem->get(1) . ' > ul.ull_memory_comment > li', '/Sooo sorry, but I worry!/')
  ->checkResponseElement($dgsEditMem->get(2), '/Assigned to user[\s]+Helpdesk Admin User[\s]+by[\s]+Helpdesk User/')
  ->checkResponseElement($dgsEditMem->get(3), '/Returned[\s]+by[\s]+Helpdesk Admin User/')
  ->checkResponseElement($dgsEditMem->get(4), '/Assigned to user[\s]+Helpdesk Admin User[\s]+by[\s]+Helpdesk User/')
  ->checkResponseElement($dgsEditMem->get(5), '/Sent[\s]+by[\s]+Test User/')
  ->checkResponseElement($dgsEditMem->get(6), '/Created[\s]+by[\s]+Test User/')  
;

$b
  ->diag('close and check result list')
  ->click('Close')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)    
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('app', 'trouble_ticket')
  ->checkResponseElement($dgsListTT->getFullRowSelector(), 2) // number of rows
  ->checkResponseElement($dgsListTT->get(1, 'subject'), 'AAA My second trouble ticket')
  ->checkResponseElement($dgsListTT->get(2, 'subject'), 'My first trouble ticket')
;  

$b
  ->diag('select closed docs')
  ->setField('filter[status]', 'close')
  ->click('Search_16x16')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('app', 'trouble_ticket')
  ->isRequestParameter('filter[status]', 'close')
  ->checkResponseElement($dgsListTT->getFullRowSelector(), 1) // number of rows
  ->checkResponseElement($dgsListTT->get(1, 'subject'), 'Urgently use ullright')  
;

$b
  ->diag('check closed doc')
  ->click('Edit')  
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('doc', '5')
  ->checkResponseElement('#ull_flow_edit_header h1', 'Trouble ticket "Urgently use ullright"')
  ->checkResponseElement($dgsEditHead->get('created'), '/Created by[\s]+Test User/')
  ->checkResponseElement($dgsEditHead->get('status'), '/Last action:[\s]+Closed[\s]+by[\s]+Helpdesk User/')
  ->checkResponseElement($dgsEditHead->get('next'), false) 
  ->checkResponseElement($dgsEditMem->getFullRowSelector(), 8) // number of memory entries
  ->checkResponseElement($dgsEditMem->get(1), '/Closed[\s]+by[\s]+Helpdesk User/')
  ->checkResponseElement($dgsEditMem->get(2), '/Rejected[\s]+by[\s]+Helpdesk Admin User/')
  ->checkResponseElement($dgsEditMem->get(3), '/Assigned to user[\s]+Helpdesk Admin User[\s]+by[\s]+Helpdesk User/')  
  ->checkResponseElement($dgsEditMem->get(4), '/Returned[\s]+by[\s]+Helpdesk Admin User/')  
  ->checkResponseElement($dgsEditMem->get(5), '/Assigned to user[\s]+Helpdesk Admin User[\s]+by[\s]+Helpdesk User/')  
  ->checkResponseElement($dgsEditMem->get(6), '/Sent[\s]+by[\s]+Test User/')
  ->checkResponseElement($dgsEditMem->get(7), '/Created[\s]+by[\s]+Test User/')
;  

$b
  ->diag('edit closed doc and check that the doc action isn\'t overwritten')
  ->setField('fields[my_email]', 'serenus@spongebob.com')
  ->click('Save only')
  ->isRedirected()
  ->followRedirect()
  
  ->checkResponseElement($dgsEditHead->get('status'), '/Last action:[\s]+Closed[\s]+by[\s]+Helpdesk User/')  
  ->checkResponseElement($dgsEditMem->getFullRowSelector(), 9) // number of memory entries
  ->checkResponseElement($dgsEditMem->get(1), '/Edited[\s]+by[\s]+Helpdesk User/')
  ->checkResponseElement($dgsEditMem->get(2), '/Closed[\s]+by[\s]+Helpdesk User/')
;

$b
  ->diag('reopen and check result list')
  ->click('Reopen')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)    
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('app', 'trouble_ticket')
  ->checkResponseElement($dgsListTT->getFullRowSelector(), false) // number of rows  
;  

$b
  ->diag('select active docs')
  ->setField('filter[status]', '')
  ->click('Search_16x16')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('app', 'trouble_ticket')
  ->checkResponseElement($dgsListTT->getFullRowSelector(), 3) // number of rows
  ->checkResponseElement($dgsListTT->get(1, 'subject'), 'Urgently use ullright')
  ->checkResponseElement($dgsListTT->get(2, 'subject'), 'AAA My second trouble ticket')
  ->checkResponseElement($dgsListTT->get(3, 'subject'), 'My first trouble ticket')  
;

$b
  ->diag('edit reopend doc and check')
  ->click('Edit')  
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('doc', '5')
  ->checkResponseElement('#ull_flow_edit_header h1', 'Trouble ticket "Urgently use ullright"')
  ->checkResponseElement($dgsEditHead->get('created'), '/Created by[\s]+Test User/')
  ->checkResponseElement($dgsEditHead->get('status'), '/Last action:[\s]+Reopened[\s]+by[\s]+Helpdesk User/')
  ->checkResponseElement($dgsEditHead->get('next'), '/Next one:[\s]+Helpdesk[\s]+\(Step[\s]+Helpdesk dispatcher \(Trouble ticket tool\)\)/') 
  ->checkResponseElement($dgsEditMem->getFullRowSelector(), 10) // number of memory entries
  ->checkResponseElement($dgsEditMem->get(1), '/Reopened[\s]+by[\s]+Helpdesk User/')
  ->checkResponseElement($dgsEditMem->get(2), '/Edited[\s]+by[\s]+Helpdesk User/')
  ->checkResponseElement($dgsEditMem->get(3), '/Closed[\s]+by[\s]+Helpdesk User/')
  ->checkResponseElement($dgsEditMem->get(4), '/Rejected[\s]+by[\s]+Helpdesk Admin User/')
  ->checkResponseElement($dgsEditMem->get(5), '/Assigned to user[\s]+Helpdesk Admin User[\s]+by[\s]+Helpdesk User/')
  ->checkResponseElement($dgsEditMem->get(6), '/Returned[\s]+by[\s]+Helpdesk Admin User/')  
  ->checkResponseElement($dgsEditMem->get(7), '/Assigned to user[\s]+Helpdesk Admin User[\s]+by[\s]+Helpdesk User/')  
  ->checkResponseElement($dgsEditMem->get(8), '/Sent[\s]+by[\s]+Test User/')
  ->checkResponseElement($dgsEditMem->get(9), '/Created[\s]+by[\s]+Test User/')
; 
