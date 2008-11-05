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
;  

$b
  ->diag('select app')
  ->click('Trouble ticket tool')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'index')
  ->responseContains('Application Trouble ticket tool')
  ->click('List')
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
  ->checkResponseElement('ul#breadcrumbs > li + li', 'Workflows')
  ->checkResponseElement('ul#breadcrumbs > li + li + li', 'Trouble ticket tool')
  ->checkResponseElement('ul#breadcrumbs > li + li + li + li', 'List')
;  
  
$b
  ->diag('list - column headers')
  ->checkResponseElement('table > thead > tr > th', 8) // number of columns
  ->checkResponseElement('thead > tr > th + th > a[href*="/ullFlow/list/app/trouble_ticket/order/id/order_dir/asc"]', 'ID')  
  ->checkResponseElement('thead > tr > th + th + th > a[href*="/ullFlow/list/app/trouble_ticket/order/ull_flow_app_id/order_dir/asc"]', 'Application')
  ->checkResponseElement('thead > tr > th + th + th + th > a[href*="/ullFlow/list/app/trouble_ticket/order/my_title/order_dir/asc"]', 'My custom title label')
  ->checkResponseElement('thead > tr > th + th + th + th + th > a', 'Your email address')
  ->checkResponseElement('thead > tr > th + th + th + th + th + th > a', 'Assigned to')
  ->checkResponseElement('thead > tr > th + th + th + th + th + th + th > a', 'Created by')
  ->checkResponseElement('thead > tr > th + th + th + th + th + th + th + th > a', 'Created at ↑')  
;

$b
  ->diag('list - content')
  
  ->checkResponseElement('tbody > tr > td + td + td + td', 'AAA My second trouble ticket')
  ->checkResponseElement('tbody > tr > td + td + td + td + td + td', 'Helpdesk (Group)')
  
  ->checkResponseElement('tbody > tr + tr > td + td + td', 'Trouble ticket tool')
  ->checkResponseElement('tbody > tr + tr > td + td + td + td', 'My first trouble ticket')
  ->checkResponseElement('tbody > tr + tr > td + td + td + td + td + td', 'Master Admin')
  ->checkResponseElement('tbody > tr + tr > td + td + td + td + td + td + td', 'Test User')   
;

$b
  ->diag('list - test order by title (a virtual field')
  ->click('My custom title label')
  ->isStatusCode(200)    
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('app', 'trouble_ticket')
  ->isRequestParameter('order', 'my_title')
  ->isRequestParameter('order_dir', 'asc')

  ->checkResponseElement('thead > tr > th + th + th + th > a[href*="/ullFlow/list/app/trouble_ticket/order/my_title/order_dir/desc"]', 'My custom title label ↓')
  ->checkResponseElement('thead > tr > th + th + th + th + th + th + th + th > a', 'Created at')

  ->checkResponseElement('tbody > tr > td + td + td + td', 'AAA My second trouble ticket')
  ->checkResponseElement('tbody > tr + tr > td + td + td + td', 'My first trouble ticket')  
;

$b
  ->diag('list - test order "desc" by title (a virtual field')
  ->click('My custom title label ↓')
  ->isStatusCode(200)    
  ->isRequestParameter('order', 'my_title')
  ->isRequestParameter('order_dir', 'desc')

  ->checkResponseElement('thead > tr > th + th + th + th > a[href*="/ullFlow/list/app/trouble_ticket/order/my_title/order_dir/asc"]', 'My custom title label ↑')

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
  ->click('Application')
  ->isStatusCode(200)    
  ->isRequestParameter('order', 'ull_flow_app_id')
  ->isRequestParameter('order_dir', 'asc')

  ->checkResponseElement('thead > tr > th + th + th > a[href*="/ullFlow/list/app/trouble_ticket/order/ull_flow_app_id/order_dir/desc"]', 'Application ↓')

  ->checkResponseElement('tbody > tr > td + td + td', 'Trouble ticket tool')
  
  ->checkResponseElement('tbody > tr > td + td + td', 'Trouble ticket tool')
  ->checkResponseElement('tbody > tr + tr > td + td + td + td + td + td + td + td', '2001-01-01 01:01:01')
;

$b
  ->diag('create with missing title and invalid email')
  ->get('ullFlow/create/app/trouble_ticket')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'create')
  ->isRequestParameter('app', 'trouble_ticket')
  ->checkResponseElement('table tr', 3) // number of displayed fields
  ->setField('fields[my_email]', 'foobar')
  ->click('save')
;

$b
  ->diag('check validation errors and create with correct values')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('app', 'trouble_ticket')
  ->checkResponseElement('tr > td + td + td > ul > li', 'Required.')
  ->checkResponseElement('tr + tr + tr > td + td + td > ul > li', 'Invalid.')
  ->setField('fields[my_title]', 'This is my shiny little title')
  ->setField('fields[my_date]', "2001-01-01 01:01:01")    
  ->setField('fields[my_email]', 'bender@ull.at')  
//  ->click('save')
//  
////  ->isRedirected()
////  ->isRequestParameter('module', 'ullTableTool')
////  ->isRequestParameter('action', 'edit')
////  ->followRedirect()
//
//  ->setField('fields[my_title]', 'This is my shiny little title')
//  ->setField('fields[my_date]', "2001-01-01 01:01:01")  
;