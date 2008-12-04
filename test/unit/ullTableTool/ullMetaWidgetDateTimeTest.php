<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends lime_test
{
  protected $columnConfig = array(
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'My timestamp',
        'metaWidget'          => 'ullMetaWidgetDateTime',
        'access'              => 'r',
  ); 
  
  public function getColumnConfig()
  {
    return $this->columnConfig;
  }
}

$t = new myTestCase(6, new lime_output_color, $configuration);

sfContext::createInstance($configuration);
sfContext::getInstance()->getUser()->setCulture('en');

$columnConfig = $t->getColumnConfig();
  
$t->diag('for read access:');
  $widget = new ullMetaWidgetDateTime($columnConfig);
  $t->isa_ok($widget, 'ullMetaWidgetDateTime', '__construct() returns the correct object');
  $t->isa_ok($widget->getSfWidget(), 'ullWidget', 'returns the correct widget for read access');
  $t->isa_ok($widget->getSfValidator(), 'sfValidatorPass', 'returns the correct validator for read access');
  
$t->diag('for write access:');
  $columnConfig['access'] = 'w';
  $widget = new ullMetaWidgetDateTime($columnConfig);
  $t->isa_ok($widget, 'ullMetaWidgetDateTime', '__construct() returns the correct object');
  $t->isa_ok($widget->getSfWidget(), 'sfWidgetFormInput', 'returns the correct widget for read access');
  $t->isa_ok($widget->getSfValidator(), 'sfValidatorDateTime', 'returns the correct validator for read access');    