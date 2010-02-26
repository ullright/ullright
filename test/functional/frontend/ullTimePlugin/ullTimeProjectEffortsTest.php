<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();
$dgsListEdit = $b->getDgsUllTimeEditList();
$dgsListToday = $b->getDgsUllTimeListTimeForToday();

$newTimeReport = new UllTimeReporting();
$newTimeReport->UllUser = Doctrine::getTable('UllUser')->findOneByUserName('test_user');
$newTimeReport->date = date('Y-m-d',time());
$newTimeReport->begin_work_at = '09:00:00';
$newTimeReport->end_work_at = '17:00:00';
$newTimeReport->save();

$newProjectReport = new UllProjectReporting();
$newProjectReport->UllUser = Doctrine::getTable('UllUser')->findOneByUserName('test_user');
$newProjectReport->UllProject = Doctrine::getTable('UllProject')->findOneBySlug('introduce-ullright');
$newProjectReport->date = date('Y-m-d',time());
$newProjectReport->duration_seconds = 17100; //04:45
$newProjectReport->save();

$newProjectReport = new UllProjectReporting();
$newProjectReport->UllUser = Doctrine::getTable('UllUser')->findOneByUserName('test_user');
$newProjectReport->UllProject = Doctrine::getTable('UllProject')->findOneBySlug('meeting-room-furniture');
$newProjectReport->date = date('Y-m-d',time());
$newProjectReport->duration_seconds = 7200; //02:00
$newProjectReport->save();


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
  ->diag('index: go to Project timereporting for today')

  ->click('Project timereporting for today')
  ->isStatusCode(200)
  ->with('request')->begin()
    ->isParameter('module', 'ullTime')
    ->isParameter('action', 'createProject')
  ->end()
;

$b
  ->diag('create: check times')
  ->with('request')->begin()
    ->isParameter('module', 'ullTime')
    ->isParameter('action', 'createProject')
  ->end()  
  ->with('response')->begin()
    ->checkElement($dgsListEdit->getFullRowSelector(), 3)
    ->checkElement($dgsListEdit->get(3, 'duration'), '6:45')
    ->checkElement('p.ull_time_working_delta_time > span', '8:00')
    ->checkElement('p.ull_time_working_delta_time > span + span', '1:15')
    ->checkElement('input[id="fields_duration_seconds"][value="1:15"]', true)
  ->end()
;

$b
  ->diag('create: edit project effort')
  ->click($dgsListEdit->get(1, 'icon') . ' > a')
  ->with('response')->begin()
    ->checkElement('select[id="fields_ull_project_id"] > option[value="' . 
      Doctrine::getTable('UllProject')->findOneBySlug('introduce-ullright')->id . 
      '"][selected="selected"]', true)
    ->checkElement('input[id="fields_duration_seconds"][value="4:45"]', true)
  ->end()
  ->setField('fields[duration_seconds]','3:00')
  ->click('Save and create another entry')
  ->isRedirected()
  ->followRedirect()
  ->with('response')->begin()
    ->checkElement($dgsListEdit->getFullRowSelector(), 3)
    ->checkElement($dgsListEdit->get(3, 'duration'), '5:00')
    ->checkElement('p.ull_time_working_delta_time > span', '8:00')
    ->checkElement('p.ull_time_working_delta_time > span + span', '3:00')
    ->checkElement('input[id="fields_duration_seconds"][value="3:00"]', true)
  ->end()
;

$b
  ->diag('create: delete project effort')
  ->click($dgsListEdit->get(2, 'icon') . ' > a + a')
  ->isRedirected()
  ->followRedirect()
  ->with('response')->begin()
    ->checkElement($dgsListEdit->getFullRowSelector(), 2)
    ->checkElement($dgsListEdit->get(2, 'duration'), '3:00')
    ->checkElement('p.ull_time_working_delta_time > span', '8:00')
    ->checkElement('p.ull_time_working_delta_time > span + span', '5:00')
    ->checkElement('input[id="fields_duration_seconds"][value="5:00"]', true)
  ->end()
;
  
$b
  ->diag('list: check times')
  ->click('Cancel and return to list')
  ->isStatusCode(200)
  ->with('request')->begin()
    ->isParameter('module', 'ullTime')
    ->isParameter('action', 'list')
  ->end()
  ->with('response')->begin()
    ->checkElement($dgsListToday->get(1, 'time_total'), '8:00')
    ->checkElement($dgsListToday->get(1, 'project_total'), '3:00')
    ->checkElement($dgsListToday->get(1, 'delta'), '5:00')
  ->end()
;
  
