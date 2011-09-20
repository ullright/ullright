<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
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
  ->click('I forgot my login details')  
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'resetPassword')
  ->responseContains('Email address')
;

$b
  ->diag('invalid username or email adress')
	->setField('resetPassword[email]', 'foofake@bar.com')
  ->click('Send me my login details')  
  ->isRedirected()
  ->followRedirect()  
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'resetPassword')
  ->responseContains('Sorry, the given email address could not be found. Please try again.') 
  ->setField('resetPassword[email]', 'test.user@example.com')
  ->click('Send me my login details')  
  ->isRedirected()
  ->followRedirect()  
  ->isRedirected()
  ->followRedirect()
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'login')
  ->responseContains('Your login details have been sent to your email address')
;  

  
