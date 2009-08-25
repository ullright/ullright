<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends lime_test
{
  public function getColumnConfig()
  {
    $columnConfig = new ullColumnConfiguration();
    $columnConfig->setMetaWidgetClassName('ullMetaWidgetFloat');
    $columnConfig->setAccess('r');
    
    return $columnConfig;
  }
}

$t = new myTestCase(7, new lime_output_color, $configuration);

$columnConfig = $t->getColumnConfig();

$form = new sfForm();

$t->diag('for read access:');
  $widget = new ullMetaWidgetFloat($columnConfig, $form);
  $t->isa_ok($widget, 'ullMetaWidgetFloat', '__construct() returns the correct object');
  $widget->addToFormAs('my_field');
  $t->isa_ok($form->getWidgetSchema()->offsetGet('my_field'), 'ullWidgetFloatRead', 'returns the correct widget for read access');
  $t->isa_ok($form->getValidatorSchema()->offsetGet('my_field'), 'sfValidatorPass', 'returns the correct validator for read access');

$t->diag('for write access:');
  $columnConfig->setAccess('w');
  $widget = new ullMetaWidgetFloat($columnConfig, $form);
  $t->isa_ok($widget, 'ullMetaWidgetFloat', '__construct() returns the correct object');
  $widget->addToFormAs('my_field');
  $t->isa_ok($form->getWidgetSchema()->offsetGet('my_field'), 'ullWidgetFloatWrite', 'returns the correct widget for write access');
  $t->isa_ok($form->getValidatorSchema()->offsetGet('my_field'), 'ullValidatorNumberI18n', 'returns the correct validator for write access');
  $validatorOptions = $form->getValidatorSchema()->offsetGet('my_field')->getOptions();
  $t->is($validatorOptions['max_decimals'], 2, 'default max decimals is valid');