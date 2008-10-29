<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
  protected $columnsConfigMock = array(
    'my_title' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => true),
        'label'               => 'My custom title label',
        'metaWidget'          => 'ullMetaWidgetString',
        'access'              => 'w',
        'show_in_list'        => true,
        ),         
    'my_datetime' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'Date',
        'metaWidget'          => 'ullMetaWidgetDateTime',
        'access'              => 'w',
        'show_in_list'        => false,
        ),                  
    'my_email' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'Your email address',
        'metaWidget'          => 'ullMetaWidgetEmail',
        'access'              => 'w',
        'show_in_list'        => true,
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

$t = new myTestCase(14, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$app = Doctrine::getTable('UllFlowApp')->find(1);
$docs = Doctrine::getTable('UllFlowDoc')->findAll();

$t->begin('__construct()');
  
  try
  {
    new ullFlowGenerator(new UllFlowApp);
    $t->fail('__construct() doesn\'t throw an exception if a non-existent UllFlowApp is given');
  }
  catch (Exception $e)
  {
    $t->pass('__construct() throws an exception if a non-existent UllFlowApp is given');
  }  

  $tableTool = new ullFlowGenerator($app, 'w');
  $t->isa_ok($tableTool, 'ullFlowGenerator', '__construct() returns the correct object');

$t->begin('getTableConfig()');
  $tableConfig = $tableTool->getTableConfig();
  $t->isa_ok($tableConfig, 'UllFlowApp', 'tableConfig is the correct object');  
  $t->is($tableConfig->label, 'Trouble ticket tool', 'Label is correct'); 
  
$t->begin('getColumnConfig()');
  $columnsConfig = $tableTool->getColumnsConfig();
  $t->is(is_array($columnsConfig), true, 'columnsConfig is an array');
  $t->is(count($columnsConfig), 3, 'columnsConfig has the correct number of columns');
  
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
  $tableTool->buildForm($docs);  
  
$t->begin('getForm() with calling buildForm() prior');  
  $form = $tableTool->getForm();
  $t->isa_ok($form, 'ullFlowForm', 'getForm() returns the correct object');
  
$t->begin('getForms()');
  $forms = $tableTool->getForms();
  $t->is(is_array($forms), true, 'getForms() returns an array');
  $t->is(count($forms), 2, 'getForms returns the correct number of forms');
  $t->isa_ok($forms[0], 'ullFlowForm', 'The first entry is the correct object');  
  $t->isa_ok($forms[1], 'ullFlowForm', 'The second entry is the correct object');  
  
