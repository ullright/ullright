<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends lime_test
{
  protected $columnConfig = array(
        'widgetOptions'       => array(),
        'widgetAttributes'    => array('maxlength' => 64),
        'validatorOptions'    => array('required' => false, 'max_length' => 64),
        'label'               => 'My password',
        'metaWidget'          => 'ullMetaWidgetPassword',
        'access'              => 'r',
  );

  public function getColumnConfig()
  {
    return $this->columnConfig;
  }
}

$t = new myTestCase(6, new lime_output_color, $configuration);

$columnConfig = $t->getColumnConfig();

$t->diag('for read access:');
  $widget = new ullMetaWidgetPassword($columnConfig);
  $t->isa_ok($widget, 'ullMetaWidgetPassword', '__construct() returns the correct object');
  $t->isa_ok($widget->getSfWidget(), 'ullWidgetPassword', 'returns the correct widget for read access');
  $t->isa_ok($widget->getSfValidator(), 'sfValidatorPass', 'returns the correct validator for read access');

$t->diag('for write access:');
  $columnConfig['access'] = 'w';
  $widget = new ullMetaWidgetPassword($columnConfig);
  $t->isa_ok($widget, 'ullMetaWidgetPassword', '__construct() returns the correct object');
  $t->isa_ok($widget->getSfWidget(), 'sfWidgetFormInputPassword', 'returns the correct widget for write access');
  $t->isa_ok($widget->getSfValidator(), 'sfValidatorString', 'returns the correct validator for write access');

  //TODO: test available options?