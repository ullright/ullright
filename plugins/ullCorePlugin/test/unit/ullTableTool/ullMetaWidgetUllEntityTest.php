<?php

include dirname(__FILE__) . '/../../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
  public function getColumnConfig()
  {
    $columnConfig = new ullColumnConfiguration();
    $columnConfig->setMetaWidgetClassName('ullMetaWidgetUllEntity');
    $columnConfig->setAccess('r');
    
    return $columnConfig;
  }
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(8, new lime_output_color, $configuration);
$t->setMode('yml_fixtures');
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);

$t->begin('for read access:');
  $columnConfig = $t->getColumnConfig();
  $form = new sfForm();
  $widget = new ullMetaWidgetUllEntity($columnConfig, $form);
  $t->isa_ok($widget, 'ullMetaWidgetUllEntity', '__construct() returns the correct object');
  $widget->addToFormAs('my_field');
  $t->isa_ok($form->getWidgetSchema()->offsetGet('my_field'), 'ullWidgetForeignKey', 'returns the correct widget for read access');
  $t->isa_ok($form->getValidatorSchema()->offsetGet('my_field'), 'sfValidatorPass', 'returns the correct validator for read access');

$t->diag('write access with default options');
  $columnConfig->setAccess('w');
  $form = new sfForm();
  $widget = new ullMetaWidgetUllEntity($columnConfig, $form);
  $t->isa_ok($widget, 'ullMetaWidgetUllEntity', '__construct() returns the correct object');
  $widget->addToFormAs('my_field');
  $t->isa_ok($form->getWidgetSchema()->offsetGet('my_field'), 'sfWidgetFormSelectWithOptionAttributes', 'returns the correct widget for write access');
  $t->isa_ok($form->getValidatorSchema()->offsetGet('my_field'), 'sfValidatorChoice', 'returns the correct validator for write access');
  $t->is($widget->getColumnConfig()->getOption('entity_classes'), array('UllUser', 'UllGroup'), 'returns the correct list of default entity classes');
  
$t->diag('write access with custom entity classes');
  $columnConfig->setOption('entity_classes', array('UllUser'));
  $form = new sfForm();
  $widget = new ullMetaWidgetUllEntity($columnConfig, $form);
  $t->is($widget->getColumnConfig()->getOption('entity_classes'), array('UllUser'), 'returns the correct list of custom entity classes');      
      