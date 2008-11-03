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
  ->checkResponseElement('table > thead > tr > th', 4) // number of columns
  ->checkResponseElement('thead > tr > th + th', 'My custom title label:')
  ->checkResponseElement('thead > tr > th + th + th ', 'Your email address:')
  ->checkResponseElement('thead > tr > th + th + th + th ', 'Assigned to:')
  
//  ->dumpDIe()
;

$b
  ->diag('list - content')
//  ->checkResponseElement('h3', 'Trouble ticket tool')
//  ->responseContains('TestTable for automated testing')
  ->checkResponseElement('tbody > tr > td + td', 'My first trouble ticket')
//  ->checkResponseElement('tbody > tr > td + td + td + td', 'Master Admin')
  ->checkResponseElement('tbody > tr > td + td + td + td', '1')
  ->checkResponseElement('tbody > tr + tr > td + td', 'My second trouble ticket')
//  ->checkResponseElement('tbody > tr + tr > td + td + td', 'Group: Helpdesk')
  ->checkResponseElement('tbody > tr + tr > td + td + td + td', '4')
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