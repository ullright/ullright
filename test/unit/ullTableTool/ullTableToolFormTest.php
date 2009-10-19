<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
  public function getColumnConfig()
  {
    $columnConfig = new ullColumnConfiguration();
    $columnConfig->setMetaWidgetClassName('ullMetaWidgetString');
    $columnConfig->setAccess('r');
    
    return $columnConfig;
  }
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(3, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);

//$form = new ullTableToolForm;

$t->begin('__construct()');
  $test = Doctrine::getTable('TestTable')->find(1);
  $form = new ullTableToolForm($test, new ullColumnConfigCollection('TestTable'));
  $t->isa_ok($form, 'ullTableToolForm', '__construct() returns the correct object');

// why doesn't this work?
//$t->begin('addUllMetaWidget()');
//  die;
  $columnConfig = $t->getColumnConfig();

  $form = new ullTableToolForm($test, new ullColumnConfigCollection('TestTable'));
  $widget = new ullMetaWidgetString($columnConfig, $form);
  $widget->addToFormAs('test_field');
  $fields = $form->getWidgetSchema()->getFields();
  $t->isa_ok($fields['test_field'], 'ullWidget', 'added ullMetaWidgetString: read access: form now contains a ullWidget');
  
  $columnConfig->setAccess('w');
  $form = new ullTableToolForm($test, new ullColumnConfigCollection('TestTable'));
  $widget = new ullMetaWidgetString($columnConfig, $form);
  $widget->addToFormAs('test_field');
  $fields = $form->getWidgetSchema()->getFields();
  $t->isa_ok($fields['test_field'], 'sfWidgetFormInput', 'added ullMetaWidgetString: write access: form now contains a sfWidgetFormInput');
  
  
 