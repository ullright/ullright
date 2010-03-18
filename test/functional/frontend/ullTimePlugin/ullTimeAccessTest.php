<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$dgsList = $b->getDgsUllTimeList();

$b
  ->diag('ullTime Home')
  ->get('ullTime/index')
  ->loginAs('test_user')
  ->isStatusCode(200)
  ->with('request')->begin()
    ->isParameter('module', 'ullTime')
    ->isParameter('action', 'index')
  ->end()
;  

$b
  ->diag('index: check rendering of access depending elements')
  ->with('response')->begin()
    ->checkElement('h3:contains("Act as user")', false)
  ->end()
;

$b
  ->diag('try to entering as another user via modified URI')
  ->get('ullTime/list/username/admin/period/october-2009')
  ->isStatusCode(200)
  ->with('response')->begin()
    ->contains('No Access')
  ->end()
;  

$b
  ->diag('create: locking (only 3 days back)')
  ->get('ullTime/index')
  ->isStatusCode(200)  
  ->click('September 2009')
  ->with('request')->begin()
    ->isParameter('module', 'ullTime')
    ->isParameter('action', 'list')
  ->end()
  ->with('response')->begin()
    ->checkElement($dgsList->get(19, 'day'), 'Thursday, 09/17/2009')
  ->end()
  ->click($dgsList->get(19, 'time_reporting') . ' > a')  //09/17/2009
  ->isRedirected()
  ->followRedirect()
  ->with('request')->begin()
    ->isParameter('module', 'ullTime')
    ->isParameter('action', 'edit')
  ->end()
  ->with('response')->begin()
    ->contains('09/17/2009')
    ->contains('Read only access. Entries are locked after 30 days.')
    ->checkElement('input#fields_begin_work_at', false)
  ->end()  
;


$b
  ->diag('reports for test_user')
;

$dgsList = $b->getDgsUllTimeListReportProject();

// Generate projects effort for master admin

$newProjectReport = new UllProjectReporting();
$newProjectReport->UllUser = Doctrine::getTable('UllUser')->findOneByUserName('admin');
$newProjectReport->UllProject = Doctrine::getTable('UllProject')->findOneBySlug('introduce-ullright');
$newProjectReport->date = '2009-10-15';
$newProjectReport->duration_seconds = 3600;
$newProjectReport->save();

$newProjectReport = new UllProjectReporting();
$newProjectReport->UllUser = Doctrine::getTable('UllUser')->findOneByUserName('admin');
$newProjectReport->UllProject = Doctrine::getTable('UllProject')->findOneBySlug('meeting-room-furniture');
$newProjectReport->date = '2009-10-15';
$newProjectReport->duration_seconds = 1800;
$newProjectReport->save();


$b
  ->get('ullTime/index')
  ->click('My projects')
  ->isStatusCode(200)
  ->with('request')->begin()
    ->isParameter('module', 'ullTime')
    ->isParameter('action', 'reportProject')
    ->isParameter('report', 'by_project')
  ->end()
;

$b
  ->diag('report: we expect the two entries for test_user')
  ->with('response')->begin()
    ->checkElement($dgsList->getFullRowSelector(), 3) // 2 + sum row
    ->checkElement($dgsList->get(1, 'project') ,'Introduce ullright')
    ->checkElement($dgsList->get(1, 'duration') ,'2:30')
    ->checkElement($dgsList->get(2, 'project') ,'Meeting room furniture')
    ->checkElement($dgsList->get(2, 'duration') ,'1:30')
    ->checkElement($dgsList->get(3, 'duration') ,'4:00') //sum
  ->end()
;

$b
  ->diag('report: now we add test_user as project manager for "introduce_ullright" project')
;
  
$manager = new UllProjectManager;
$manager->UllProject = Doctrine::getTable('UllProject')->findOneBySlug('introduce-ullright');
$manager->UllUser = Doctrine::getTable('UllUser')->findOneByUserName('test_user');
$manager->save();  

$b
  ->get('ullTime/reportProject/report/by_project')
  ->with('response')->begin()
    ->checkElement($dgsList->getFullRowSelector(), 3) // 2 + sum row
    ->checkElement($dgsList->get(1, 'project') ,'Introduce ullright')
    ->checkElement($dgsList->get(1, 'duration') ,'3:30') // 2:30 for test_user, 1:00 for admin
    ->checkElement($dgsList->get(2, 'project') ,'Meeting room furniture')
    ->checkElement($dgsList->get(2, 'duration') ,'1:30')
    ->checkElement($dgsList->get(3, 'duration') ,'5:00') //sum
  ->end()
;

