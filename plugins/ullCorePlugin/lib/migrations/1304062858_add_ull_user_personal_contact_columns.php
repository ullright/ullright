<?php

class AddUllUserPersonalContactColumns extends Doctrine_Migration_Base
{
  protected $tableNames = array(
    'ull_entity',
    'ull_entity_version',
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
      $this->addColumn($tableName, 'title', 'string', 100);
      $this->addColumn($tableName, 'birth_date', 'date');
      $this->addColumn($tableName, 'street', 'string', 200);
      $this->addColumn($tableName, 'post_code', 'string', 10);
      $this->addColumn($tableName, 'city', 'string', 100);
      $this->addColumn($tableName, 'country', 'string', 10);
      $this->addColumn($tableName, 'phone_number', 'string', 20);
      $this->addColumn($tableName, 'fax_number', 'string', 20);
      $this->addColumn($tableName, 'website', 'string', 255);
    }
  }

  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}
