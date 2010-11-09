<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(5, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);


$t->begin('auto-generate totals');
  $user = Doctrine::getTable('UllUser')->findOneByUsername('test_user');

  $report = new UllTimeReporting;
  $report->UllUser        = $user;
  $report->date           = '2009-09-18';
  $report->begin_work_at  = '08:30:00';
  $report->end_work_at    = '17:00:00';
  $report->begin_break1_at = '10:00:00';
  $report->end_break1_at  = '10:10:00';
  $report->begin_break2_at = '12:40:00';
  $report->end_break2_at  = '13:00:00';
  
  $report->save();
  
  $t->is($report->total_work_seconds, 28800, 'Generates the correct work total');
  $t->is($report->total_break_seconds, 1800, 'Generates the correct break total');

$t->diag('getBreakSum()');
  $report = new UllTimeReporting;
  $report->UllUser        = $user;
  $report->date           = '2009-09-18';
  $report->begin_work_at  = '08:30:00';
  $report->end_work_at    = '17:00:00';
  $report->begin_break1_at = '10:00:00';
  $report->end_break1_at  = '10:10:00';
  $report->begin_break2_at = '12:40:00';
  
  $report->save();
  $t->is($report->getBreakDuration(1), 600, 'Returns the correct break1 seconds');
  $t->is($report->getBreakDuration(2), 0, 'Returns zero for empty end time');
  $t->is($report->getBreakDuration(3), 0, 'Returns zero for empty begin and end time');
  
  
