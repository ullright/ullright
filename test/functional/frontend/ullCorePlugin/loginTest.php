<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new sfDoctrineTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
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
  ->responseContains('Username')
  ->responseContains('Password')
// we can't use the following, because there is a link and a button with the same name
//	->setField('username', 'admin')
//	->setField('password', 'admin')
//  ->click('Log in')
  ->post('/ullUser/login', array('login' => array('username' => 'admin', 'password' => 'admin', 'login_request' => true)))
  ->isRedirected()
  ->followRedirect()  
	->isStatusCode(200)
  ->isRequestParameter('module', 'myModule')
  ->isRequestParameter('action', 'index')
  ->responseContains('Logged in as    ' . ull_link_entity_popup('admin', 1))
  ->click('Log out')  
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'myModule')
  ->isRequestParameter('action', 'index')
  ->responseContains('Log in')
;  
  
//login with invalid username  
$b
  ->get('/')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'myModule')
  ->isRequestParameter('action', 'index')
  ->click('Log in')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'login')
  ->responseContains('Username')
  ->responseContains('Password')
  ->post('/ullUser/login', array('login' => array('username' => 'foouser', 'password' => 'happy treefriends', 'login_request' => true)))
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'login')
  ->responseContains('Login failed. Please try again:')  
;  
  
