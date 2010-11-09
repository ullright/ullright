<?php

include dirname(__FILE__) . '/../../../../../test/bootstrap/unit.php';

class myTestCase extends lime_test
{
  public function getColumnConfig()
  {
    $columnConfig = new ullColumnConfiguration();
    $columnConfig->setMetaWidgetClassName('ullMetaWidgetInteger');
    $columnConfig->setAccess('r');
    
    return $columnConfig;
  }
}

$t = new myTestCase(6, new lime_output_color, $configuration);

$columnConfig = $t->getColumnConfig();

$form = new sfForm();

$t->diag('for read access:');
  $widget = new ullMetaWidgetInteger($columnConfig, $form);
  $t->isa_ok($widget, 'ullMetaWidgetInteger', '__construct() returns the correct object');
  $widget->addToFormAs('my_field');
  $t->isa_ok($form->getWidgetSchema()->offsetGet('my_field'), 'ullWidget', 'returns the correct widget for read access');
  $t->isa_ok($form->getValidatorSchema()->offsetGet('my_field'), 'sfValidatorPass', 'returns the correct validator for read access');

$t->diag('for write access:');
  $columnConfig->setAccess('w');
  $widget = new ullMetaWidgetInteger($columnConfig, $form);
  $t->isa_ok($widget, 'ullMetaWidgetInteger', '__construct() returns the correct object');
  $widget->addToFormAs('my_field');
  $t->isa_ok($form->getWidgetSchema()->offsetGet('my_field'), 'ullWidgetFormInput', 'returns the correct widget for write access');
  $t->isa_ok($form->getValidatorSchema()->offsetGet('my_field'), 'sfValidatorInteger', 'returns the correct validator for write access');