<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';



$t = new lime_test(6, new lime_output_color, $configuration);

$form = new sfForm();
$columnConfig = new ullColumnConfiguration();

$t->diag('for read access:');
  $columnConfig->setAccess('r');
  $widget = new ullMetaWidgetPhoneNumber($columnConfig, $form);
  $t->isa_ok($widget, 'ullMetaWidgetPhoneNumber', '__construct() returns the correct object');
  $widget->addToFormAs('my_field');
  $t->isa_ok(
    $form->getWidgetSchema()->offsetGet('my_field'), 
    'ullWidgetPhoneNumberRead', 
    'Returns the correct widget for read access'
  );
  $t->isa_ok(
    $form->getValidatorSchema()->offsetGet('my_field'), 
    'sfValidatorPass', 
    'Returns the correct validator for read access'
  );

$t->diag('for write access:');
  $columnConfig->setAccess('w');
  $widget = new ullMetaWidgetPhoneNumber($columnConfig, $form);
  $t->isa_ok($widget, 'ullMetaWidgetPhoneNumber', '__construct() returns the correct object');
  $widget->addToFormAs('my_field');
  $t->isa_ok(
    $form->getWidgetSchema()->offsetGet('my_field'), 
    'sfWidgetFormInput', 
    'returns the correct widget for write access'
  );
  $t->isa_ok(
    $form->getValidatorSchema()->offsetGet('my_field'), 
    'ullValidatorPhoneNumber', 
    'returns the correct validator for write access'
  );
  