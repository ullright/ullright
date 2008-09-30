<?php

$app = 'myApp';
include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$b
  ->diag('login')
  ->get('ullAdmin/index')
  ->loginAsAdmin()  
  ->isStatusCode(200)   
  ->isRequestParameter('module', 'ullAdmin')
  ->isRequestParameter('action', 'index')
  ->responseContains('ullAdmin')
;
  
  
  
//	->responseContains('!/error/')
//	->checkResponseElement('body', '!/error|Error|ERROR/')

//print $b->getResponse()->getContent();