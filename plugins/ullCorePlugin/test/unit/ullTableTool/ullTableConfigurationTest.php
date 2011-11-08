<?php

include dirname(__FILE__) . '/../../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers(array('I18N', 'ull'));

$t = new myTestCase(38, new lime_output_color, $configuration);

class TestTableWithoutTableConfiguration extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        parent::setTableDefinition();
        $this->hasColumn('name', 'string', 64, array('type' => 'string', 'length' => '64'));
    }

    public function setUp()
    {
        parent::setUp();
    }
}  

$t->begin('__construct()');
  try
  {
    new ullTableConfiguration('InvalidTable');
    $t->fail('Doesn\'t throw an exception for an invalid table name');
  }
  catch (Exception $e)
  {
    $t->pass('Throws an exception for an invalid table name');
  }
  
  $config = new ullTableConfiguration('TestTable');
  $t->isa_ok($config, 'ullTableConfiguration', 'Creates the correct object');

  
$t->diag('getIdentifier()');
  $t->is($config->getIdentifier(), 'id', 'Returns the correct identifier');  
  
  
$t->diag('getModelName()');
  $t->is($config->getModelName(), 'TestTable', 'Returns the correct model name');  
  
  
$t->diag('set/getName()');
  $config->setName('My TestTable name');
  $t->is($config->getName(), 'My TestTable name', 'Returns the correct name');
  
  
$t->diag('set/getDescription()');
  $config->setDescription('This table is for ... *useless blabla*');
  $t->is($config->getDescription(), 'This table is for ... *useless blabla*', 'Returns the correct description');

  
$t->diag('set/getOrderBy()');
  $config->setOrderBy('created_at DESC, priority');
  $t->is($config->getOrderBy(), 'created_at DESC, priority', 'Returns the correct sortColumns');

  
$t->diag('set/getSearchColumns()');
  $config->setSearchColumns(array('user_name', 'email'));
  $t->is($config->getSearchColumns(), array('user_name', 'email'), 'Returns the correct searchColumns');  

$t->diag('set/getToStringColumn()');
  try
  {
    $config->getToStringColumn();
    $t->fail('Doesn\'t throw an exception for an not-set and non-guessable to string column'); 
  } 
  catch (InvalidArgumentException $e)
  {
    $t->pass('Throws an exception for an not-set and non-guessable to string column');  
  } 
  $config->setToStringColumn('my_name');
  $t->is($config->getToStringColumn(), 'my_name', 'Returns the correct to string column name');
  
  $jobTableConfig = ullTableConfiguration::buildFor('UllJobTitle');
  $t->is($jobTableConfig->getToStringColumn(), 'name', 'Guesses "name" as default to string column name');
  
$t->diag('getSearchColumnsAsArray()');
  $t->is($config->getSearchColumnsAsArray(), array('user_name', 'email'), 'Returns the correct searchColumns');    
    
  
$t->diag('buildFor() a class without a TableConfig');
  $config = ullTableConfiguration::buildFor('TestTableWithoutTableConfiguration');
  $t->isa_ok($config, 'ullTableConfiguration', 'Returns the correct object');
  $t->is($config instanceof TestTableWithoutTableConfigurationTableConfiguration, false, 'has no TableConfig');

  $t->is($config->getName(), 'TestTableWithoutTableConfiguration', 'build sets the correct name');
  $t->is($config->getSearchColumns(), array('id'), 'build sets the correct default search columns');
  
  
$t->diag('buildFor() a class with a TableConfig');
  $config = ullTableConfiguration::buildFor('TestTable');
  $t->is($config instanceof ullTableConfiguration, true, 'Returns the correct object');
  $t->isa_ok($config, 'TestTableTableConfiguration', 'has TableConfig');

  $t->is($config->getName(), 'TestTableLabel', 'build sets the correct name');
  $t->is($config->getDescription(), 'TestTable for automated testing', 'build sets the correct description');
  $t->is($config->getSearchColumns(), array('id', 'my_email'), 'build sets the correct search columns');  
  $t->is($config->getOrderBy(), 'id', 'build sets the sort columns');

  
$t->diag('renderTaskCenterLink()');
  $reference = '<div class="float_left"><a href="/ullTableTool/list/table/TestTable"><img title="TestTable for automated testing" alt="Manage TestTableLabel" src="/ullCoreThemeNGPlugin/images/ull_admin_24x24.png" height="24" width="24" /></a></div><div><a title="TestTable for automated testing" href="/ullTableTool/list/table/TestTable">Manage TestTableLabel</a></div><div class="clear_left"></div>';
  $t->is(ullTableConfiguration::renderTaskCenterLink('TestTable', 'ullCore', 'ull_admin_24x24'), $reference, 'Returns the correct HTML');  
  
  
$t->diag('set/getForeignRelationName()');
  $config->setForeignRelationName('Test');
  $t->is($config->getForeignRelationName(), 'Test', 'Gets the foreign relation name for the current model');
  $t->is($config->getForeignRelationName('TestTable'), 'Test', 'Gets the foreign relation name for the given name');
  $config->setForeignRelationName('Relation to user', 'UllEntity');
  $t->is($config->getForeignRelationName('UllEntity'), 'Relation to user', 'Gets the foreign relation name for the given name');
  $t->is($config->getForeignRelationName('FooBar'), 'FooBar', 'Returns the humanized value for an invalid name');

  
$t->diag('set/getCustomRelationName()');
  $config->setCustomRelationName('UllUser->UllLocation', 'User location');
  $t->is($config->getCustomRelationName('UllUser->UllLocation'), 'User location', 'Returns the correct name for a given relation string');  
  $t->is($config->getCustomRelationName(array('UllUser', 'UllLocation')), 'User location', 'Returns the correct name for a given relation array');
  $config->setCustomRelationName(array('UllUser', 'UllLocation'), 'User location');
  $t->is($config->getCustomRelationName('UllUser->UllLocation'), 'User location', 'Returns the correct name for a given relation string');  
  $t->is($config->getCustomRelationName(array('UllUser', 'UllLocation')), 'User location', 'Returns the correct name for a given relation array');
  $t->is($config->getCustomRelationName('foobar'), null, 'Returns null for an invalid name');


$t->diag('set/getListColumns()');
  $config->setListColumns(array('my_email', 'UllUser->username'));
  $t->is($config->getListColumns(), array('my_email', 'UllUser->username'), 'Returns the correct columns');
// deactivated by ku 2009-10-13 because check for artificial columns needs more think-through...  
//  try
//  {
//    $config->setListColumns(array('UllUser->foobar'));
//    $t->fail('Setting an invalid relation column doesn\'t throw an exception');
//  }
//  catch (Exception $e)
//  {
//    $t->pass('Setting an invalid relation column throws an exception');
//  }

$t->diag('set/getEditColumns()');
  $config->setEditColumns(array('my_email', 'UllUser->username'));
  $t->is($config->getEditColumns(), array('my_email', 'UllUser->username'), 'Returns the correct columns');
  try
  {
    $config->setEditColumns(array('UllUser->foobar'));
    $t->fail('Setting an invalid relation column doesn\'t throw an exception');
  }
  catch (Exception $e)
  {
    $t->pass('Setting an invalid relation column throws an exception');
  }   

$t->diag('set/getFilterColumns()');
  $config->setFilterColumns(array('user_name', 'email'));
  $t->is($config->getFilterColumns(), array('user_name', 'email'), 'Returns the correct filter columns');  
  
$t->diag('set/getBreadcrumbClass()');
  $config->setBreadcrumbClass('ullNewsletterBreadcrumbTree');
  $t->is($config->getBreadcrumbClass(),'ullNewsletterBreadcrumbTree', 'Returns the correct breadcrumb class name');   
  
$t->diag('set/getPlugin()');
  $config->setPlugin('ullMailPlugin');
  $t->is($config->getPlugin(), 'ullMailPlugin', 'Returns the correct symfony plugin name');  