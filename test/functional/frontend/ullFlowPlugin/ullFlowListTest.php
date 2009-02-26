<?php

$app = 'frontend';
include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$dgsList = $b->getDgsUllFlowListGeneric();
$dgsListTT = $b->getDgsUllFlowListTroubleTicket();
$dgsListTD = $b->getDgsUllFlowListTodo();


$b
  ->diag('ullFlow Home')
  ->get('ullAdmin/index')
  ->loginAsAdmin()
  ->get('ullFlow/index')
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
  ->checkResponseElement($dgsListTD->getFullRowSelector(), 1) // number of rows
  ->checkResponseElement($dgsListTD->get(1, 'subject'), 'AAA My second thing todo')
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
  ->checkResponseElement($dgsListTT->getFullHeaderColumnSelector(), 6) // number of columns
  ->checkResponseElement($dgsListTT->getHeader('id') . ' > a[href*="/ullFlow/list/app/trouble_ticket/order/id/order_dir/asc"]', 'ID')  
  ->checkResponseElement($dgsListTT->getHeader('subject') . ' > a[href*="/ullFlow/list/app/trouble_ticket/order/subject/order_dir/asc"]', 'Subject')
  ->checkResponseElement($dgsListTT->getHeader('priority') . ' > a', 'Priority')
  ->checkResponseElement($dgsListTT->getHeader('assigned_to') . ' > a', 'Assigned to')
  ->checkResponseElement($dgsListTT->getHeader('created_at') . ' > a', 'Created at ↑')  
;

$b
  ->diag('list - content')
  ->checkResponseElement($dgsListTT->get(1, 'subject') . ' > b > a[href*="/ullFlow/edit/app/trouble_ticket/doc/2"]', 'AAA My second trouble ticket')
  ->checkResponseElement($dgsListTT->get(1, 'assigned_to'), 'Helpdesk')
  ->checkResponseElement($dgsListTT->get(2, 'subject'), 'My first trouble ticket')
  ->checkResponseElement($dgsListTT->get(2, 'assigned_to'), 'Master Admin')
;

$b
  ->diag('list - test order by subject (a virtual field)')
  ->click('Subject')
  ->isStatusCode(200)    
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('app', 'trouble_ticket')
  ->isRequestParameter('order', 'subject')
  ->isRequestParameter('order_dir', 'asc')

  ->checkResponseElement($dgsListTT->getHeader('subject') . ' > a[href*="/ullFlow/list/app/trouble_ticket/order/subject/order_dir/desc"]', 'Subject ↓')
  ->checkResponseElement($dgsListTT->getHeader('created_at') . ' > a', 'Created at')

  ->checkResponseElement($dgsListTT->get(1, 'subject'), 'AAA My second trouble ticket')
  ->checkResponseElement($dgsListTT->get(2, 'subject'), 'My first trouble ticket')  
;

$b
  ->diag('list - test order "desc" by subject (a virtual field)')
  ->click('Subject ↓')
  ->isStatusCode(200)    
  ->isRequestParameter('order', 'subject')
  ->isRequestParameter('order_dir', 'desc')

  ->checkResponseElement($dgsListTT->getHeader('subject') . ' > a[href*="/ullFlow/list/app/trouble_ticket/order/subject/order_dir/asc"]', 'Subject ↑')

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
  ->diag('list: test filter search + status')
  ->setField('filter[status]', 'all')
  ->click('Search_16x16')
  ->followRedirect()  
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('filter[search]', 'first t')
  ->isRequestParameter('filter[status]', 'all')
  ->checkResponseElement($dgsList->getFullRowSelector(), 2) // number of rows
  ->checkResponseElement($dgsList->get(1, 'subject'), 'My first thing todo')
  ->checkResponseElement($dgsList->get(2, 'subject'), 'My first trouble ticket')  
;

$b
  ->diag('list: now select another status')
  ->setField('filter[status]', 'reject')
  ->click('Search_16x16')
  ->followRedirect()  
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('filter[search]', 'first t')
  ->isRequestParameter('filter[status]', 'reject')
  ->checkResponseElement($dgsList->getFullRowSelector(), false) // number of rows
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


