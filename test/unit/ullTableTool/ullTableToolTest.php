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
        'access'              => 'w',
        ),   
    'my_text' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'My text',
        'metaWidget'          => 'ullMetaWidgetString',
        'access'              => 'w',
        ),                       
    'my_boolean' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'My boolean',
        'metaWidget'          => 'ullMetaWidgetString',
        'access'              => 'w',
        ),
    'ull_user_id' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'Ull user',
        'metaWidget'          => 'ullMetaWidgetForeignKey',
        'access'              => 'w',
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
    'creator_user_id' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'Creator user',
        'metaWidget'          => 'ullMetaWidgetForeignKey',
        'access'              => 'r',
        'relation'            => array('model' => 'UllUser', 'foreign_id' => 'id'),
        ),
    'created_at' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'Created at',
        'metaWidget'          => 'ullMetaWidgetDateTime',
        'access'              => 'r',
        ),
    'updator_user_id' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'Updator user',
        'metaWidget'          => 'ullMetaWidgetForeignKey',
        'access'              => 'r',
        'relation'            => array('model' => 'UllUser', 'foreign_id' => 'id'),
        ),                  
    'updated_at' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'Updated at',
        'metaWidget'          => 'ullMetaWidgetDateTime',
        'access'              => 'r',
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

$t = new myTestCase(34, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);

$tests = Doctrine::getTable('TestTable')->findAll();

$t->begin('__construct()');
  try
  {
    new ullTableTool();
    $t->fail('__construct() doesn\'t throw an exception if no rows are given');
  }
  catch (Exception $e)
  {
    $t->pass('__construct() throws an exception if no rows are given');
  }

  $tableTool = new ullTableTool($tests);
  $t->isa_ok($tableTool, 'ullTableTool', '__construct() returns the correct object');

  $t->is($tableTool->getModelName(), 'TestTable', '__construct() sets the right model name');
  
  $t->is($tableTool->getDefaultAccess(), 'r', '__construct() sets the default access to "r"');
  
  try
  {
    new ullTableTool($tests, 'x');
    $t->fail('__construct() doesn\'t throw an exception if an invalid access type is given');
  }
  catch(Exception $e)
  {
    $t->pass('__construct() throws an exception if an invalid access type is given');
  }
  
  $tableTool = new ullTableTool($tests, 'w');
  $t->is($tableTool->getDefaultAccess(), 'w', '__construct() sets the correct access type "w"');
  
  
$t->begin('getTableConfig()');
  $tableConfig = $tableTool->getTableConfig();
  $t->is(is_array($tableConfig), true, 'tableConfig is an array');
  $t->is(count($tableConfig), 2, 'tableConfig has the correct number of entries');
  $t->is(is_string($tableConfig['identifier']), true, 'Identifier is a string');
  $t->is($tableConfig['label'], 'TestTable', 'Label is correct');
  $t->is($tableConfig['identifier'], 'id', 'Identifier is correct'); 

$t->begin('getTableConfig() for a table with a multi-columns primary key');  
  $entityGroups = Doctrine::getTable('UllEntityGroup')->findAll();
  $tableTool2 = new ullTableTool($entityGroups);
  $tableConfig = $tableTool2->getTableConfig();
  $t->is(is_array($tableConfig), true, 'tableConfig is an array');
  $t->is(count($tableConfig), 2, 'tableConfig has the correct number of entries');
  $t->is($tableConfig['label'], 'UllEntityGroup', 'Label is correct');
  $t->is(is_array($tableConfig['identifier']), true, 'Identifier is an array');
  $t->is($tableConfig['identifier'], array(0 => 'entity_id', 1 => 'group_id'), 'Identifiers are correct');
  
$t->begin('getColumnConfig()');
  $columnsConfig = $tableTool->getColumnsConfig();
  $t->is(is_array($columnsConfig), true, 'columnsConfig is an array');
  $t->is(count($columnsConfig), 9, 'columnsConfig has the correct number of columns');
  
  // don't use foreach because it ignores the ordering of the fields  
  $references = $t->getColumnsConfigReference();
  while (list($key, $val) = each($columnsConfig))
  {
    $columnConfig = array($key => $val);
    
    list($key, $val) = each($references);
    $ref = array($key => $val);
    
    $t->is($columnConfig, $ref, 'columnConfig for column "' . key($columnConfig) . '" is correct');
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
  
$t->begin('getIdentifierUrlParams()');
  $t->is($tableTool->getIdentifierUrlParams(0), 'id=1', 'Return the correct URL params');
  $t->is($tableTool2->getIdentifierUrlParams(0), 'entity_id=1&group_id=2', 'Return the correct URL params for multi-column primary keys');
  
  
//TODO: test access/enablement of fields
  
   