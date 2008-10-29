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