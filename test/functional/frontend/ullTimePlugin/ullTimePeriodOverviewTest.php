<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();
$dgsList = $b->getDgsUllTimeList();
$dgsListSum = $b->getDgsUllTimeListTableSum();

$newTimeReport = new UllTimeReporting();
$newTimeReport->UllUser = Doctrine::getTable('UllUser')->findOneByUserName('test_user');
$newTimeReport->date = '2009-10-27';
$newTimeReport->begin_work_at = '09:00:00';
$newTimeReport->end_work_at = '14:00:00';
$newTimeReport->save();

$newTimeReport = new UllTimeReporting();
$newTimeReport->UllUser = Doctrine::getTable('UllUser')->findOneByUserName('test_user');
$newTimeReport->date = '2009-10-28';
$newTimeReport->begin_work_at = '08:45:00';
$newTimeReport->end_work_at = '14:15:00';
$newTimeReport->save();

$newProjectReport = new UllProjectReporting();
$newProjectReport->UllUser = Doctrine::getTable('UllUser')->findOneByUserName('test_user');
$newProjectReport->UllProject = Doctrine::getTable('UllProject')->findOneBySlug('introduce-ullright');
$newProjectReport->date = '2009-10-27';
$newProjectReport->duration_seconds = 9900; //02:45
$newProjectReport->save();

$newProjectReport = new UllProjectReporting();
$newProjectReport->UllUser = Doctrine::getTable('UllUser')->findOneByUserName('test_user');
$newProjectReport->UllProject = Doctrine::getTable('UllProject')->findOneBySlug('meeting-room-furniture');
$newProjectReport->date = '2009-10-27';
$newProjectReport->duration_seconds = 8100; //02:15
$newProjectReport->save();

$newProjectReport = new UllProjectReporting();
$newProjectReport->UllUser = Doctrine::getTable('UllUser')->findOneByUserName('test_user');
$newProjectReport->UllProject = Doctrine::getTable('UllProject')->findOneBySlug('introduce-ullright');
$newProjectReport->date = '2009-10-28';
$newProjectReport->duration_seconds = 5100; //01:25
$newProjectReport->save();

$newProjectReport = new UllProjectReporting();
$newProjectReport->UllUser = Doctrine::getTable('UllUser')->findOneByUserName('test_user');
$newProjectReport->UllProject = Doctrine::getTable('UllProject')->findOneBySlug('meeting-room-furniture');
$newProjectReport->date = '2009-10-28';
$newProjectReport->duration_seconds = 4200; //01:10
$newProjectReport->save();

// TODO: rename to PeriodOverviewTest
// TODO: use manual fixtures

/*
 * For a simpler test we use fixed dates.
 * We act as admin to ignore the locking (Not allowed to edit older entries)
 */
$b
  ->diag('ullTime Home')
  ->get('ullAdmin/index')
  ->loginAs()
  ->get('ullTime/index')
  ->isStatusCode(200)
  ->with('request')->begin()
    ->isParameter('module', 'ullTime')
    ->isParameter('action', 'index')
  ->end()
;  

$b
  ->diag('index: go to overwiew october 2009')
  ->click('October 2009')
  ->isStatusCode(200)
  ->with('request')->begin()
    ->isParameter('module', 'ullTime')
    ->isParameter('action', 'list')
  ->end()
;

$b
  ->diag('list: check times')
  ->with('response')->begin()
    ->checkElement($dgsList->get(4, 'time_total') . ' > span', '5:30')
    ->checkElement($dgsList->get(5, 'time_total') . ' > span', '5:00')
    ->checkElement($dgsListSum->get(1, 'time_total') . ' > span', '10:30')
    ->checkElement($dgsList->get(4, 'project_total') . '> span', '2:35')
    ->checkElement($dgsList->get(5, 'project_total') . '> span', '5:00')
    ->checkElement($dgsListSum->get(1, 'project_total') . '> span', '7:35')
    ->checkElement($dgsList->get(4, 'delta') . '> span', '2:55')
    ->checkElement($dgsList->get(5, 'delta'), '')
    ->checkElement($dgsListSum->get(1, 'delta') . '> span', '2:55')
  ->end()
;  

$b
  ->diag('list: check total times')
  ->with('response')->begin()
    ->checkElement('tr.list_table_total_sum > td + td + td > span', '10:30')  //time_total
    ->checkElement('tr.list_table_total_sum > td + td + td + td + td > span', '7:35')  //project_total
    ->checkElement('tr.list_table_total_sum > td + td + td + td + td + td > span', '2:55')  //delta_total
  ->end()
;  
