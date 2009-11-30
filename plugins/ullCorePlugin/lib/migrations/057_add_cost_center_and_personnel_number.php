<?php

class AddCostCenterAndPersonnelNumber extends Doctrine_Migration
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
      $this->addColumn($tableName, 'cost_center', 'string', array('length' => 64));
      $this->addColumn($tableName, 'personnel_number', 'string', array('length' => 20)); 
    }
  }

  public function down()
  {
    foreach ($this->tableNames as $tableName)
    {
      $this->removeColumn($tableName, 'cost_center');
      $this->removeColumn($tableName, 'personnel_number');
    }
  }
}