<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{

}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(3, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);

//$form = new ullTableToolForm;

$t->begin('__construct()');
  $test = Doctrine::getTable('TestTable')->find(1);
  $form = new ullGeneratorForm($test, 'edit');
  $t->isa_ok($form, 'ullGeneratorForm', '__construct() returns the correct object');
  $t->is($form->getWidgetSchema()->getFormFormatterName(), 'ullTable', 'The form uses the "ullTable" formatter by default');
  
  $form = new ullGeneratorForm($test);
  $t->is($form->getWidgetSchema()->getFormFormatterName(), 'ullList', 'The form uses the "ullList" formatter for list actions');

 