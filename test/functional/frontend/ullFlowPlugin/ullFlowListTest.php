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
  ->checkResponseElement('table > tbody > tr', 1) // number of rows
  ->checkResponseElement('tbody > tr > td + td + td + td', 'My first trouble ticket')
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
  ->checkResponseElement('table > tbody > tr', 1) // number of rows
  ->checkResponseElement('tbody > tr > td + td + td + td', 'AAA My second thing todo')
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
  ->checkResponseElement('table > thead > tr > th', 9) // number of columns
  ->checkResponseElement('thead > tr > th + th > a[href*="/ullFlow/list/app/trouble_ticket/order/id/order_dir/asc"]', 'ID')  
  ->checkResponseElement('thead > tr > th + th + th > a[href*="/ullFlow/list/app/trouble_ticket/order/ull_flow_app_id/order_dir/asc"]', 'App')
  ->checkResponseElement('thead > tr > th + th + th + th > a[href*="/ullFlow/list/app/trouble_ticket/order/my_subject/order_dir/asc"]', 'My custom subject label')
  //->checkResponseElement('thead > tr > th + th + th + th + th > a', 'Your email address')
  ->checkResponseElement('thead > tr > th + th + th + th + th > a', 'Priority')
  ->checkResponseElement('thead > tr > th + th + th + th + th + th > a', 'Assigned to')
  ->checkResponseElement('thead > tr > th + th + th + th + th + th + th > a', 'Status')
  ->checkResponseElement('thead > tr > th + th + th + th + th + th + th + th > a', 'Created by')
  ->checkResponseElement('thead > tr > th + th + th + th + th + th + th + th + th > a', 'Created at ↑')  
;

$b
  ->diag('list - content')
  ->checkResponseElement('tbody > tr > td + td + td + td', 'AAA My second trouble ticket')
  ->checkResponseElement('tbody > tr > td + td + td + td + td + td', 'Helpdesk (Group)')
  //app name is not there anymore
  //->checkResponseElement('tbody > tr + tr > td + td + td', '/Trouble ticket tool/')
  ->checkResponseElement('tbody > tr + tr > td + td + td + td', 'My first trouble ticket')
  ->checkResponseElement('tbody > tr + tr > td + td + td + td + td + td', 'Master Admin')
  ->checkResponseElement('tbody > tr + tr > td + td + td + td + td + td + td + td', 'Test User')   
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

  ->checkResponseElement('thead > tr > th + th + th + th > a[href*="/ullFlow/list/app/trouble_ticket/order/my_subject/order_dir/desc"]', 'My custom subject label ↓')
  ->checkResponseElement('thead > tr > th + th + th + th + th + th + th + th + th > a', 'Created at')

  ->checkResponseElement('tbody > tr > td + td + td + td', 'AAA My second trouble ticket')
  ->checkResponseElement('tbody > tr + tr > td + td + td + td', 'My first trouble ticket')  
;

$b
  ->diag('list - test order "desc" by subject (a virtual field)')
  ->click('My custom subject label ↓')
  ->isStatusCode(200)    
  ->isRequestParameter('order', 'my_subject')
  ->isRequestParameter('order_dir', 'desc')

  ->checkResponseElement('thead > tr > th + th + th + th > a[href*="/ullFlow/list/app/trouble_ticket/order/my_subject/order_dir/asc"]', 'My custom subject label ↑')

  ->checkResponseElement('tbody > tr > td + td + td + td', 'My first trouble ticket')
  ->checkResponseElement('tbody > tr + tr > td + td + td + td', 'AAA My second trouble ticket')  
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

  ->checkResponseElement('thead > tr > th + th + th > a[href*="/ullFlow/list/app/trouble_ticket/order/ull_flow_app_id/order_dir/desc"]', 'App ↓')

  //->checkResponseElement('tbody > tr > td + td + td', '/Trouble ticket tool/')
  //->checkResponseElement('tbody > tr > td + td + td', '/Trouble ticket tool/')
  ->checkResponseElement('tbody > tr + tr > td + td + td + td + td + td + td + td + td', '2001-01-01 01:01:01')
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
  ->checkResponseElement('table > tbody > tr', 2) // number of rows
  ->checkResponseElement('tbody > tr > td + td + td + td', 'My first thing todo')
  ->checkResponseElement('tbody > tr + tr > td + td + td + td', 'My first trouble ticket')
;

$b
  ->diag('list: named queries: to me')
  ->get('ullFlow/index')
  ->click('Entries created by me')
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('query', 'by_me')
  ->checkResponseElement('tbody > tr > td + td + td + td', 'AAA My second trouble ticket')
  ->checkResponseElement('tbody > tr + tr > td + td + td + td', 'AAA My second thing todo')
;

$b
  ->diag('list: named queries: by me')
  ->get('ullFlow/index')
  ->click('Entries assigned to me')
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('query', 'to_me')
  ->checkResponseElement('tbody > tr > td + td + td + td', 'My first trouble ticket')
;