<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$b = new ullTimeTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();
$dgsByUser = $b->getDgsUllTimeReportProjectByUser();
$dgsDetails = $b->getDgsUllTimeReportProjectDetails();

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


// Detailed Report

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
  ->diag('go to Project reports')
  ->click('My projects')
  ->isStatusCode(200)
  ->with('request')->begin()
    ->isParameter('module', 'ullTime')
    ->isParameter('action', 'reportProject')
  ->end()
;

$b->setFromDateTo();

$b
  ->diag('click on project')
  ->click('Introduce ullright')
  ->isStatusCode(200)
  ->with('request')->begin()
    ->isParameter('module', 'ullTime')
    ->isParameter('action', 'reportProject')
    ->isParameter('report', 'by_user')
    ->isParameter('filter', array('from_date' => '2009-01-01', 'ull_project_id' => 1))
  ->end()
  
  ->with('response')->begin()
    ->checkElement($dgsByUser->get(1, 'user') ,'Test User')
    ->checkElement($dgsByUser->get(1, 'duration') ,'12:25')
  ->end()  
;

$b
  ->diag('click on user to load the details for the selected project and user')
  ->click('Test User')
  ->isStatusCode(200)
  ->with('request')->begin()
    ->isParameter('module', 'ullTime')
    ->isParameter('action', 'reportProject')
    ->isParameter('report', 'details')
    ->isParameter('filter', array('from_date' => '2009-01-01', 'ull_project_id' => 1, 'ull_user_id' => 2))
  ->end()
  ->with('response')->begin()
    ->checkElement($dgsDetails->getFullRowSelector(), 6)
    ->checkElement($dgsDetails->get(1, 'date') ,'09/17/2009')
    ->checkElement($dgsDetails->get(1, 'duration') ,'1:30')
    ->checkElement($dgsDetails->get(1, 'comment') ,'Server hardware setup')
  ->end()
;
