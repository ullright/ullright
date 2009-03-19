<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends lime_test
{
  protected $columnConfig = array(
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'My datestamp',
        'metaWidget'          => 'ullMetaWidgetDate',
        'access'              => 'r',
  );

  public function getColumnConfig()
  {
    return $this->columnConfig;
  }
}

$t = new myTestCase(6, new lime_output_color, $configuration);

sfContext::createInstance($configuration);

$columnConfig = $t->getColumnConfig();

$form = new sfForm();

$t->diag('for read access:');
  $widget = new ullMetaWidgetDate($columnConfig, $form);
  $t->isa_ok($widget, 'ullMetaWidgetDate', '__construct() returns the correct object');
  $widget->addToFormAs('my_field');
  $t->isa_ok($form->getWidgetSchema()->offsetGet('my_field'), 'ullWidgetDateRead', 'returns the correct widget for read access');
  $t->isa_ok($form->getValidatorSchema()->offsetGet('my_field'), 'sfValidatorPass', 'returns the correct validator for read access');

$t->diag('for write access:');
  $columnConfig['access'] = 'w';
  $widget = new ullMetaWidgetDate($columnConfig, $form);
  $t->isa_ok($widget, 'ullMetaWidgetDate', '__construct() returns the correct object');
  $widget->addToFormAs('my_field');
  $t->isa_ok($form->getWidgetSchema()->offsetGet('my_field'), 'ullWidgetDateWrite', 'returns the correct widget for write access');
  $t->isa_ok($form->getValidatorSchema()->offsetGet('my_field'), 'sfValidatorDate', 'returns the correct validator for write access');