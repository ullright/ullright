<?php

$app = 'frontend';
include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();


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
  ->checkResponseElement('ul#breadcrumbs > li + li', 'Workflows')
  ->checkResponseElement('ul#breadcrumbs > li + li + li', 'List')
;  
  
$b
  ->diag('list - column headers')
  ->checkResponseElement('table > thead > tr > th', 7) // number of columns
  ->checkResponseElement('thead > tr > th + th > a[href*="/ullFlow/list/order/id/order_dir/asc"]', 'ID')  
  ->checkResponseElement('thead > tr > th + th + th > a[href*="/ullFlow/list/order/ull_flow_app_id/order_dir/asc"]', 'Application')
  ->checkResponseElement('thead > tr > th + th + th + th > a[href*="/ullFlow/list/order/title/order_dir/asc"]', 'Title')
  ->checkResponseElement('thead > tr > th + th + th + th + th > a', 'Assigned to')
  ->checkResponseElement('thead > tr > th + th + th + th + th + th > a', 'Created by')
  ->checkResponseElement('thead > tr > th + th + th + th + th + th + th > a', 'Created at ↑')  
;

$b
  ->diag('list - content')
  ->checkResponseElement('tbody > tr > td + td + td + td', 'AAA My second trouble ticket')
  ->checkResponseElement('tbody > tr + tr > td + td + td + td', 'AAA My second thing todo')
  ->checkResponseElement('tbody > tr + tr + tr > td + td + td + td', 'My first thing todo')
  ->checkResponseElement('tbody > tr + tr + tr + tr > td + td + td + td', 'My first trouble ticket')
  ->checkResponseElement('ul.ull_action', '!/Create/')
;

$b
  ->diag('list - order by application - the result should be ordered by app ASC, created_at DESC')
  ->click('Application')
  ->checkResponseElement('tbody > tr > td + td + td + td', 'AAA My second trouble ticket')
  ->checkResponseElement('tbody > tr + tr > td + td + td + td', 'My first trouble ticket')  
  ->checkResponseElement('tbody > tr + tr + tr > td + td + td + td', 'AAA My second thing todo')
  ->checkResponseElement('tbody > tr + tr + tr + tr > td + td + td + td', 'My first thing todo')
;