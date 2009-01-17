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
  ->loginAsTestUser()
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
  ->click('Send')
  ->isRedirected()
  ->followRedirect()
  ->click('Log out')
;

$b
  ->diag('login as helpdesk_admin: check that entry has been created properly')
  ->get('ullFlow/index')
  ->loginAs('helpdesk_user')
  ->click('Trouble ticket tool')
  ->click('All entries')
  ->click('Edit')  
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('doc', '5')
  ->checkResponseElement('#ull_flow_edit_header h1', 'Trouble ticket "Urgently use ullright"')
  ->checkResponseElement('ul.ull_flow_edit_header_list > li', '/Created by[\s]+Test User/')
  ->checkResponseElement('ul.ull_flow_edit_header_list > li + li', '/Last action:[\s]+Sent[\s]+by[\s]+Test User/')
  ->checkResponseElement('ul.ull_flow_edit_header_list > li + li + li', '/Next one:[\s]+Helpdesk \(Group\)[\s]+\(Step[\s]+Helpdesk dispatcher \(Trouble ticket tool\)\)/') 
  ->checkResponseElement('#ull_flow_memories ul > ul.ull_flow_memories_day > li', 2) // number of memory entries
  ->checkResponseElement('#ull_flow_memories ul > ul.ull_flow_memories_day > li', '/Sent[\s]+by[\s]+Test User/')
  ->checkResponseElement('#ull_flow_memories ul > ul.ull_flow_memories_day > li + li', '/Created[\s]+by[\s]+Test User/')
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
  ->get('ullFlow/index')
  ->loginAs('helpdesk_admin_user')
  ->click('Trouble ticket tool')
  ->click('All entries')
  ->click('Edit')  
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('doc', '5')
  ->checkResponseElement('#ull_flow_edit_header h1', 'Trouble ticket "Urgently use ullright"')
  ->checkResponseElement('ul.ull_flow_edit_header_list > li', '/Created by[\s]+Test User/')
  ->checkResponseElement('ul.ull_flow_edit_header_list > li + li', '/Last action:[\s]+Assigned to user[\s]+Helpdesk Admin User[\s]+by[\s]+Helpdesk User/')
  ->checkResponseElement('ul.ull_flow_edit_header_list > li + li + li', '/Next one:[\s]+Helpdesk Admin User[\s]+\(Step[\s]+Troubleshooter \(Trouble ticket tool\)\)/') 
  ->checkResponseElement('#ull_flow_memories ul > ul.ull_flow_memories_day > li', 3) // number of memory entries
  ->checkResponseElement('#ull_flow_memories ul > ul.ull_flow_memories_day > li', '/Assigned to user[\s]+Helpdesk Admin User[\s]+by[\s]+Helpdesk User/')  
  ->checkResponseElement('#ull_flow_memories ul > ul.ull_flow_memories_day > li + li', '/Sent[\s]+by[\s]+Test User/')
  ->checkResponseElement('#ull_flow_memories ul > ul.ull_flow_memories_day > li + li + li', '/Created[\s]+by[\s]+Test User/')
;

$b
  ->diag('click return')
  ->click('Return')
  ->isRedirected()
  ->followRedirect()
  ->click('Log out')  
;

$b
  ->diag('login as helpdesk_admin: check that entry has been updated properly')
  ->get('ullFlow/index')
  ->loginAs('helpdesk_user')
  ->click('Trouble ticket tool')
  ->click('All entries')
  ->click('Edit')  
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('doc', '5')
  ->checkResponseElement('#ull_flow_edit_header h1', 'Trouble ticket "Urgently use ullright"')
  ->checkResponseElement('ul.ull_flow_edit_header_list > li', '/Created by[\s]+Test User/')
  ->checkResponseElement('ul.ull_flow_edit_header_list > li + li', '/Last action:[\s]+Returned[\s]+by[\s]+Helpdesk Admin User/')
  ->checkResponseElement('ul.ull_flow_edit_header_list > li + li + li', '/Next one:[\s]+Helpdesk \(Group\)[\s]+\(Step[\s]+Helpdesk dispatcher \(Trouble ticket tool\)\)/') 
  ->checkResponseElement('#ull_flow_memories ul > ul.ull_flow_memories_day > li', 4) // number of memory entries
  ->checkResponseElement('#ull_flow_memories ul > ul.ull_flow_memories_day > li', '/Returned[\s]+by[\s]+Helpdesk Admin User/')  
  ->checkResponseElement('#ull_flow_memories ul > ul.ull_flow_memories_day > li + li', '/Assigned to user[\s]+Helpdesk Admin User[\s]+by[\s]+Helpdesk User/')  
  ->checkResponseElement('#ull_flow_memories ul > ul.ull_flow_memories_day > li + li + li', '/Sent[\s]+by[\s]+Test User/')
  ->checkResponseElement('#ull_flow_memories ul > ul.ull_flow_memories_day > li + li + li + li', '/Created[\s]+by[\s]+Test User/')
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
  ->checkResponseElement('table > tbody > tr', 2) // number of rows
  ->checkResponseElement('tbody > tr > td + td + td + td', 'AAA My second trouble ticket')
  ->checkResponseElement('tbody > tr + tr > td + td + td + td', 'My first trouble ticket')
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
  ->checkResponseElement('table > tbody > tr', 1) // number of rows
  ->checkResponseElement('tbody > tr > td + td + td + td', 'Urgently use ullright')  
;

$b
  ->diag('edit closed doc and check')
  ->click('Edit')  
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('doc', '5')
  ->checkResponseElement('#ull_flow_edit_header h1', 'Trouble ticket "Urgently use ullright"')
  ->checkResponseElement('ul.ull_flow_edit_header_list > li', '/Created by[\s]+Test User/')
  ->checkResponseElement('ul.ull_flow_edit_header_list > li + li', '/Last action:[\s]+Closed[\s]+by[\s]+Helpdesk User/')
  ->checkResponseElement('ul.ull_flow_edit_header_list > li + li + li', false) 
  ->checkResponseElement('#ull_flow_memories ul > ul.ull_flow_memories_day > li', 5) // number of memory entries
  ->checkResponseElement('#ull_flow_memories ul > ul.ull_flow_memories_day > li ', '/Closed[\s]+by[\s]+Helpdesk User/')
  ->checkResponseElement('#ull_flow_memories ul > ul.ull_flow_memories_day > li + li', '/Returned[\s]+by[\s]+Helpdesk Admin User/')  
  ->checkResponseElement('#ull_flow_memories ul > ul.ull_flow_memories_day > li + li + li', '/Assigned to user[\s]+Helpdesk Admin User[\s]+by[\s]+Helpdesk User/')  
  ->checkResponseElement('#ull_flow_memories ul > ul.ull_flow_memories_day > li + li + li + li', '/Sent[\s]+by[\s]+Test User/')
  ->checkResponseElement('#ull_flow_memories ul > ul.ull_flow_memories_day > li + li + li + li + li', '/Created[\s]+by[\s]+Test User/')
;  

