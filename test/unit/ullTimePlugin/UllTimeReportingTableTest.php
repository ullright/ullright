<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(1, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$user = Doctrine::getTable('UllUser')->findOneByUsername('test_user');

$t->begin('findTotalWorkSecondsByDateAndUserId()');
  $t->is(UllTimeReportingTable::findTotalWorkSecondsByDateAndUserId('2009-09-17', $user->id), 25800, 'returns the correct sum');
  