<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
  protected $columnsConfigMock = array(
    'id' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => true),
        'label'               => 'Id',
        'metaWidget'          => 'ullMetaWidgetInteger',
        'access'              => 'r',
        'show_in_list'        => true,
        ),                   
    'my_boolean' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'My boolean',
        'metaWidget'          => 'ullMetaWidgetString',
        'access'              => 'w',
        'show_in_list'        => true,
        ),
    'my_email' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array('maxlength' => 64),
        'validatorOptions'    => array('required' => false, 'max_length' => 64),
        'label'               => 'My email',
        'metaWidget'          => 'ullMetaWidgetEmail',
        'access'              => 'w',
        'show_in_list'        => true,
        ),          
    'my_useless_column' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array('maxlength' => 64),
        'validatorOptions'    => array('required' => false, 'max_length' => 64),
        'label'               => 'My useless column',
        'metaWidget'          => 'ullMetaWidgetString',
        'access'              => null,
        'show_in_list'        => true,
        ),        
    'ull_user_id' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'Ull user',
        'metaWidget'          => 'ullMetaWidgetForeignKey',
        'access'              => 'w',
        'show_in_list'        => true,
        'relation'            => array('model' => 'UllUser', 'foreign_id' => 'id'),
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
        'show_in_list'        => true,
        'translation'         => true,
        ),   
    'my_text' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'My text',
        'metaWidget'          => 'ullMetaWidgetString',
        'access'              => 'w',
        'show_in_list'        => true,
        'translation'         => true,
        ),   
    'creator_user_id' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'Creator user',
        'metaWidget'          => 'ullMetaWidgetForeignKey',
        'access'              => 'r',
        'show_in_list'        => false,
        'relation'            => array('model' => 'UllUser', 'foreign_id' => 'id'),
        ),
    'created_at' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'Created at',
        'metaWidget'          => 'ullMetaWidgetDateTime',
        'access'              => 'r',
        'show_in_list'        => false,
        ),
    'updator_user_id' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'Updator user',
        'metaWidget'          => 'ullMetaWidgetForeignKey',
        'access'              => 'r',
        'show_in_list'        => false,
        'relation'            => array('model' => 'UllUser', 'foreign_id' => 'id'),
        ),                  
    'updated_at' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'Updated at',
        'metaWidget'          => 'ullMetaWidgetDateTime',
        'access'              => 'r',
        'show_in_list'        => false,
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
sfContext::getInstance()->getUser()->setCulture('en'); // because it's set to 'xx' per default !?!
sfLoader::loadHelpers('I18N');

$t = new myTestCase(39, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);

$tests = Doctrine::getTable('TestTable')->findAll();

$t->begin('__construct()');

  try
  {
    new ullTableTool();
    $t->fail('__construct() doesn\'t throw an exception if no model is given');
  }
  catch (Exception $e)
  {
    $t->pass('__construct() throws an exception if no model is given');
  }

  try
  {
    new ullTableTool($tests);
    $t->fail('__construct() doesn\'t throw an exception if an invalid model is given');
  }
  catch (Exception $e)
  {
    $t->pass('__construct() throws an exception if an invalid model is given');
  }
  
  $tableTool = new ullTableTool('TestTable');
  $t->isa_ok($tableTool, 'ullTableTool', '__construct() returns the correct object');

  $t->is($tableTool->getModelName(), 'TestTable', '__construct() sets the right model name');
  
  $t->is($tableTool->getDefaultAccess(), 'r', '__construct() sets the default access to "r"');
  
  try
  {
    new ullTableTool('TestTable', 'x');
    $t->fail('__construct() doesn\'t throw an exception if an invalid access type is given');
  }
  catch(Exception $e)
  {
    $t->pass('__construct() throws an exception if an invalid access type is given');
  }
  
  $tableTool = new ullTableTool('TestTable', 'w');
  $t->is($tableTool->getDefaultAccess(), 'w', '__construct() sets the correct access type "w"');
  
  
$t->begin('getTableConfig()');
  $tableConfig = $tableTool->getTableConfig();
  $t->isa_ok($tableConfig, 'UllTableConfig', 'tableConfig is a UllTableConfig object');  
  $t->is(is_string($tableConfig->getIdentifier()), true, 'Identifier is a string');
  $t->is($tableConfig->getIdentifier(), 'id', 'Identifier is correct');
  $t->is($tableConfig->label, 'TestTableLabel', 'Label is correct'); 

$t->begin('getTableConfig() for a table with a multi-columns primary key');  
  $tableTool2 = new ullTableTool('UllEntityGroup');
  $tableConfig = $tableTool2->getTableConfig();
  $t->isa_ok($tableConfig, 'UllTableConfig', 'tableConfig is a UllTableConfig object');
  $t->is(is_array($tableConfig->getIdentifier()), true, 'Identifier is an array');
  $t->is($tableConfig->getIdentifier(), array(0 => 'entity_id', 1 => 'group_id'), 'Identifiers are correct');
  $t->is($tableConfig->label, 'UllEntityGroup', 'Label is correct');
  
$t->begin('getColumnConfig()');
  $columnsConfig = $tableTool->getColumnsConfig();
  $t->is(is_array($columnsConfig), true, 'columnsConfig is an array');
  $t->is(count($columnsConfig), 11, 'columnsConfig has the correct number of columns');
  
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
  
$t->begin('getForm() without calling buildForm()');
  try
  {
    $tableTool->getForm();
    $t->fail('__construct() doesn\'t throw an exception although buildForm() wasn\'t called yet');
  }
  catch (Exception $e)
  {
    $t->pass('__construct() throws an exception because buildForm() wasn\'t called yet');
  }

$t->begin('buildForm()');
  $tableTool->buildForm($tests);  
  
  $entityGroups = Doctrine::getTable('UllEntityGroup')->findAll();
  $tableTool2->buildForm($entityGroups);
  
$t->begin('getIdentifierUrlParams()');
  $t->is($tableTool->getIdentifierUrlParams(0), 'id=1', 'Return the correct URL params');
  $t->is($tableTool2->getIdentifierUrlParams(0), 'entity_id=1&group_id=2', 'Return the correct URL params for multi-column primary keys');  
  
$t->begin('getForm() with calling buildForm() prior');  
  $form = $tableTool->getForm();
  $t->isa_ok($form, 'ullForm', 'getForm() returns a UllForm object');
  
$t->begin('getForms()');
  $forms = $tableTool->getForms();
  $t->is(is_array($forms), true, 'getForms() returns an array');
  $t->is(count($forms), 2, 'getForms returns the correct number of forms');
  $t->isa_ok($forms[0], 'ullForm', 'The first entry is a UllForm object');  
  $t->isa_ok($forms[1], 'ullForm', 'The second entry is a UllForm object');  
  
$t->begin('static function getDefaultCultures()');
  $t->is(array('en',), ullTableTool::getDefaultCultures(), 'returns the correct culture');
  sfContext::getInstance()->getUser()->setCulture('de');
  $t->is(array('en', 'de'), ullTableTool::getDefaultCultures(), 'returns the correct cultures');
  
  
//TODO: build without rows?  
  
//TODO: test access/enablement of fields
  
   