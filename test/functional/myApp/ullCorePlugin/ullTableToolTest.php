<?php

$app = 'myApp';
include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new sfDoctrineTestBrowser(null, null, array('configuration' => $configuration));
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$b->setFixturesPath($path);
$b->resetDatabase();

$b
	->get('ullTableTool/list/table/User')
	->isRedirected()
	->followRedirect()
	->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'noaccess')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullUser')
  ->isRequestParameter('action', 'login')  
  ->isRequestParameter('option', 'noaccess')
	->post('/ullUser/login', array('username' => 'admin', 'password' => 'admin'))
  ->isRedirected()
  ->followRedirect()  
  ->isStatusCode(200)		
	->isRequestParameter('module', 'ullTableTool')
	->isRequestParameter('action', 'list')
	->isRequestParameter('table', 'ull_user')
	->responseContains('list')
;  
  
  
  
//	->responseContains('!/error/')
//	->checkResponseElement('body', '!/error|Error|ERROR/')

//print $b->getResponse()->getContent();