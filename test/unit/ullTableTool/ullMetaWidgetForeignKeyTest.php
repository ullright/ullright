<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends lime_test
{
  protected $columnConfig = array(
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'Ull user',
        'metaWidget'          => 'ullMetaWidgetForeignKey',
        'access'              => 'r',
        'relation'            => array('model' => 'UllUser', 'foreign_id' => 'id'),
  );

  public function getColumnConfig()
  {
    return $this->columnConfig;
  }
}

$t = new myTestCase(6, new lime_output_color, $configuration);

$form = new sfForm();

$columnConfig = $t->getColumnConfig();

$t->diag('for read access:');
  $widget = new ullMetaWidgetForeignKey($columnConfig, $form);
  $t->isa_ok($widget, 'ullMetaWidgetForeignKey', '__construct() returns the correct object');
  $widget->addToFormAs('my_field');
  $t->isa_ok($form->getWidgetSchema()->offsetGet('my_field'), 'ullWidgetForeignKey', 'returns the correct widget for read access');
  $t->isa_ok($form->getValidatorSchema()->offsetGet('my_field'), 'sfValidatorPass', 'returns the correct validator for read access');

$t->diag('for write access:');
  $columnConfig['access'] = 'w';
  $widget = new ullMetaWidgetForeignKey($columnConfig, $form);
  $t->isa_ok($widget, 'ullMetaWidgetForeignKey', '__construct() returns the correct object');
  $widget->addToFormAs('my_field');
  $t->isa_ok($form->getWidgetSchema()->offsetGet('my_field'), 'sfWidgetFormDoctrineSelect', 'returns the correct widget for write access');
  $t->isa_ok($form->getValidatorSchema()->offsetGet('my_field'), 'sfValidatorDoctrineChoice', 'returns the correct validator for write access');