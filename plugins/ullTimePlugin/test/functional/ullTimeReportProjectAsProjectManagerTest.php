<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$b = new ullTimeTestBrowser(null, null, array('configuration' => $configuration));
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
$manager1 = new UllProjectManager;
$manager1->UllUser = Doctrine::getTable('UllUser')->findOneByUsername('admin');
$manager1->UllProject = Doctrine::getTable('UllProject')->findOneBySlug('introduce-ullright');
$manager1->save();


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

$b->setFromDateTo();

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


$b->diag('*** Test UllProjectReporting::is_visible_only_for_project_managers');

$project = Doctrine::getTable('UllProject')->findOneBySlug('introduce-ullright');
$project->is_visible_only_for_project_manager = true;
$project->save();

$b
  ->diag('Our test_user can see the project in the project list because he is manager')
  ->get('ullTime/createProject')
  ->with('response')->begin()
    ->checkElement('select[id="fields_ull_project_id"] > option[value="' . 
      Doctrine::getTable('UllProject')->findOneBySlug('introduce-ullright')->id . 
      '"]', true)
  ->end()
;  

$manager->delete();

$b
  ->diag('Now delete test_user as project manager. He now should not ee the project in the project list')
  ->get('ullTime/createProject')
  ->with('response')->begin()
    ->checkElement('select[id="fields_ull_project_id"] > option[value="' . 
      Doctrine::getTable('UllProject')->findOneBySlug('introduce-ullright')->id . 
      '"]', false)
  ->end()
; 