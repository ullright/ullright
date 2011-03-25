<?php

include dirname(__FILE__) . '/../../../../../test/bootstrap/unit.php';

class myTestCase extends lime_test
{
  public function getColumnConfig()
  {
    $columnConfig = new ullColumnConfiguration();
    $columnConfig->setMetaWidgetClassName('ullMetaWidgetForeignKey');
    $columnConfig->setAccess('r');
    
    return $columnConfig;
  }
}

$t = new myTestCase(6, new lime_output_color, $configuration);

$form = new sfForm();

$columnConfig = $t->getColumnConfig();

$t->diag('for read access:');
  $widget = new ullMetaWidgetForeignKey($columnConfig, $form);
  $t->isa_ok($widget, 'ullMetaWidgetForeignKey', '__construct() returns the correct object');
  $widget->addToFormAs('my_field');
  $t->isa_ok($form->getWidgetSchema()->offsetGet('my_field'), 'ullWidgetForeignKey', 'returns the correct widget for read access');
  $t->isa_ok($form->getValidatorSchema()->offsetGet('my_field'), 'sfValidatorPass', 'returns the correct validator for read access');

$t->diag('for write access:');
  $columnConfig->setAccess('w');
  $widget = new ullMetaWidgetForeignKey($columnConfig, $form);
  $t->isa_ok($widget, 'ullMetaWidgetForeignKey', '__construct() returns the correct object');
  $widget->addToFormAs('my_field');
  $t->isa_ok($form->getWidgetSchema()->offsetGet('my_field'), 'ullWidgetFormDoctrineChoice', 'returns the correct widget for write access');
  $t->isa_ok($form->getValidatorSchema()->offsetGet('my_field'), 'sfValidatorDoctrineChoice', 'returns the correct validator for write access');