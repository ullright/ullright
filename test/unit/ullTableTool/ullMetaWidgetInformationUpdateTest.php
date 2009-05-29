<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
  public function getColumnConfig()
  {
    $columnConfig = new ullColumnConfiguration();
    $columnConfig->setMetaWidgetClassName('ullMetaWidgetInformationUpdate');
    $columnConfig->setAccess('r');
    
    return $columnConfig;
  }
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
//sfLoader::loadHelpers('I18N');

$t = new myTestCase(6, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);

$columnConfig = $t->getColumnConfig();

$form = new sfForm();

$t->begin('for read access:');
  $widget = new ullMetaWidgetInformationUpdate($columnConfig, $form);
  $t->isa_ok($widget, 'ullMetaWidgetInformationUpdate', '__construct() returns the correct object');
  $widget->addToFormAs('my_field');
  $t->isa_ok($form->getWidgetSchema()->offsetGet('my_field'), 'ullWidgetInformationUpdateRead', 'returns the correct widget for read access');
  $t->isa_ok($form->getValidatorSchema()->offsetGet('my_field'), 'sfValidatorPass', 'returns the correct validator for read access');

$t->diag('for write access:');
  $columnConfig->setAccess('w');
  $widget = new ullMetaWidgetInformationUpdate($columnConfig, $form);
  $t->isa_ok($widget, 'ullMetaWidgetInformationUpdate', '__construct() returns the correct object');
  $widget->addToFormAs('my_field');
  $t->isa_ok($form->getWidgetSchema()->offsetGet('my_field'), 'ullWidgetInformationUpdateWrite', 'returns the correct widget for write access');
  $t->isa_ok($form->getValidatorSchema()->offsetGet('my_field'), 'sfValidatorString', 'returns the correct validator for write access');
  