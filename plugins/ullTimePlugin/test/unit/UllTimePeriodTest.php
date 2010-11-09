<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(8, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);


$t->begin('getDateList()');
  $period = Doctrine::getTable('UllTimePeriod')->findOneBySlug('september-2009');
  $list = $period->getDateList();
  $t->is(is_array($list), true, 'returns an array');
  $t->is(count($list), 30, 'returns the correct number of days');
  $t->is($list['2009-09-01'], array('date' => '2009-09-01', 'weekend' => false, 'calendarWeek' => 36), 'returns the correct entry for the first day');
  $t->is($list['2009-09-05'], array('date' => '2009-09-05', 'weekend' => true, 'calendarWeek' => 36), 'returns the correct entry for the first day');
  $t->is($list['2009-09-06'], array('date' => '2009-09-06', 'weekend' => true, 'calendarWeek' => 36), 'returns the correct entry for the first day');
  $t->is($list['2009-09-16'], array('date' => '2009-09-16', 'weekend' => false, 'calendarWeek' => 38), 'returns the correct entry for the first day');
  
  //Daylight saving problem
  $period = new UllTimePeriod;
  $period->from_date = '2010-03-01';
  $period->to_date = '2010-03-31';
  $period->save();
  
  $list = $period->getDateList();
  $t->is(count($list), 31, 'returns the correct number of days for a daylight saving period');
  $t->is($list['2010-03-31'], array('date' => '2010-03-31', 'weekend' => false, 'calendarWeek' => 13), 'returns the correct entry for the last day of a daytime saving period');
  
  