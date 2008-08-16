<?php

$app = 'myApp';
include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new sfDoctrineTestBrowser(null, null, array('configuration' => $configuration));
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/fixtures.yml';
$b->setFixturesPath($path);
$b->resetDatabase();

// successfull login 
$b
	->get('/')
	->isStatusCode(200)
	->isRequestParameter('module', 'myModule')
	->isRequestParameter('action', 'index')
	->click('Log in')
	->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'login')
  ->responseContains('Username:')
  ->responseContains('Password:')
// we can't use the following, because there is a link and a button with the same name
//	->setField('username', 'admin')
//	->setField('password', 'admin')
//  ->click('Log in')
  ->post('/ullUser/login', array('username' => 'admin', 'password' => 'admin'))
  ->isRedirected()
  ->followRedirect()  
	->isStatusCode(200)
  ->isRequestParameter('module', 'myModule')
  ->isRequestParameter('action', 'index')
  ->responseContains('Logged in as admin')
  ->click('Admin')  
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullAdmin')
  ->isRequestParameter('action', 'index')
  ->responseContains('UllAdmin Startpage')
;  
  
  
  
//	->responseContains('!/error/')
//	->checkResponseElement('body', '!/error|Error|ERROR/')

//print $b->getResponse()->getContent();