<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();
$dgsList = $b->getDgsUllTimeList();

$b
  ->diag('ullTime Home')
  ->get('ullAdmin/index')
  ->loginAs('test_user')
  ->get('ullTime/index')
  ->isStatusCode(200)
  ->with('request')->begin()
    ->isParameter('module', 'ullTime')
    ->isParameter('action', 'index')
  ->end()
;  

$b
  ->diag('index: check rendering')
  ->with('response')->begin()
    ->checkElement('h3:contains("Act as user")', false)
    ->checkElement('h3:contains("By project")', false)
    ->checkElement('h3:contains("By user")', false)
  ->end()
;

$b
  ->diag('ullTime: check permissions')
  ->get('ullTime/reportProject/report/by_project')
  ->isStatusCode(200)
  ->with('response')->begin()
    ->contains('No Access')
  ->end()
  ->get('ullTime/reportProject/report/by_user')
  ->isStatusCode(200)
  ->with('response')->begin()
    ->contains('No Access')
  ->end()
  ->get('ullTime/list/username/admin/period/october-2009')
  ->isStatusCode(200)
  ->with('response')->begin()
    ->contains('No Access')
  ->end()
  ->get('ullTime/index')
  ->isStatusCode(200)
;  

$b
  ->diag('create: locking (only 3 days back)')
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

