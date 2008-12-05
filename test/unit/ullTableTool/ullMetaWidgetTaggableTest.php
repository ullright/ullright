<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
  protected $columnConfig = array(
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'My tags',
        'metaWidget'          => 'ullMetaWidgetTaggable',
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
  $widget = new ullMetaWidgetTaggable($columnConfig);
  $t->isa_ok($widget, 'ullMetaWidgetTaggable', '__construct() returns the correct object');
  $t->isa_ok($widget->getSfWidget(), 'ullWidget', 'returns the correct widget for read access');
  $t->isa_ok($widget->getSfValidator(), 'sfValidatorPass', 'returns the correct validator for read access');

$t->diag('for write access:');
  $columnConfig['access'] = 'w';
  $widget = new ullMetaWidgetTaggable($columnConfig);
  $t->isa_ok($widget, 'ullMetaWidgetTaggable', '__construct() returns the correct object');
  $t->isa_ok($widget->getSfWidget(), 'ullWidgetTaggable', 'returns the correct widget for write access');
  $t->isa_ok($widget->getSfValidator(), 'ullValidatorTaggable', 'returns the correct validator for write access');