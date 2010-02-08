<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();


$b
  ->diag('ullTime Home')
  ->get('ullAdmin/index')
  ->loginAsAdmin()
  ->get('ullTime/index')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullTime')
  ->isRequestParameter('action', 'index')
;  

$b
  ->diag('index: go to Timereporting for today')
  ->click('Timereporting for today')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullTime')
  ->isRequestParameter('action', 'create')
  ->responseContains('Time report')
;

$b
  ->diag('create: enter begin and end time, then click on save')
  ->setField('fields[begin_work_at]','9:00')
  ->setField('fields[end_work_at]','14:00')
  ->click('Save and return to list')
  ->isStatusCode(302)
  ->isRedirected()
  ->followRedirect()
  ->isRequestParameter('module', 'ullTime')
  ->isRequestParameter('action', 'list')
;

$b
  ->diag('list: check correct working time')
  ->checkResponseElement('td.ull_time_list_time_column > span.ull_widget_time', '5:00')
;

$b
  ->diag('index: go to Project timereporting for today')
  ->get('ullTime/index')
  ->click('Project timereporting for today')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullTime')
  ->isRequestParameter('action', 'createProject')
  ->responseContains('Project efforts')
;

$b
  ->diag('create: enter two new project efforts')
  ->setField('fields[ull_project_id]','1')
  ->setField('fields[duration_seconds]','1:45')
  ->click('Save and create another entry')
  ->isStatusCode(302)
  ->isRedirected()
  ->followRedirect()
  ->setField('fields[ull_project_id]','2')
  ->setField('fields[duration_seconds]','1:10')
  ->click('Save and create another entry')
  ->isStatusCode(302)
  ->isRedirected()
  ->followRedirect()
  ->isRequestParameter('module', 'ullTime')
  ->isRequestParameter('action', 'createProject')
;  

$b
  ->diag('list: check times')
  ->checkResponseElement('tr.list_table_sum > td > span.ull_widget_time', '2:55')
  ->checkResponseElement('table#ull_time_edit_list > tbody > tr + tr + tr + tr > td + td + td > span', '5:00')
  ->checkResponseElement('tr.list_table_diff > td > span.ull_widget_time', '2:05')
;
  
