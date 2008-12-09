<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends lime_test
{
  protected $columnConfig = array(
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'My checkbox',
        'metaWidget'          => 'ullMetaWidgetUpload',
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
  $widget = new ullMetaWidgetUpload($columnConfig);
  $t->isa_ok($widget, 'ullMetaWidgetUpload', '__construct() returns the correct object');
  $t->isa_ok($widget->getSfWidget(), 'ullWidgetUploadRead', 'returns the correct widget for read access');
  $t->isa_ok($widget->getSfValidator(), 'sfValidatorPass', 'returns the correct validator for read access');

$t->diag('for write access:');
  $columnConfig['access'] = 'w';
  $widget = new ullMetaWidgetUpload($columnConfig);
  $t->isa_ok($widget, 'ullMetaWidgetUpload', '__construct() returns the correct object');
  $t->isa_ok($widget->getSfWidget(), 'ullWidgetUpload', 'returns the correct widget for write access');
  $t->isa_ok($widget->getSfValidator(), 'sfValidatorString', 'returns the correct validator for write access');

