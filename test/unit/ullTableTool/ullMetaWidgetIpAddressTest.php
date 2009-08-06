<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends lime_test
{
  public function getColumnConfig()
  {
    $columnConfig = new ullColumnConfiguration();
    $columnConfig->setMetaWidgetClassName('ullMetaWidgetIpAddress');
    $columnConfig->setAccess('r');
    
    return $columnConfig;
  }
}

$t = new myTestCase(7, new lime_output_color, $configuration);

$columnConfig = $t->getColumnConfig();

$form = new sfForm();

$t->diag('for read access:');
  $widget = new ullMetaWidgetIpAddress($columnConfig, $form);
  $t->isa_ok($widget, 'ullMetaWidgetIpAddress', '__construct() returns the correct object');
  $widget->addToFormAs('my_field');
  $t->isa_ok($form->getWidgetSchema()->offsetGet('my_field'), 'ullWidget', 'returns the correct widget for read access');
  $t->isa_ok($form->getValidatorSchema()->offsetGet('my_field'), 'sfValidatorPass', 'returns the correct validator for read access');

$t->diag('for write access:');
  $columnConfig->setAccess('w');
  $widget = new ullMetaWidgetIpAddress($columnConfig, $form);
  $t->isa_ok($widget, 'ullMetaWidgetIpAddress', '__construct() returns the correct object');
  $widget->addToFormAs('my_field');
  $t->isa_ok($form->getWidgetSchema()->offsetGet('my_field'), 'sfWidgetFormInput', 'returns the correct widget for write access');
  $t->isa_ok($form->getValidatorSchema()->offsetGet('my_field'), 'sfValidatorRegex', 'returns the correct validator for write access');
  $validatorOptions = $form->getValidatorSchema()->offsetGet('my_field')->getOptions();
  $t->is($validatorOptions['pattern'],
    '\b(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b',
    'regex pattern is valid');