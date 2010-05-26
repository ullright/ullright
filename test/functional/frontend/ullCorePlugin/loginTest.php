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
  ->isRedirected()
  ->followRedirect()	
	->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'login')
  ->responseContains('Username')
  ->responseContains('Password')
	->setField('login[username]', 'admin')
	->setField('login[password]', 'admin')
  ->click('Log in', null, array('position' => 2))
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
  ->isRedirected()
  ->followRedirect()    
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'login')
  ->responseContains('Username')
  ->responseContains('Password')
  ->setField('login[username]', 'foouser')
  ->setField('login[password]', 'happy treefriends')
  ->click('Log in', null, array('position' => 2))
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'login')
  ->responseContains('Login failed. Please try again:')  
;  
  
