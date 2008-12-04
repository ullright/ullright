<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
  protected $columnConfig = array(
        'widgetOptions'       => array('ull_select' => 'ull_select_test', 'add_empty' => true),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'My select box',
        'metaWidget'          => 'ullMetaWidgetUllSelect',
        'access'              => 'r',
  ); 
  
  public function getColumnConfig()
  {
    return $this->columnConfig;
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
  $widget = new ullMetaWidgetUllSelect($columnConfig);
  $t->isa_ok($widget, 'ullMetaWidgetUllSelect', '__construct() returns the correct object');
  $t->isa_ok($widget->getSfWidget(), 'ullWidgetUllSelect', 'returns the correct widget for read access');
  $t->isa_ok($widget->getSfValidator(), 'sfValidatorPass', 'returns the correct validator for read access');
  
$t->diag('for write access:');
  $columnConfig['access'] = 'w';
  $widget = new ullMetaWidgetUllSelect($columnConfig);
  $t->isa_ok($widget, 'ullMetaWidgetUllSelect', '__construct() returns the correct object');
  $t->isa_ok($widget->getSfWidget(), 'sfWidgetFormDoctrineSelect', 'returns the correct widget for read access');
  $t->isa_ok($widget->getSfValidator(), 'sfValidatorDoctrineChoice', 'returns the correct validator for read access');    