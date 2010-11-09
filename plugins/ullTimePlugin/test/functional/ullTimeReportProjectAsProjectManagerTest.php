<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();
$dgsList = $b->getDgsUllTimeListReportProject();

//Test-Imports: introduce-ullright: 5400 (1:30) + 3600 (1:00) = 9000 (2:30)
//              meeting-room-furniture: 5400 (1:30)

// Test to prove solution of Bug #1175 (http://www.ullright.org/ullFlow/edit/doc/1175)

// Appoint test_user as project manager for project "introduce-ullright"
$manager = new UllProjectManager;
$manager->UllUser = Doctrine::getTable('UllUser')->findOneByUsername('test_user');
$manager->UllProject = Doctrine::getTable('UllProject')->findOneBySlug('introduce-ullright');
$manager->save();

// Appoint a second project manager for project "introduce-ullright"
$manager = new UllProjectManager;
$manager->UllUser = Doctrine::getTable('UllUser')->findOneByUsername('admin');
$manager->UllProject = Doctrine::getTable('UllProject')->findOneBySlug('introduce-ullright');
$manager->save();

$b
  ->diag('ullTime Home')
  ->get('ullAdmin/index')
  ->loginAs('test_user')
  ->responseContains('test_user')
  ->get('ullTime/index')
  ->isStatusCode(200)
  ->with('request')->begin()
    ->isParameter('module', 'ullTime')
    ->isParameter('action', 'index')
  ->end()
;  

$b
  ->diag('index: go to Project reports')
  ->click('My projects')
  ->isStatusCode(200)
  ->with('request')->begin()
    ->isParameter('module', 'ullTime')
    ->isParameter('action', 'reportProject')
  ->end()
;

$b
  ->diag('list: check correct times')
  ->with('response')->begin()
    ->checkElement($dgsList->get(1, 'project') ,'Introduce ullright')
    ->checkElement($dgsList->get(1, 'duration') ,'2:30')
    ->checkElement($dgsList->get(2, 'project') ,'Meeting room furniture')
    ->checkElement($dgsList->get(2, 'duration') ,'1:30')
    ->checkElement($dgsList->get(3, 'duration') ,'4:00')   
  ->end()
;


