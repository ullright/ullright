<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(3, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('findByDateAndUserId()');
  $user = Doctrine::getTable('UllUser')->findOneByUsername('test_user');

  $t->is(count(UllProjectReportingTable::findByDateAndUserId('2009-09-17', 666)), 0, 'returns no entry for an invalid userId');
  $t->is(UllProjectReportingTable::findByDateAndUserId('2009-09-17', $user->id)->getFirst()->comment, 'Server hardware setup', 'returns the correct row');

  
$t->diag('findSumByDateAndUserId()');
  $t->is(UllProjectReportingTable::findSumByDateAndUserId('2009-09-17', $user->id), 14400, 'returns the correct sum');
  