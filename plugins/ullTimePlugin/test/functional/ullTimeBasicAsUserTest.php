<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();
$dgsListEdit = $b->getDgsUllTimeEditList();
$dgsList = $b->getDgsUllTimeList();


/* A very basic workflow as a normal user */

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
  ->diag('index: go to Timereporting for today')
  ->click('Timereporting for today')
  ->isStatusCode(200)
  ->with('request')->begin()
    ->isParameter('module', 'ullTime')
    ->isParameter('action', 'create')
  ->end()
;

$b
  ->diag('create: enter begin and end time, then click on save')
  ->setField('fields[begin_work_at]','9:00')
  ->setField('fields[end_work_at]','14:30')
  ->setField('fields[begin_break1_at]','12:00')
  ->setField('fields[end_break1_at]','12:30')
  ->click('Save and return to list')
  ->isRedirected()
  ->followRedirect()
;

$b
  ->diag('list: go to project efforts')
  ->with('request')->begin()
    ->isParameter('module', 'ullTime')
    ->isParameter('action', 'list')
  ->end()
  //we cannot simply click the first "project reporting", it might
  //be in the future (where we don't have edit rights) => select the
  //first project reporting link which is NOT in the future
  ->click('tr.ull_time_today > td:nth-child('
    . $dgsList->getColumnAlias('project_reporting') . ') > a')
  ->isStatusCode(200)
  ->with('request')->begin()
    ->isParameter('module', 'ullTime')
    ->isParameter('action', 'createProject')
  ->end()
;

$b
  ->diag('create: enter two new project efforts')
  ->setField('fields[ull_project_id]','1')
  ->setField('fields[duration_seconds]','1:45')
  ->click('Save and create another entry')
  ->isRedirected()
  ->followRedirect()
  ->setField('fields[ull_project_id]','2')
  ->setField('fields[duration_seconds]','1:10')
  ->click('Save and create another entry')
  ->isRedirected()
  ->followRedirect()
;  

$b
  ->diag('create: check times')
  ->with('request')->begin()
    ->isParameter('module', 'ullTime')
    ->isParameter('action', 'createProject')
  ->end()  
  ->with('response')->begin()
    ->checkElement($dgsListEdit->getFullRowSelector(), 3)
    ->checkElement($dgsListEdit->get(3, 'duration'), '2:55')
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
    ->checkElement('tr.ull_time_today > td:nth-child(' . $dgsList->getColumnAlias('time_total') . ')', '5:00')
    ->checkElement('tr.ull_time_today > td:nth-child(' . $dgsList->getColumnAlias('project_total') . ')', '2:55')
    ->checkElement('tr.ull_time_today > td:nth-child(' . $dgsList->getColumnAlias('delta') . ')', '2:05')
  ->end()
;
  
