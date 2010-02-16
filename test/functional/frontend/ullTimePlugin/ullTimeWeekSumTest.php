<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();
$dgsList = $b->getDgsUllTimeList();
$dgsListEdit = $b->getDgsUllTimeEditList();


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
  ->diag('index: go to Timereporting for 2009-10-27')
  ->get('ullTime/create/username/admin/date/2009-10-27')
  ->isStatusCode(200)
  ->with('request')->begin()
    ->isParameter('module', 'ullTime')
    ->isParameter('action', 'create')
  ->end()
;

$b
  ->diag('create: enter begin and end time, then click on save')
  ->setField('fields[begin_work_at]','9:00')
  ->setField('fields[end_work_at]','14:00')
  ->click('Save and return to list')
  ->isRedirected()
  ->followRedirect()
  ->get('ullTime/list/period/october-2009/username/admin')
  ->with('request')->begin()
    ->isParameter('module', 'ullTime')
    ->isParameter('action', 'list')
  ->end()
;

$b
  ->diag('list: check correct working time')
  ->with('response')->begin()
    ->checkElement($dgsList->get(5, 'time_total'), '5:00')
  ->end()
;

$b
  ->diag('index: go to Project timereporting for 2009-10-27')
  ->get('ullTime/createProject/username/admin/date/2009-10-27')
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
  ->with('request')->begin()
    ->isParameter('module', 'ullTime')
    ->isParameter('action', 'createProject')
  ->end()
;  

$b
  ->diag('list: check correct project time')
  ->get('ullTime/list/period/october-2009/username/admin')
  ->with('request')->begin()
    ->isParameter('module', 'ullTime')
    ->isParameter('action', 'list')
  ->end()
  ->with('response')->begin()
    ->checkElement($dgsList->get(5, 'project_total'), '2:55')
  ->end()
;
  
$b
  ->diag('index: go to Timereporting for 2009-10-28')
  ->get('ullTime/create/username/admin/date/2009-10-28')
  ->isStatusCode(200)
  ->with('request')->begin()
    ->isParameter('module', 'ullTime')
    ->isParameter('action', 'create')
  ->end()
;

$b
  ->diag('create: enter begin and end time, then click on save')
  ->setField('fields[begin_work_at]','8:45')
  ->setField('fields[end_work_at]','14:15')
  ->click('Save and return to list')
  ->isRedirected()
  ->followRedirect()
  ->get('ullTime/list/period/october-2009/username/admin')
  ->with('request')->begin()
    ->isParameter('module', 'ullTime')
    ->isParameter('action', 'list')
  ->end()
;

$b
  ->diag('list: check correct working time')
  ->with('response')->begin()
    ->checkElement($dgsList->get(4, 'time_total'), '5:30')
  ->end()
;

$b
  ->diag('index: go to Project timereporting for 2009-10-28')
  ->get('ullTime/createProject/username/admin/date/2009-10-28')
  ->isStatusCode(200)
  ->with('request')->begin()
    ->isParameter('module', 'ullTime')
    ->isParameter('action', 'createProject')
  ->end()
;

$b
  ->diag('create: enter two new project efforts')
  ->setField('fields[ull_project_id]','1')
  ->setField('fields[duration_seconds]','1:25')
  ->click('Save and create another entry')
  ->isRedirected()
  ->followRedirect()
  ->setField('fields[ull_project_id]','2')
  ->setField('fields[duration_seconds]','1:00')
  ->click('Save and create another entry')
  ->isRedirected()
  ->followRedirect()
  ->with('request')->begin()
    ->isParameter('module', 'ullTime')
    ->isParameter('action', 'createProject')
  ->end()
;  

$b
  ->diag('list: check correct project time')
  ->get('ullTime/list/period/october-2009/username/admin')
  ->with('request')->begin()
    ->isParameter('module', 'ullTime')
    ->isParameter('action', 'list')
  ->end()
  ->with('response')->begin()
    ->checkElement($dgsList->get(4, 'project_total'), '2:25')
  ->end()
;  

$b
  ->diag('list: check sum times')
  ->get('ullTime/list/period/october-2009/username/admin')
  ->with('request')->begin()
    ->isParameter('module', 'ullTime')
    ->isParameter('action', 'list')
  ->end()
  ->with('response')->begin()
    ->checkElement($dgsList->get(7, 'time_total') . ' > span', '10:30')
    ->checkElement($dgsList->get(7, 'project_total') . '> span', '5:20')
  ->end()
;  
