<?php

$app = 'frontend';
include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$dgsList = $b->getDgsUllFlowListGeneric();
//$dgsListTT = $b->getDgsUllFlowListTroubleTicket();

$b
  ->diag('ullFlow Home')
  ->get('ullFlow/index')
  ->loginAsAdmin()
;

$b
  ->diag('multi app list')
  ->click('All entries')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('app', false)
;

$b
  ->diag('list - breadcrumb')  
  ->checkResponseElement('ul#breadcrumbs > li + li', 'Workflow Home')
  ->checkResponseElement('ul#breadcrumbs > li + li + li', 'Result list')
;  
  
$b
  ->diag('list - column headers')
  ->checkResponseElement($dgsList->getFullHeaderColumnSelector(), 9) // number of columns
  ->checkResponseElement($dgsList->getHeader('id') . ' > a[href*="/ullFlow/list/order/id/order_dir/asc"]', 'ID')  
  ->checkResponseElement($dgsList->getHeader('app') . ' > a[href*="/ullFlow/list/order/ull_flow_app_id/order_dir/asc"]', 'App')
  ->checkResponseElement($dgsList->getHeader('subject') . ' > a[href*="/ullFlow/list/order/subject/order_dir/asc"]', 'Subject')
  ->checkResponseElement($dgsList->getHeader('assigned_to') . ' > a', 'Assigned to')
  ->checkResponseElement($dgsList->getHeader('created_by') . ' > a', 'Created by')
  ->checkResponseElement($dgsList->getHeader('created_at') . ' > a', 'Created at ↑')  
;

$b
  ->diag('list - content')
  ->checkResponseElement($dgsList->get(1, 'subject'), 'AAA My second trouble ticket')
  ->checkResponseElement($dgsList->get(2, 'subject'), 'AAA My second thing todo')
  ->checkResponseElement($dgsList->get(3, 'subject'), 'My first thing todo')
  ->checkResponseElement($dgsList->get(4, 'subject'), 'My first trouble ticket')
  ->checkResponseElement('ul.list_action_buttons input input[type="button"]', false)
;

$b
  ->diag('list - order by application - the result should be ordered by app ASC, created_at DESC')
  ->click('App')
  ->checkResponseElement($dgsList->get(1, 'subject'), 'AAA My second trouble ticket')
  ->checkResponseElement($dgsList->get(2, 'subject'), 'My first trouble ticket')  
  ->checkResponseElement($dgsList->get(3, 'subject'), 'AAA My second thing todo')
  ->checkResponseElement($dgsList->get(4, 'subject'), 'My first thing todo')
;