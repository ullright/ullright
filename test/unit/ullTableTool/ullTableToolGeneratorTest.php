<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
  protected $columnsConfigMock = array();
  
  public function initialize() {
    
    $columnConfig = new ullColumnConfiguration('id');
    $columnConfig->setValidatorOptions(array('required' => true));
    $columnConfig->setLabel('ID');
    $columnConfig->setMetaWidgetClassName('ullMetaWidgetInteger');
    $columnConfig->setAccess('r');
    $columnConfig->setUnique(true);
    $columnConfig->setIsInList(true);
    $this->columnsConfigMock['id'] = $columnConfig;
    
    $columnConfig = new ullColumnConfiguration('my_boolean');
    $columnConfig->setLabel('My boolean');
    $columnConfig->setMetaWidgetClassName('ullMetaWidgetCheckbox');
    $this->columnsConfigMock['my_boolean'] = $columnConfig;
    
    $columnConfig = new ullColumnConfiguration('my_email');
    $columnConfig->setWidgetAttributes(array('maxlength' => 64));
    $columnConfig->setValidatorOptions(array('required' => true, 'max_length' => 64));
    $columnConfig->setLabel('My email');
    $columnConfig->setMetaWidgetClassName('ullMetaWidgetEmail');
    $columnConfig->setUnique(true);
    $this->columnsConfigMock['my_email'] = $columnConfig;
    
    $columnConfig = new ullColumnConfiguration('my_select_box');
    $columnConfig->setWidgetOptions(array('ull_select' => 'ull_select_test', 'add_empty' => true));
    $columnConfig->setWidgetAttributes(array());
    $columnConfig->setLabel('My select box');
    $columnConfig->setMetaWidgetClassName('ullMetaWidgetUllSelect');
    $this->columnsConfigMock['my_select_box'] = $columnConfig;
  
    $columnConfig = new ullColumnConfiguration('my_useless_column');
    $columnConfig->setWidgetAttributes(array('maxlength' => 64));
    $columnConfig->setValidatorOption('max_length', 64);
    $columnConfig->setMetaWidgetClassName('ullMetaWidgetString');
    $columnConfig->setAccess(false);
    $this->columnsConfigMock['my_useless_column'] = $columnConfig;
  
    $columnConfig = new ullColumnConfiguration('ull_user_id');
    $columnConfig->setLabel('Ull user');
    $columnConfig->setMetaWidgetClassName('ullMetaWidgetUllEntity');
    $columnConfig->setRelation(array('model' => 'UllUser', 'foreign_id' => 'id'));
    $this->columnsConfigMock['ull_user_id'] = $columnConfig;
    
    $columnConfig = new ullColumnConfiguration('my_string');
    $columnConfig->setWidgetAttributes(array('maxlength' => 64));
    $columnConfig->setValidatorOptions(array('required' => true, 'max_length' => 64));
    $columnConfig->setLabel('My custom string label');
    $columnConfig->setMetaWidgetClassName('ullMetaWidgetString');
    $columnConfig->setTranslated(true);
    $this->columnsConfigMock['my_string'] = $columnConfig;
    
    $columnConfig = new ullColumnConfiguration('my_text');
    $columnConfig->setLabel('My text');
    $columnConfig->setMetaWidgetClassName('ullMetaWidgetTextarea');
    $columnConfig->setTranslated(true);
    $this->columnsConfigMock['my_text'] = $columnConfig;
    
    $columnConfig = new ullColumnConfiguration('creator_user_id');
    $columnConfig->setLabel('Created by');
    $columnConfig->setMetaWidgetClassName('ullMetaWidgetUllEntity');
    $columnConfig->setAccess('r');
    $columnConfig->setIsInList(false);
    $columnConfig->setRelation(array('model' => 'UllUser', 'foreign_id' => 'id'));
    $this->columnsConfigMock['creator_user_id'] = $columnConfig;
    
    $columnConfig = new ullColumnConfiguration('created_at');
    $columnConfig->setLabel('Created at');
    $columnConfig->setMetaWidgetClassName('ullMetaWidgetDateTime');
    $columnConfig->setAccess('r');
    $columnConfig->setIsInList(false);
    $this->columnsConfigMock['created_at'] = $columnConfig;
    
    $columnConfig = new ullColumnConfiguration('updator_user_id');
    $columnConfig->setLabel('Updated by');
    $columnConfig->setMetaWidgetClassName('ullMetaWidgetUllEntity');
    $columnConfig->setAccess('r');
    $columnConfig->setIsInList(false);
    $columnConfig->setRelation(array('model' => 'UllUser', 'foreign_id' => 'id'));
    $this->columnsConfigMock['updator_user_id'] = $columnConfig;
    
    $columnConfig = new ullColumnConfiguration('updated_at');
    $columnConfig->setWidgetOptions(array());
    $columnConfig->setWidgetAttributes(array());
    $columnConfig->setLabel('Updated at');
    $columnConfig->setMetaWidgetClassName('ullMetaWidgetDateTime');
    $columnConfig->setAccess('r');
    $columnConfig->setIsInList(false);
    $this->columnsConfigMock['updated_at'] = $columnConfig;
  }      

  public function getColumnsConfigMock()
  {
    return $this->columnsConfigMock;
  }
  
  public function compareSingleColumnConfig($columnConfig, $columnConfigMock)
  {
    $this->diag('Now comparing: ' . $columnConfig->getColumnName());
    
    //compare some of the more common values
    $this->is_deeply($columnConfig->getWidgetOptions(), $columnConfigMock->getWidgetOptions(), 'widget options ok');
    $this->is_deeply($columnConfig->getWidgetAttributes(), $columnConfigMock->getWidgetAttributes(), 'widget attributes ok');
    $this->is_deeply($columnConfig->getValidatorOptions(), $columnConfigMock->getValidatorOptions(), 'validator attributes ok');
    $this->is($columnConfig->getLabel(), $columnConfigMock->getLabel(), 'label ok');
    $this->is($columnConfig->getMetaWidgetClassName(), $columnConfigMock->getMetaWidgetClassName(), 'meta widget class name ok');
    $this->is($columnConfig->getAccess(), $columnConfigMock->getAccess(), 'access ok');
    $this->is($columnConfig->getIsInList(), $columnConfigMock->getIsInList(), 'isInList ok');
    $this->is_deeply($columnConfig->getRelation(), $columnConfigMock->getRelation(), 'relation ok');
    $this->is($columnConfig->getUnique(), $columnConfigMock->getUnique(), 'unique ok');
    $this->is($columnConfig->getTranslated(), $columnConfigMock->getTranslated(), 'translation ok');
  }
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfLoader::loadHelpers('I18N');

$t = new myTestCase(149, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);

$tests = Doctrine::getTable('TestTable')->findAll();

$t->initialize();

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
  
$t->diag('getTableConfig()');
  $tableConfig = $tableTool->getTableConfig();
  $t->isa_ok($tableConfig, 'UllTableConfig', 'tableConfig is a UllTableConfig object');  
  $t->is(is_string($tableConfig->getIdentifier()), true, 'Identifier is a string');
  $t->is($tableConfig->getIdentifier(), 'id', 'Identifier is correct');
  $t->is($tableConfig->label, 'TestTableLabel', 'Label is correct'); 

// we don't have any composite primary keys at the moment  
//$t->begin('getTableConfig() for a table with a multi-columns primary key');  
//  $tableTool2 = new ullTableToolGenerator('UllEntityGroup');
//  $tableConfig = $tableTool2->getTableConfig();
//  $t->isa_ok($tableConfig, 'UllTableConfig', 'tableConfig is a UllTableConfig object');
//  $t->is(is_array($tableConfig->getIdentifier()), true, 'Identifier is an array');
//  $t->is($tableConfig->getIdentifier(), array(0 => 'ull_entity_id', 1 => 'ull_group_id'), 'Identifiers are correct');
//  $t->is($tableConfig->label, 'Group memberships', 'Label is correct');
  
$t->diag('getColumnConfig()');
  $columnsConfig = $tableTool->getColumnsConfig();
  $t->is(is_array($columnsConfig), true, 'columnsConfig is an array');
  $t->is(count($columnsConfig), 12, 'columnsConfig has the correct number of columns');
   
  // don't use foreach because it ignores the ordering of the fields  
  $mocks = $t->getColumnsConfigMock();
  for ($i = 0; $i < count($columnsConfig); $i++)
  {
    $columnConfig =  current($columnsConfig);
    $columnConfigMock = current($mocks);
    next($columnsConfig);
    next($mocks);

    $t->isa_ok($columnConfig, 'ullColumnConfiguration', 'column configuration is correct class');
    $t->compareSingleColumnConfig($columnConfig, $columnConfigMock);
  }

$t->diag('getIdentifierUrlParams() without calling buildForm()');
  try
  {
    $tableTool->getIdentifierUrlParams(0);
    $t->fail('__construct() doesn\'t throw an exception although buildForm() wasn\'t called yet');
  }
  catch (Exception $e)
  {
    $t->pass('__construct() throws an exception because buildForm() wasn\'t called yet');
  }  

$t->diag('buildForm()');
  $tableTool->buildForm($tests);  
  
  $entityGroups = Doctrine::getTable('UllEntityGroup')->findAll();
  
  
$t->diag('getIdentifierUrlParams()');
  $t->is($tableTool->getIdentifierUrlParams(0), 'id=1', 'Return the correct URL params');
// we don't habe any composite primaray keys at the moment
//  $tableTool2->buildForm($entityGroups);  
//  $t->is($tableTool2->getIdentifierUrlParams(0), 'ull_entity_id=1&ull_group_id=2', 'Return the correct URL params for multi-column primary keys');  
  
$t->diag('getForm() with calling buildForm() prior');  
  $form = $tableTool->getForm();
  $t->isa_ok($form, 'ullTableToolForm', 'getForm() returns a UllForm object');
  
$t->diag('getForms()');
  $forms = $tableTool->getForms();
  $t->is(is_array($forms), true, 'getForms() returns an array');
  $t->is(count($forms), 2, 'getForms returns the correct number of forms');
  $t->isa_ok($forms[0], 'ullTableToolForm', 'The first entry is a UllForm object');  
  $t->isa_ok($forms[1], 'ullTableToolForm', 'The second entry is a UllForm object');  
  
//TODO: build without rows?  
  
//TODO: test access/enablement of fields
  
   