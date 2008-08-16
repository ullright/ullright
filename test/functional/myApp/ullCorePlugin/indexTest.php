<?php

$app = 'myApp';
include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new sfDoctrineTestBrowser(null, null, array('configuration' => $configuration));
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$b->setFixturesPath($path);
$b->resetDatabase();

// start page 
$b
	->get('/')
	->isStatusCode(200)
	->isRequestParameter('module', 'myModule')
	->isRequestParameter('action', 'index')
	->responseContains('Welcome, I hope you\'re ullright!')
//	->responseContains('!/error/')
//	->checkResponseElement('body', '!/error|Error|ERROR/')
;

//print $b->getResponse()->getContent();