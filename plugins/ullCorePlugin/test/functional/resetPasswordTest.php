<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$b = new sfDoctrineTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$b
  ->diag('Navigate to reset password form')
	->get('/')
  ->isRedirected()
  ->followRedirect()
	->isStatusCode(200)
  ->isRequestParameter('module', 'ullCms')
  ->isRequestParameter('action', 'show')
  ->isRequestParameter('slug', 'homepage')
	->click('Log in')
  ->isRedirected()
  ->followRedirect()	
	->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'login')
  ->responseContains('Username')
  ->responseContains('Password')
  ->click('I forgot my password')  
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'resetPassword')
  ->responseContains('Username')
  ->responseContains('Email address')
;

$b
  ->diag('invalid username or email adress')
	->setField('resetPassword[username]', 'foo')
	->setField('resetPassword[email]', 'foofake@bar.com')
  ->click('Send me a new password')  
  ->isRedirected()
  ->followRedirect()  
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'resetPassword')
  ->responseContains('Invalid username and email address') 
  ->setField('resetPassword[username]', 'test_user')
  ->setField('resetPassword[email]', 'foofake@bar.com')
  ->click('Send me a new password')  
  ->isRedirected()
  ->followRedirect()  
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'resetPassword')
  ->responseContains('Invalid username and email address') 
  ->setField('resetPassword[username]', 'test_user')
  ->setField('resetPassword[email]', 'test.user@example.com')
  ->click('Send me a new password')  
  ->isRedirected()
  ->followRedirect()  
  ->isRedirected()
  ->followRedirect()
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'login')
  ->responseContains('Your new password has been sent to your email address')
;  

$b
  ->diag('login try with old password')
  ->get('/')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullCms')
  ->isRequestParameter('action', 'show')
  ->isRequestParameter('slug', 'homepage')
  ->click('Log in')
  ->isRedirected()
  ->followRedirect()    
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'login')
  ->responseContains('Username')
  ->responseContains('Password')
  ->setField('login[username]', 'test_user')
  ->setField('login[password]', 'test')
  ->click('Log in', null, array('position' => 2))
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'login')
  ->responseContains('Login failed. Please try again:')  
;  
  
