<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();
$dgsList = $b->getDgsUllTimeListReportProject();

//Test-Imports: introduce-ullright: 5400 (1:30) + 3600 (1:00) = 9000 (2:30)
//              meeting-room-furniture: 5400 (1:30)

//The entering of project timereporting is tested in other functional tests
$newProjectReport = new UllProjectReporting();
$newProjectReport->UllUser = Doctrine::getTable('UllUser')->findOneByUserName('test_user');
$newProjectReport->UllProject = Doctrine::getTable('UllProject')->findOneBySlug('introduce-ullright');
$newProjectReport->date = '2009-10-15';
$newProjectReport->duration_seconds = 24300; //06:45
$newProjectReport->save();

$newProjectReport = new UllProjectReporting();
$newProjectReport->UllUser = Doctrine::getTable('UllUser')->findOneByUserName('test_user');
$newProjectReport->UllProject = Doctrine::getTable('UllProject')->findOneBySlug('introduce-ullright');
$newProjectReport->date = '2009-10-16';
$newProjectReport->duration_seconds = 4200;  //1:10
$newProjectReport->save();

$newProjectReport = new UllProjectReporting();
$newProjectReport->UllUser = Doctrine::getTable('UllUser')->findOneByUserName('test_user');
$newProjectReport->UllProject = Doctrine::getTable('UllProject')->findOneBySlug('introduce-ullright');
$newProjectReport->date = '2009-10-17';
$newProjectReport->duration_seconds = 7200;  //2:00
$newProjectReport->save();

$newProjectReport = new UllProjectReporting();
$newProjectReport->UllUser = Doctrine::getTable('UllUser')->findOneByUserName('test_user');
$newProjectReport->UllProject = Doctrine::getTable('UllProject')->findOneBySlug('meeting-room-furniture');
$newProjectReport->date = '2009-10-15';
$newProjectReport->duration_seconds = 7200; //2:00
$newProjectReport->save();

$newProjectReport = new UllProjectReporting();
$newProjectReport->UllUser = Doctrine::getTable('UllUser')->findOneByUserName('test_user');
$newProjectReport->UllProject = Doctrine::getTable('UllProject')->findOneBySlug('meeting-room-furniture');
$newProjectReport->date = '2009-10-18';
$newProjectReport->duration_seconds = 19500;  //5:25
$newProjectReport->save();


$b
  ->diag('ullTime Home')
  ->get('ullAdmin/index')
  ->loginAsAdmin()
  ->get('ullTime/index')
  ->isStatusCode(200)
  ->with('request')->begin()
    ->isParameter('module', 'ullTime')
    ->isParameter('action', 'index')
  ->end()
;  

$b
  ->diag('index: go to Project reports')
  ->click('By project')
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
    ->checkElement($dgsList->get(1, 'duration') ,'12:25')   //9:55 + Testdaten (2:30)
    ->checkElement($dgsList->get(2, 'project') ,'Meeting room furniture')
    ->checkElement($dgsList->get(2, 'duration') ,'8:55')    //7:25 + Testdaten (1:30)
    ->checkElement($dgsList->get(3, 'duration') ,'21:20')   
  ->end()
;

$b
  ->get('ullTime/index')
  ->isStatusCode(200)
  ->with('request')->begin()
    ->isParameter('module', 'ullTime')
    ->isParameter('action', 'index')
  ->end()
;  

$b
  ->diag('index: go to Project reports')
  ->click('By user')
  ->isStatusCode(200)
  ->with('request')->begin()
    ->isParameter('module', 'ullTime')
    ->isParameter('action', 'reportProject')
  ->end()
;

$b
  ->diag('list: check correct times')
  ->with('response')->begin()
    ->checkElement($dgsList->get(1, 'project') ,'Test User')
    ->checkElement($dgsList->get(1, 'duration') ,'21:20')
  ->end()
;

$b
  ->diag('list: check correct times by projects')
  ->setField('filter[ull_project_id]','1')
  ->click('search_list')
  ->isRedirected()
  ->followRedirect()
  ->with('response')->begin()
    ->checkElement($dgsList->get(1, 'project') ,'Test User')
    ->checkElement($dgsList->get(1, 'duration') ,'12:25')   //9:55 + Testdaten (2:30)
  ->end()
  ->setField('filter[ull_project_id]','2')
  ->click('search_list')
  ->isRedirected()
  ->followRedirect()
  ->with('response')->begin()
    ->checkElement($dgsList->get(1, 'project') ,'Test User')
    ->checkElement($dgsList->get(1, 'duration') ,'8:55')    //7:25 + Testdaten (1:30)   
  ->end()
;


//TODO: test date range filter
