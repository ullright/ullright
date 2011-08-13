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


$t->begin('findIdsByCourseId)');

  $t->is(UllCourseTariffTable::findIdsByCourseId(1), array(1, 2), 'Returns the correct tariff ids');
  $t->is(UllCourseTariffTable::findIdsByCourseId(2), array(3, 4), 'Returns the correct tariff ids');
  
  Doctrine::getTable('UllCourseTariffCourse')->findByUllCourseTariff(2)->delete();
  Doctrine::getTable('UllCourseBooking')->findAll()->delete();
  Doctrine::getTable('UllCourseTariff')->findOneById(2)->delete();
  
  $t->is(UllCourseTariffTable::findIdsByCourseId(1), array(1), 'Returns the correct tariff ids');