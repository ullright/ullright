<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
  protected $columnConfig = array(
        'widgetOptions'       => array(),
        'widgetAttributes'    => array('maxlength' => 64),
        'validatorOptions'    => array('required' => false, 'max_length' => 64),
        'label'               => 'My string',
        'metaWidget'          => 'ullMetaWidgetString',
        'access'              => 'r',
  ); 
  
  public function getColumnConfig()
  {
    return $this->columnConfig;
  }
  
  public function reset()
  {
    parent::reset();
  }
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(6, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);

//$form = new ullForm;

$columnConfig = $t->getColumnConfig();
  
$t->begin('for read access:');
  $widget = new ullMetaWidgetString($columnConfig);
  $t->isa_ok($widget, 'ullMetaWidgetString', '__construct() returns the correct object');
  $t->isa_ok($widget->getSfWidget(), 'ullWidget', 'returns the correct widget for read access');
  $t->isa_ok($widget->getSfValidator(), 'sfValidatorPass', 'returns the correct validator for read access');
  
$t->begin('for write access:');
  $columnConfig['access'] = 'w';
  $widget = new ullMetaWidgetString($columnConfig);
  $t->isa_ok($widget, 'ullMetaWidgetString', '__construct() returns the correct object');
  $t->isa_ok($widget->getSfWidget(), 'sfWidgetFormInput', 'returns the correct widget for read access');
  $t->isa_ok($widget->getSfValidator(), 'sfValidatorString', 'returns the correct validator for read access');

  //TODO: test available options?