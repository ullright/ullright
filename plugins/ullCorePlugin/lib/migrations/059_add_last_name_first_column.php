<?php

class AddLastNameFirstColumn extends Doctrine_Migration
{
  protected $tableNames = array(
    'ull_entity',
    'ull_user_version',
    'ull_group_version',
    'ull_ventory_origin_dummy_user_version',
    'ull_ventory_status_dummy_user_version',
    'ull_clone_user_version'
    );

  public function up()
  {
    foreach ($this->tableNames as $tableName)
    {
      $this->addColumn($tableName, 'last_name_first', 'string', array('length' => 129)); 
    }
  }

  public function down()
  {
    foreach ($this->tableNames as $tableName)
    {
      $this->removeColumn($tableName, 'last_name_first');
    }
  }
}