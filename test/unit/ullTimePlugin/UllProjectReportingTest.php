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


$t->begin('auto-generate week on update');
  $user = Doctrine::getTable('UllUser')->findOneByUsername('test_user');
  $project = Doctrine::getTable('UllProject')->findOneBySlug('introduce-ullright');

  $report = new UllProjectReporting;
  $report->UllUser = $user;
  $report->date = '2009-09-18';
  $report->UllProject = $project;
  $report->duration_seconds = 3600;
  $report->save();
  
  $t->is($report->week, '200938', 'Generates the correct week upon create');
  
  $report->date = '2009-09-21';
  $report->save();
  $t->is($report->week, '200939', 'Generates the correct week upon update');