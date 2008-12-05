<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
  protected $columnsConfigMock = array(
    'my_subject' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => true),
        'label'               => 'My custom subject label',
        'metaWidget'          => 'ullMetaWidgetString',
        'access'              => 'w',
        'is_in_list'          => true,
        ),         
    'my_datetime' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'Date',
        'metaWidget'          => 'ullMetaWidgetDateTime',
        'access'              => 'w',
        'is_in_list'          => false,
        ),                  
    'my_email' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'Your email address',
        'metaWidget'          => 'ullMetaWidgetEmail',
        'access'              => 'w',
        'is_in_list'          => true,
        ),
    'column_priority' => array (
        'widgetOptions'       => array('ull_select' => 'priority'),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'Priority',
        'metaWidget'          => 'ullMetaWidgetUllSelect',
        'access'              => 'w',
        'is_in_list'          => false,
        'default_value'       => '6',
        ),        
    'column_tags' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'Tags',
        'metaWidget'          => 'ullMetaWidgetTaggable',
        'access'              => 'w',
        'is_in_list'          => false,
        ),                  
  );

  public function getColumnsConfigMock()
  {
    return $this->columnsConfigMock;
  }
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfContext::getInstance()->getUser()->setCulture('en'); // because it's set to 'xx' per default !?!
sfLoader::loadHelpers('I18N');

$t = new myTestCase(16, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$app = Doctrine::getTable('UllFlowApp')->find(1);
$docs = Doctrine::getTable('UllFlowDoc')->findByUllFlowAppId(1);

$t->begin('__construct()');

  $generator = new ullFlowGenerator();
  $t->isa_ok($generator, 'ullFlowGenerator', '__construct() returns the correct object without params');
  
  $generator = new ullFlowGenerator($app, 'w');
  $t->isa_ok($generator, 'ullFlowGenerator', '__construct() returns the correct object');

$t->begin('getTableConfig()');
  $tableConfig = $generator->getTableConfig();
  $t->isa_ok($tableConfig, 'UllFlowApp', 'tableConfig is the correct object');  
  $t->is($tableConfig->label, 'Trouble ticket tool', 'Label is correct'); 
  
$t->begin('getColumnConfig()');
  $columnsConfig = $generator->getColumnsConfig();
  $t->is(is_array($columnsConfig), true, 'columnsConfig is an array');
  $t->is(count($columnsConfig), 5, 'columnsConfig has the correct number of columns');
  
  // don't use foreach because it ignores the ordering of the fields  
  $mocks = $t->getColumnsConfigMock();
  while (list($key, $val) = each($columnsConfig))
  {
    $columnConfig = array($key => $val);
    
    list($key, $val) = each($mocks);
    $mock = array($key => $val);
    
    $t->is($columnConfig, $mock, 'columnConfig for column "' . key($columnConfig) . '" is correct');
  }

$t->begin('buildForm()');
  $generator->buildForm($docs);  
  
$t->begin('getForm() with calling buildForm() prior');  
  $form = $generator->getForm();
  $t->isa_ok($form, 'ullFlowForm', 'getForm() returns the correct object');
  
$t->begin('getForms()');
  $forms = $generator->getForms();
  $t->is(is_array($forms), true, 'getForms() returns an array');
  $t->is(count($forms), 2, 'getForms returns the correct number of forms');
  $t->isa_ok($forms[0], 'ullFlowForm', 'The first entry is the correct object');  
  $t->isa_ok($forms[1], 'ullFlowForm', 'The second entry is the correct object');  
  
