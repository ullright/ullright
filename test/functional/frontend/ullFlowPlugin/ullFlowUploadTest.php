<?php

$app = 'frontend';
include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();


$b
  ->diag('edit doc')
  ->get('ullAdmin/index')
  ->loginAsAdmin()
  ->get('ullFlow/edit/doc/1')
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
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'upload')
  ->isRequestParameter('doc', '1')  
  ->isRequestParameter('column', 'my_upload')
  ->checkResponseElement('table.ull_flow_upload > tbody > tr', 1)
  ->checkResponseElement('table.ull_flow_upload > tbody > tr > td > a', 'Icons.zip')  

;

$b
  ->diag('upload file')
  ->setField('fields[file]', sfConfig::get('sf_upload_dir').'/assets/image/test_image.png')
  ->click('Upload file')
;

$b
  ->diag('check uploaded file')
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'upload')
  ->isRequestParameter('doc', '1')  
  ->isRequestParameter('column', 'my_upload')
  ->checkResponseElement('table.ull_flow_upload > tbody > tr', 2)
  ->checkResponseElement('table.ull_flow_upload > tbody > tr > td > a', 'Icons.zip')
  ->checkResponseElement('table.ull_flow_upload > tbody > tr + tr > td > a', 'test_image.png')
;  

$b
  ->diag('save and close')
  ->click('Save and return to list')
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('doc', '1')
  ->responseContains('Icons.zip')
;

// cleanup upload files
sfToolkit::clearDirectory(sfConfig::get('sf_upload_dir') . '/ullFlow');