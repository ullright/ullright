<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

$t = new myTestCase(19, new lime_output_color, $configuration);

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

$t->diag('__construct()');
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

  
$t->diag('set/getSortColumns()');
  $config->setSortColumns('created_at DESC, priority');
  $t->is($config->getSortColumns(), 'created_at DESC, priority', 'Returns the correct sortColumns');

  
$t->diag('set/getSearchColumns()');
  $config->setSearchColumns('user_name,email');
  $t->is($config->getSearchColumns(), 'user_name,email', 'Returns the correct searchColumns');  

  
$t->diag('getSearchColumnsAsArray()');
  $t->is($config->getSearchColumnsAsArray(), array('user_name', 'email'), 'Returns the correct searchColumns');    
    
  
$t->diag('buildFor() a class without a TableConfig');
  $config = ullTableConfiguration::buildFor('TestTableWithoutTableConfiguration');
  $t->isa_ok($config, 'ullTableConfiguration', 'Returns the correct object');
  $t->is($config instanceof TestTableWithoutTableConfigurationTableConfiguration, false, 'has no TableConfig');

  $t->is($config->getName(), 'TestTableWithoutTableConfiguration', 'build sets the correct name');
  $t->is($config->getSearchColumns(), 'id', 'build sets the correct default search columns');
  
  
$t->diag('buildFor() a class with a TableConfig');
  $config = ullTableConfiguration::buildFor('TestTable');
  $t->is($config instanceof ullTableConfiguration, true, 'Returns the correct object');
  $t->isa_ok($config, 'TestTableTableConfiguration', 'has TableConfig');

  $t->is($config->getName(), 'My TestTable name', 'build sets the correct name');
  $t->is($config->getDescription(), 'My TestTable description', 'build sets the correct description');
  $t->is($config->getSearchColumns(), 'id,my_email', 'build sets the correct search columns');  
  $t->is($config->getSortColumns(), 'created_at DESC, my_email', 'build sets the sort columns');

  
  
