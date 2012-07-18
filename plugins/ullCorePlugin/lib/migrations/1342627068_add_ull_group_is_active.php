<?php

class AddUllGroupIsActive extends Doctrine_Migration_Base
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
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    
    foreach ($this->tableNames as $tableName)
    {
      $this->addColumn($tableName, 'is_active', 'boolean');
      
    }
  }
    
  public function down()
  {
    
  }
}
