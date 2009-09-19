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


$t->begin('auto-generate totals');
  $user = Doctrine::getTable('UllUser')->findOneByUsername('test_user');

  $report = new UllTimeReporting;
  $report->UllUser        = $user;
  $report->date           = '2009-09-18';
  $report->begin_work_at  = '06:32:11';
  $report->end_work_at    = '23:43:57';
  $report->begin_break1_at = '09:31:43';
  $report->end_break1_at  = '09:37:15';
  $report->begin_break2_at = '12:31:44';
  $report->end_break2_at  = '13:37:16';
  
  $report->save();
  
  $t->is($report->total_work_seconds, 61906, 'Generates the correct work total');
  $t->is($report->total_break_seconds, 4264, 'Generates the correct break total');
  
