<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$b
  ->diag('ullPhone Home')
  ->get('ullAdmin/index')
  ->loginAsAdmin()
  ->get('ullPhone/list')
  ->isStatusCode(200)
  ->with('request')->begin()
    ->isParameter('module', 'ullPhone')
    ->isParameter('action', 'list')
  ->end()
;

$b
  ->diag('ullUser: open UserPopup')
  ->click('User Test')
  ->isStatusCode(200)
  ->with('request')->begin()
    ->isParameter('module', 'ullUser')
    ->isParameter('action', 'show')
    ->isParameter('username', Doctrine::getTable('UllUser')->findOneByUserName('test_user')->id)
  ->end()
  ->with('response')->begin()
    ->contains('test_user')
    ->contains('Superior')
  ->end()
;