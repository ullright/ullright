<?php

$app = 'myApp';
include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new sfDoctrineTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

// wiki index 
$b
	->get('ullWiki/index')
	->isStatusCode(200)
	->isRequestParameter('module', 'ullWiki')
	->isRequestParameter('action', 'index')
	->responseContains('Wiki Home')
;	

// wiki create
//$b
//  ->click('Create')
//  ->isStatusCode(200)
//  ->isRequestParameter('module', 'ullWiki')
//  ->isRequestParameter('action', 'create')
//;

//TODO: continue ullWiki migration


//	->click('Log in')
//	->isStatusCode(200)
//  ->isRequestParameter('module', 'ullUser')
//  ->isRequestParameter('action', 'login')
//  ->responseContains('Username:')
//  ->responseContains('Password:')
//// we can't use the following, because there is a link and a button with the same name
////	->setField('username', 'admin')
////	->setField('password', 'admin')
////  ->click('Log in')
//  ->post('/ullUser/login', array('username' => 'admin', 'password' => 'admin'))
//  ->isRedirected()
//  ->followRedirect()  
//	->isStatusCode(200)
//  ->isRequestParameter('module', 'myModule')
//  ->isRequestParameter('action', 'index')
//  ->responseContains('Logged in as admin')
//  ->click('Log out')  
//  ->isRedirected()
//  ->followRedirect()
//  ->isStatusCode(200)
//  ->isRequestParameter('module', 'myModule')
//  ->isRequestParameter('action', 'index')
//  ->responseContains('Log in')
//;  
  
  
//	->responseContains('!/error/')
//	->checkResponseElement('body', '!/error|Error|ERROR/')

//print $b->getResponse()->getContent();