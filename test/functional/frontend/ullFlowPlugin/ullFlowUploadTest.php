<?php

$app = 'frontend';
include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();


$b
  ->diag('edit doc')
  ->get('ullFlow/edit/doc/1')
  ->loginAsAdmin()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('doc', '1')
  ->responseContains('Icons.zip')
;  

$b
  ->diag('manage files')
  ->click('Manage files')
  ->isRedirected()
  ->followRedirect()
  ->checkResponseElement('table.ull_flow_upload > tbody > tr', 1)
  ->checkResponseElement('table.ull_flow_upload > tbody > tr > td > a', 'Icons.zip')  
  // how to test upload?
;
  
$b
  ->diag('save and close')
  ->click('Save and close')
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('doc', '1')
  ->responseContains('Icons.zip')
;