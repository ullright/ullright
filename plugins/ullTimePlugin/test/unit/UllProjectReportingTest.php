<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

sfContext::getInstance()->getConfiguration()->loadHelpers('Url');

$t = new myTestCase(5, new lime_output_color, $configuration);
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
  $report->comment = 'Foo bar';
  $report->save();
  
  $t->is($report->week, '200938', 'Generates the correct week upon create');
  
  $report->date = '2009-09-21';
  $report->save();
  $t->is($report->week, '200939', 'Generates the correct week upon update');
  
  
$t->diag('getComment()');
  $t->is($report['comment'], 'Foo bar', 'Returns the correct comment for a native project effort');
  
  $report = new UllProjectReporting;
  $report->UllUser = $user;
  $report->date = '2009-09-18';
  $report->UllProject = $project;
  $report->duration_seconds = 3600;
  $report->linked_model = 'UllFlowMemory';
  $report->linked_id = 1;
  
  $t->is($report['comment'], '<a href="' . url_for('ullFlow/edit?doc=1') . '">Trouble ticket "My first trouble ticket"</a>', 'Returns the correct comment for a linked UllFlowDoc entry');
  $t->is($report['raw_comment'], '', 'Returns empty string for a linked UllFlowDoc entry with option raw = true');

  