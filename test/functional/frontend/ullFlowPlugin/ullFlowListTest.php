<?php

$app = 'frontend';
include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$dgsList = $b->getDgsUllFlowListGeneric();
$dgsListTT = $b->getDgsUllFlowListTroubleTicket();


$b
  ->diag('ullFlow Home')
  ->get('ullFlow/index')
  ->loginAsAdmin()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'index')
  ->responseContains('Workflows Home')
  ->responseContains('Applications')
  ->responseContains('Trouble ticket tool')
  ->responseContains('Todo list')
  ->responseContains('Quick search')
  ->responseContains('All entries')
;  

$b
  ->diag('index: click on tag')
  ->click('ull_flow_tag2')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('filter[search]', 'ull_flow_tag2')
  ->isRequestParameter('app', '')
  ->checkResponseElement($dgsList->getFullRowSelector(), 1) // number of rows
  ->checkResponseElement($dgsList->get(1, 'subject'), 'My first trouble ticket')
;

$b
  ->diag('index: select app, then click on tag')
  ->get('ullFlow/index')
  ->click('Todo list')
  ->click('ull_flow_tag1')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('filter[search]', 'ull_flow_tag1')
  ->isRequestParameter('app', 'todo')
  ->checkResponseElement($dgsList->getFullRowSelector(), 1) // number of rows
  ->checkResponseElement($dgsList->get(1, 'subject'), 'AAA My second thing todo')
;

$b
  ->diag('index: select app')
  ->get('ullFlow/index')
  ->click('Trouble ticket tool')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'index')
  ->responseContains('Application Trouble ticket tool')
  ->responseContains('Quick search')  
  ->click('All entries')
;

$b
  ->diag('list')
  ->isStatusCode(200)    
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('app', 'trouble_ticket')
;

$b
  ->diag('list - breadcrumb')  
  ->checkResponseElement('ul#breadcrumbs > li + li', 'Workflow Home')
  ->checkResponseElement('ul#breadcrumbs > li + li + li', 'Trouble ticket tool')
  ->checkResponseElement('ul#breadcrumbs > li + li + li + li', 'Result list')
;  
  
$b
  ->diag('list - column headers')
  ->checkResponseElement($dgsList->getFullHeaderColumnSelector(), 9) // number of columns
  ->checkResponseElement($dgsList->getHeader('id') . ' > a[href*="/ullFlow/list/app/trouble_ticket/order/id/order_dir/asc"]', 'ID')  
  ->checkResponseElement($dgsList->getHeader('app') . ' > a[href*="/ullFlow/list/app/trouble_ticket/order/ull_flow_app_id/order_dir/asc"]', 'App')
  ->checkResponseElement($dgsList->getHeader('subject') . ' > a[href*="/ullFlow/list/app/trouble_ticket/order/my_subject/order_dir/asc"]', 'My custom subject label')
  //->checkResponseElement('thead > tr > th + th + th + th + th > a', 'Your email address')
  ->checkResponseElement($dgsList->getHeader('priority') . ' > a', 'Priority')
  ->checkResponseElement($dgsList->getHeader('assigned_to') . ' > a', 'Assigned to')
  ->checkResponseElement($dgsList->getHeader('status') . ' > a', 'Status')
  ->checkResponseElement($dgsList->getHeader('created_by') . ' > a', 'Created by')
  ->checkResponseElement($dgsList->getHeader('created_at') . ' > a', 'Created at ↑')  
;

$b
  ->diag('list - content')
  ->checkResponseElement($dgsListTT->get(1, 'subject'), 'AAA My second trouble ticket')
  ->checkResponseElement($dgsListTT->get(1, 'assigned_to'), 'Helpdesk (Group)')
  //app name is not there anymore
  //->checkResponseElement('tbody > tr + tr > td + td + td', '/Trouble ticket tool/')
  ->checkResponseElement($dgsListTT->get(2, 'subject'), 'My first trouble ticket')
  ->checkResponseElement($dgsListTT->get(2, 'assigned_to'), 'Master Admin')
  ->checkResponseElement($dgsListTT->get(2, 'created_by'), 'Test User')   
;

$b
  ->diag('list - test order by subject (a virtual field)')
  ->click('My custom subject label')
  ->isStatusCode(200)    
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('app', 'trouble_ticket')
  ->isRequestParameter('order', 'my_subject')
  ->isRequestParameter('order_dir', 'asc')

  ->checkResponseElement($dgsListTT->getHeader('subject') . ' > a[href*="/ullFlow/list/app/trouble_ticket/order/my_subject/order_dir/desc"]', 'My custom subject label ↓')
  ->checkResponseElement($dgsListTT->getHeader('created_at') . ' > a', 'Created at')

  ->checkResponseElement($dgsListTT->get(1, 'subject'), 'AAA My second trouble ticket')
  ->checkResponseElement($dgsListTT->get(2, 'subject'), 'My first trouble ticket')  
;

$b
  ->diag('list - test order "desc" by subject (a virtual field)')
  ->click('My custom subject label ↓')
  ->isStatusCode(200)    
  ->isRequestParameter('order', 'my_subject')
  ->isRequestParameter('order_dir', 'desc')

  ->checkResponseElement($dgsListTT->getHeader('subject') . ' > a[href*="/ullFlow/list/app/trouble_ticket/order/my_subject/order_dir/asc"]', 'My custom subject label ↑')

  ->checkResponseElement($dgsListTT->get(1, 'subject'), 'My first trouble ticket')
  ->checkResponseElement($dgsListTT->get(2, 'subject'), 'AAA My second trouble ticket')  
;

$b
  ->diag('list - test order by id')
  ->click('ID')
  ->isStatusCode(200)    
  ->isRequestParameter('order', 'id')
  ->isRequestParameter('order_dir', 'asc')

  ->checkResponseElement('thead > tr > th + th > a[href*="/ullFlow/list/app/trouble_ticket/order/id/order_dir/desc"]', 'ID ↓')

  ->checkResponseElement('tbody > tr > td + td', '1')
  ->checkResponseElement('tbody > tr + tr > td + td', '2')  
;

$b
  ->diag('list - test order by application (which is the same for both entries, so the result should be ordered by created_at DESC')
  ->click('App')
  ->isStatusCode(200)    
  ->isRequestParameter('order', 'ull_flow_app_id')
  ->isRequestParameter('order_dir', 'asc')

  ->checkResponseElement($dgsListTT->getHeader('app') . ' > a[href*="/ullFlow/list/app/trouble_ticket/order/ull_flow_app_id/order_dir/desc"]', 'App ↓')

  //->checkResponseElement('tbody > tr > td + td + td', '/Trouble ticket tool/')
  //->checkResponseElement('tbody > tr > td + td + td', '/Trouble ticket tool/')
  ->checkResponseElement($dgsListTT->get(2, 'created_at'), '2001-01-01 01:01:01')
;

$b
  ->diag('quick search')
  ->get('ullFlow/index')
  ->setField('filter[search]', 'first t')
  ->click('Search_16x16')
  ->followRedirect()
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('filter[search]', 'first t')  
  ->checkResponseElement('ul.list_action_buttons input[name="filter[search]"][value="first t"]', true)
  ->checkResponseElement($dgsList->getFullRowSelector(), 2) // number of rows
  ->checkResponseElement($dgsList->get(1, 'subject'), 'My first thing todo')
  ->checkResponseElement($dgsList->get(2, 'subject'), 'My first trouble ticket')
;

$b
  ->diag('list: named queries: to me')
  ->get('ullFlow/index')
  ->click('Entries created by me')
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('query', 'by_me')
  ->checkResponseElement($dgsList->get(1, 'subject'), 'AAA My second trouble ticket')
  ->checkResponseElement($dgsList->get(2, 'subject'), 'AAA My second thing todo')
;

$b
  ->diag('list: named queries: by me')
  ->get('ullFlow/index')
  ->click('Entries assigned to me')
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('query', 'to_me')
  ->checkResponseElement($dgsList->get(1, 'subject'), 'My first trouble ticket')
;