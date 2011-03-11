<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$b = new sfDoctrineTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

// start page 
$b
	->get('/')
	->isRedirected()
	->followRedirect()
	->isStatusCode(200)
	->isRequestParameter('module', 'ullCms')
	->isRequestParameter('action', 'show')
	->isRequestParameter('slug', 'homepage')
	->responseContains('Log in')
  ->click('Deutsch')
  ->isRedirected()
  ->followRedirect()  
	->isStatusCode(200)
  ->isRequestParameter('module', 'ullCms')
  ->isRequestParameter('action', 'show')
  ->isRequestParameter('slug', 'homepage')
  ->responseContains('Anmelden')
  ->click('English')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullCms')
  ->isRequestParameter('action', 'show')
  ->isRequestParameter('slug', 'homepage')
  ->responseContains('Log in')
;