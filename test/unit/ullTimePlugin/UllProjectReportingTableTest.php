<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(2, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);


$t->begin('findByDateAndUserId()');
  
  $user = Doctrine::getTable('UllUser')->findOneByUsername('test_user');

  $t->is(UllProjectReportingTable::findByDateAndUserId('2009-09-17', 666), false, 'returns false for an invalid userId');
  $t->is(UllProjectReportingTable::findByDateAndUserId('2009-09-17', $user->id)->getFirst()->comment, 'Server hardware setup', 'returns the correct row');
