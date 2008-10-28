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

//$form = new ullTableToolForm;

$t->begin('__construct()');
  $test = Doctrine::getTable('TestTable')->find(1);
  $form = new ullTableToolForm($test);
  $t->isa_ok($form, 'ullTableToolForm', '__construct() returns the correct object');
  $t->is($form->getWidgetSchema()->getFormFormatterName(), 'ullTable', 'The form uses the "ullTable" formatter by default');
  
  sfContext::getInstance()->getRequest()->setParameter('action', 'list');
  $form = new ullTableToolForm($test);
  $t->is($form->getWidgetSchema()->getFormFormatterName(), 'ullList', 'The form uses the "ullList" formatter for list actions');

// why doesn't this work?
//$t->begin('addUllMetaWidget()');
//  die;
  $columnConfig = $t->getColumnConfig();

  $form = new ullTableToolForm($test);
  $widget = new ullMetaWidgetString($columnConfig);
  $form->addUllMetaWidget('test_field', $widget);
  $fields = $form->getWidgetSchema()->getFields();
  $t->isa_ok($fields['test_field'], 'ullWidget', 'added ullMetaWidgetString: read access: form now contains a ullWidget');
  
  $columnConfig['access'] = 'w';
  $form = new ullTableToolForm($test);
  $widget = new ullMetaWidgetString($columnConfig);
  $form->addUllMetaWidget('test_field', $widget);
  $fields = $form->getWidgetSchema()->getFields();
  $t->isa_ok($fields['test_field'], 'sfWidgetFormInput', 'added ullMetaWidgetString: write access: form now contains a sfWidgetFormInput');
  
  
 