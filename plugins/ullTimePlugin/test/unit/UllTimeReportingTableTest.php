<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(3, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('findTotalWorkSecondsByDateAndUserId()');
  $user = Doctrine::getTable('UllUser')->findOneByUsername('test_user');
  $t->is(UllTimeReportingTable::findTotalWorkSecondsByDateAndUserId('2009-09-17', $user->id), 20700, 'returns the correct sum');
  
$t->diag('findByDateAndUserId()');
  $t->is(UllTimeReportingTable::findByDateAndUserId('2009-09-17', 666), null, 'returns no entry for an invalid userId');
  $t->is(UllTimeReportingTable::findByDateAndUserId('2009-09-17', $user->id)->begin_work_at, '08:20:00', 'returns the correct row');