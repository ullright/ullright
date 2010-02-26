<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends lime_test
{
  public function getColumnConfig()
  {
    $columnConfig = new ullColumnConfiguration();
    $columnConfig->setMetaWidgetClassName('ullMetaWidgetPassword');
    $columnConfig->setAccess('r');
    
    return $columnConfig;
  }
}

sfContext::createInstance($configuration);
sfLoader::loadHelpers('I18N');

$t = new myTestCase(8, new lime_output_color, $configuration);

$columnConfig = $t->getColumnConfig();

$form = new sfForm();

$t->diag('for read access:');
  $widget = new ullMetaWidgetPassword($columnConfig, $form);
  $t->isa_ok($widget, 'ullMetaWidgetPassword', '__construct() returns the correct object');
  $widget->addToFormAs('my_field');
  $t->isa_ok($form->getWidgetSchema()->offsetGet('my_field'), 'ullWidgetPassword', 'returns the correct widget for read access');
  $t->isa_ok($form->getValidatorSchema()->offsetGet('my_field'), 'sfValidatorPass', 'returns the correct validator for read access');

$t->diag('for write access:');
  $columnConfig->setAccess('w');
  $widget = new ullMetaWidgetPassword($columnConfig, $form);
  $t->isa_ok($widget, 'ullMetaWidgetPassword', '__construct() returns the correct object');
  $widget->addToFormAs('my_field');
  $t->isa_ok($form->getWidgetSchema()->offsetGet('my_field'), 'ullWidgetPasswordWrite', 'returns the correct widget for write access');
  $t->isa_ok($form->getValidatorSchema()->offsetGet('my_field'), 'ullValidatorPassword', 'returns the correct validator for write access');

  $t->isa_ok($form->getWidgetSchema()->offsetGet('my_field_confirmation'), 'ullWidgetPasswordWrite', 'returns the correct widget for write access');
  $t->isa_ok($form->getValidatorSchema()->offsetGet('my_field_confirmation'), 'ullValidatorPassword', 'returns the correct validator for write access');
