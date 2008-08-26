<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
  protected $columnsConfigReference = array(
    'id' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => true),
        'label'               => 'Id',
        'metaWidget'          => 'ullMetaWidgetInteger',
        'access'              => 'r',
        ),
    'my_string' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array('maxlength' => 64),
        'validatorOptions'    => array('required' => true, 'max_length' => 64),
        'label'               => 'My string',
        'metaWidget'          => 'ullMetaWidgetString',
        'access'              => 'r',
        ),   
    'my_text' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'My text',
        'metaWidget'          => 'ullMetaWidgetString',
        'access'              => 'r',
        ), 
    'my_timestamp' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'My timestamp',
        'metaWidget'          => 'ullMetaWidgetDateTime',
        'access'              => 'r',
        ),
    'my_boolean' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'My boolean',
        'metaWidget'          => 'ullMetaWidgetString',
        'access'              => 'r',
        ),
    'ull_user_id' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'Ull user',
        'metaWidget'          => 'ullMetaWidgetForeignKey',
        'access'              => 'r',
        'relation'            => array('model' => 'UllUser', 'foreign_id' => 'id'),
        ),   
  ); 

  public function reset()
  {
    parent::reset();
  }
  
  public function getColumnsConfigReference()
  {
    return $this->columnsConfigReference;
  }
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(17, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);

$tests = Doctrine::getTable('TestTable')->findAll();

$t->begin('__construct()');
  try
  {
    new ullTableTool();
    $t->fail('__construct() doesn\' throw an exception if no rows are given');
  }
  catch (Exception $e)
  {
    $t->pass('__construct() throws an exception if no rows are given');
  }

  $tableTool = new ullTableTool($tests);
  $t->isa_ok($tableTool, 'ullTableTool', '__construct() returns the correct object');

  $t->is($tableTool->getModelName(), 'TestTable', '__construct() sets the right model name');
  
  $t->is($tableTool->getDefaultAccess(), 'r', '__construct() sets the default access to "read"');
  
$t->begin('getColumnConfig()');
  $columnsConfig = $tableTool->getColumnsConfig();
  $t->is(is_array($columnsConfig), true, 'columnsConfig is an array');
  $t->is(count($columnsConfig), 6, 'columnsConfig has the correct number of columns');
  
  foreach ($columnsConfig as $columnName => $columnConfig)
  {
    $reference = $t->getColumnsConfigReference();
    $t->is($columnConfig, $reference[$columnName], 'columnConfig for column "' . $columnName . '" is correct');
  }
      
$t->begin('getForm()');
  $form = $tableTool->getForm();
  $t->isa_ok($form, 'ullForm', 'getForm() returns a UllForm object');
  
$t->begin('getForms()');
  $forms = $tableTool->getForms();
  $t->is(is_array($forms), true, 'getForms() returns an array');
  $t->is(count($forms), 2, 'getForms returns the correct number of forms');
  $t->isa_ok($forms[0], 'ullForm', 'The first entry is a UllForm object');  
  $t->isa_ok($forms[1], 'ullForm', 'The second entry is a UllForm object');
  
//TODO: test access/enablement of fields
  
   