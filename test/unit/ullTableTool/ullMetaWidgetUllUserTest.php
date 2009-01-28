<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
  protected $columnConfig = array(
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'My field',
        'metaWidget'          => 'ullMetaWidgetUllUser',
        'access'              => 'r',
  );

  public function getColumnConfig()
  {
    return $this->columnConfig;
  }
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(9, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);

$t->begin('for read access:');
  $columnConfig = $t->getColumnConfig();
  $form = new sfForm();
  $widget = new ullMetaWidgetUllUser($columnConfig, $form);
  $t->isa_ok($widget, 'ullMetaWidgetUllUser', '__construct() returns the correct object');
  $widget->addToFormAs('my_field');
  $t->isa_ok($form->getWidgetSchema()->offsetGet('my_field'), 'ullWidgetUllUserRead', 'returns the correct widget for read access');
  $t->isa_ok($form->getValidatorSchema()->offsetGet('my_field'), 'sfValidatorPass', 'returns the correct validator for read access');

$t->diag('write access');
  $columnConfig['access'] = 'w';
  $form = new sfForm();
  $widget = new ullMetaWidgetUllUser($columnConfig, $form);
  $t->isa_ok($widget, 'ullMetaWidgetUllUser', '__construct() returns the correct object');
  $widget->addToFormAs('my_field');
  $t->isa_ok($form->getWidgetSchema()->offsetGet('my_field'), 'ullWidgetUllUser', 'returns the correct widget for write access');
  $t->isa_ok($form->getValidatorSchema()->offsetGet('my_field'), 'sfValidatorDoctrineChoice', 'returns the correct validator for write access');
  $t->is(
      $form->getWidgetSchema()->offsetGet('my_field')->getChoices(),
      array(1 => 'Master Admin', 2 => 'Test User'),
      'returns the correct choices'
  );  
  
$t->diag('write access with given UllGroup display_name');
  $columnConfig = $t->getColumnConfig();
  $columnConfig['access'] = 'w';
  $columnConfig['widgetOptions'] = array('group' => 'TestGroup');
  $form = new sfForm();
  $widget = new ullMetaWidgetUllUser($columnConfig, $form);
  $widget->addToFormAs('my_field');
  $t->is(
      $form->getWidgetSchema()->offsetGet('my_field')->getChoices(),
      array(2 => 'Test User'),
      'returns the correct choices'
  );

$t->diag('write access: invalid UllGroup display_name');
  $columnConfig = $t->getColumnConfig();
  $columnConfig['access'] = 'w';
  $columnConfig['widgetOptions'] = array('group' => 'FooBar');
  $form = new sfForm();
  $widget = new ullMetaWidgetUllUser($columnConfig, $form);
  try
  {
    $widget->addToFormAs('my_field');
    $t->fail('addToFormAs() doesn\'t throw an exception for an invalid UllGroup display_name');
  }
  catch (Exception $e)
  {
    $t->pass('addToFormAs() throws an exception for an invalid UllGroup display_name');
  }
    