<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends lime_test
{
  protected $columnConfig = array(
        'widgetOptions'       => array(),
        'widgetAttributes'    => array('maxlength' => 64),
        'validatorOptions'    => array('required' => false, 'max_length' => 64),
        'label'               => 'My string',
        'metaWidget'          => 'ullMetaWidgetLink',
        'access'              => 'r',
  );

  public function getColumnConfig()
  {
    return $this->columnConfig;
  }
}

$t = new myTestCase(6, new lime_output_color, $configuration);

$columnConfig = $t->getColumnConfig();

$form = new sfForm();

$t->diag('for read access:');
  $widget = new ullMetaWidgetLink($columnConfig, $form);
  $t->isa_ok($widget, 'ullMetaWidgetLink', '__construct() returns the correct object');
  $widget->addToFormAs('my_field');
  $t->isa_ok($form->getWidgetSchema()->offsetGet('my_field'), 'ullWidgetLink', 'returns the correct widget for read access');
  $t->isa_ok($form->getValidatorSchema()->offsetGet('my_field'), 'sfValidatorPass', 'returns the correct validator for read access');

$t->diag('for write access:');
  $columnConfig['access'] = 'w';
  $widget = new ullMetaWidgetLink($columnConfig, $form);
  $t->isa_ok($widget, 'ullMetaWidgetLink', '__construct() returns the correct object');
  $widget->addToFormAs('my_field');
  $t->isa_ok($form->getWidgetSchema()->offsetGet('my_field'), 'sfWidgetFormInput', 'returns the correct widget for write access');
  $t->isa_ok($form->getValidatorSchema()->offsetGet('my_field'), 'sfValidatorString', 'returns the correct validator for write access');
  