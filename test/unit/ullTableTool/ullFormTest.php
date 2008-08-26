<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
  protected $columnConfig = array(
        'widgetOptions'       => array(),
        'widgetAttributes'    => array('maxlength' => 64),
        'validatorOptions'    => array('required' => true, 'max_length' => 64),
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

$t = new myTestCase(5, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);

//$form = new ullForm;

$t->begin('__construct()');
  $form = new ullForm;
  $t->isa_ok($form, 'ullForm', '__construct() returns the correct object');
  $t->is($form->getWidgetSchema()->getFormFormatterName(), 'ullTable', 'The form uses the "ullTable" formatter by default');
  
  sfContext::getInstance()->getRequest()->setParameter('action', 'list');
  $form = new ullForm;
  $t->is($form->getWidgetSchema()->getFormFormatterName(), 'ullList', 'The form uses the "ullList" formatter for list actions');

$t->begin('__addUllMetaWidget()');
  $columnConfig = $t->getColumnConfig();

  $form = new ullForm;
  $widget = new ullMetaWidgetString($columnConfig);
  $form->addUllMetaWidget('test_field', $widget);
  $fields = $form->getWidgetSchema()->getFields();
  $t->isa_ok($fields['test_field'], 'ullWidget', 'added ullMetaWidgetString: read access: form now contains a ullWidget');
  
  $columnConfig['access'] = 'w';
  $form = new ullForm;
  $widget = new ullMetaWidgetString($columnConfig);
  $form->addUllMetaWidget('test_field', $widget);
  $fields = $form->getWidgetSchema()->getFields();
  $t->isa_ok($fields['test_field'], 'sfWidgetFormInput', 'added ullMetaWidgetString: write access: form now contains a sfWidgetFormInput');
  
  
 