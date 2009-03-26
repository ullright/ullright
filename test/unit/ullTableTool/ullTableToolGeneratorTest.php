<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
  protected $columnsConfigMock = array(
    'id' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => true),
        'label'               => 'ID',
        'metaWidget'          => 'ullMetaWidgetInteger',
        'access'              => 'r',
        'is_in_list'        => true,
        'unique'            => false,
        ),                   
    'my_boolean' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'My boolean',
        'metaWidget'          => 'ullMetaWidgetCheckbox',
        'access'              => 'w',
        'is_in_list'        => true,
        'unique'            => false,
        ),
    'my_email' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array('maxlength' => 64),
        'validatorOptions'    => array('required' => false, 'max_length' => 64),
        'label'               => 'My email',
        'metaWidget'          => 'ullMetaWidgetEmail',
        'access'              => 'w',
        'is_in_list'        => true,
        'unique'            => false,
        ),
    'my_select_box' => array (
        'widgetOptions'       => array('ull_select' => 'ull_select_test', 'add_empty' => true),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'My select box',
        'metaWidget'          => 'ullMetaWidgetUllSelect',
        'access'              => 'w',
        'is_in_list'        => true,
        'unique'            => false,
        ),                    
    'my_useless_column' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array('maxlength' => 64),
        'validatorOptions'    => array('required' => false, 'max_length' => 64),
        'label'               => 'My useless column',
        'metaWidget'          => 'ullMetaWidgetString',
        'access'              => null,
        'is_in_list'        => true,
        'unique'            => false,
        ),        
    'ull_user_id' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'Ull user',
        'metaWidget'          => 'ullMetaWidgetForeignKey',
        'access'              => 'w',
        'is_in_list'        => true,
        'relation'            => array('model' => 'UllUser', 'foreign_id' => 'id'),
        'unique'            => false,
        ),  
//    'namespace' => array (
//        'widgetOptions'       => array(),
//        'widgetAttributes'    => array('maxlength' => 32),
//        'validatorOptions'    => array('required' => false, 'max_length' => 32),
//        'label'               => 'Namespace',
//        'metaWidget'          => 'ullMetaWidgetString',
//        'access'              => 'r',
//        ),   
    'my_string' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array('maxlength' => 64),
        'validatorOptions'    => array('required' => true, 'max_length' => 64),
        'label'               => 'My custom string label',
        'metaWidget'          => 'ullMetaWidgetString',
        'access'              => 'w',
        'is_in_list'        => true,
        'translation'         => true,
        'unique'            => false,
        ),   
    'my_text' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'My text',
        'metaWidget'          => 'ullMetaWidgetTextarea',
        'access'              => 'w',
        'is_in_list'        => true,
        'translation'         => true,
        'unique'            => false,
        ),   
    'creator_user_id' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'Created by',
        'metaWidget'          => 'ullMetaWidgetForeignKey',
        'access'              => 'r',
        'is_in_list'        => false,
        'relation'            => array('model' => 'UllUser', 'foreign_id' => 'id'),
        'unique'            => false,
        ),
    'created_at' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'Created at',
        'metaWidget'          => 'ullMetaWidgetDateTime',
        'access'              => 'r',
        'is_in_list'        => false,
        'unique'            => false,
        ),
    'updator_user_id' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'Updated by',
        'metaWidget'          => 'ullMetaWidgetForeignKey',
        'access'              => 'r',
        'is_in_list'        => false,
        'relation'            => array('model' => 'UllUser', 'foreign_id' => 'id'),
        'unique'            => false,
        ),                  
    'updated_at' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'Updated at',
        'metaWidget'          => 'ullMetaWidgetDateTime',
        'access'              => 'r',
        'is_in_list'        => false,
        'unique'            => false,
        ),                   
  ); 

//  public function reset()
//  {
//    parent::reset();
//  }
  
  public function getColumnsConfigMock()
  {
    return $this->columnsConfigMock;
  }
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfLoader::loadHelpers('I18N');

$t = new myTestCase(29, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);

$tests = Doctrine::getTable('TestTable')->findAll();

$t->begin('__construct()');

  try
  {
    new ullTableToolGenerator();
    $t->fail('__construct() doesn\'t throw an exception if no model is given');
  }
  catch (Exception $e)
  {
    $t->pass('__construct() throws an exception if no model is given');
  }

  try
  {
    new ullTableToolGenerator($tests);
    $t->fail('__construct() doesn\'t throw an exception if an invalid model is given');
  }
  catch (Exception $e)
  {
    $t->pass('__construct() throws an exception if an invalid model is given');
  }
  
  $tableTool = new ullTableToolGenerator('TestTable', 'w');
  $t->isa_ok($tableTool, 'ullTableToolGenerator', '__construct() returns the correct object');
  $t->is($tableTool->getModelName(), 'TestTable', '__construct() sets the right model name');
  
$t->begin('getTableConfig()');
  $tableConfig = $tableTool->getTableConfig();
  $t->isa_ok($tableConfig, 'UllTableConfig', 'tableConfig is a UllTableConfig object');  
  $t->is(is_string($tableConfig->getIdentifier()), true, 'Identifier is a string');
  $t->is($tableConfig->getIdentifier(), 'id', 'Identifier is correct');
  $t->is($tableConfig->label, 'TestTableLabel', 'Label is correct'); 

// we don't habe any composite primaray keys at the moment  
//$t->begin('getTableConfig() for a table with a multi-columns primary key');  
//  $tableTool2 = new ullTableToolGenerator('UllEntityGroup');
//  $tableConfig = $tableTool2->getTableConfig();
//  $t->isa_ok($tableConfig, 'UllTableConfig', 'tableConfig is a UllTableConfig object');
//  $t->is(is_array($tableConfig->getIdentifier()), true, 'Identifier is an array');
//  $t->is($tableConfig->getIdentifier(), array(0 => 'ull_entity_id', 1 => 'ull_group_id'), 'Identifiers are correct');
//  $t->is($tableConfig->label, 'Group memberships', 'Label is correct');
  
$t->begin('getColumnConfig()');
  $columnsConfig = $tableTool->getColumnsConfig();
  $t->is(is_array($columnsConfig), true, 'columnsConfig is an array');
  $t->is(count($columnsConfig), 12, 'columnsConfig has the correct number of columns');
  
  // don't use foreach because it ignores the ordering of the fields  
  $mocks = $t->getColumnsConfigMock();
  while (list($key, $val) = each($columnsConfig))
  {
    $columnConfig = array($key => $val);
    
    list($key, $val) = each($mocks);
    $mock = array($key => $val);
    
    $t->is($columnConfig, $mock, 'columnConfig for column "' . key($columnConfig) . '" is correct');
  }

$t->begin('getIdentifierUrlParams() without calling buildForm()');
  try
  {
    $tableTool->getIdentifierUrlParams(0);
    $t->fail('__construct() doesn\'t throw an exception although buildForm() wasn\'t called yet');
  }
  catch (Exception $e)
  {
    $t->pass('__construct() throws an exception because buildForm() wasn\'t called yet');
  }  

$t->begin('buildForm()');
  $tableTool->buildForm($tests);  
  
  $entityGroups = Doctrine::getTable('UllEntityGroup')->findAll();
  
  
$t->begin('getIdentifierUrlParams()');
  $t->is($tableTool->getIdentifierUrlParams(0), 'id=1', 'Return the correct URL params');
// we don't habe any composite primaray keys at the moment
//  $tableTool2->buildForm($entityGroups);  
//  $t->is($tableTool2->getIdentifierUrlParams(0), 'ull_entity_id=1&ull_group_id=2', 'Return the correct URL params for multi-column primary keys');  
  
$t->begin('getForm() with calling buildForm() prior');  
  $form = $tableTool->getForm();
  $t->isa_ok($form, 'ullTableToolForm', 'getForm() returns a UllForm object');
  
$t->begin('getForms()');
  $forms = $tableTool->getForms();
  $t->is(is_array($forms), true, 'getForms() returns an array');
  $t->is(count($forms), 2, 'getForms returns the correct number of forms');
  $t->isa_ok($forms[0], 'ullTableToolForm', 'The first entry is a UllForm object');  
  $t->isa_ok($forms[1], 'ullTableToolForm', 'The second entry is a UllForm object');  
  
//TODO: build without rows?  
  
//TODO: test access/enablement of fields
  
   