<?php

class AddShowInOrgchartForUllUserTable extends Doctrine_Migration_Base
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
      $this->addColumn($tableName, 'is_show_in_orgchart', 'boolean'); 
    }
  }
  
  public function postUp()
  {
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    $dbh->exec("UPDATE ull_entity SET is_show_in_orgchart=1 WHERE type IN ('user', 'clone_user')");    
  }

  public function down()
  {
    foreach ($this->tableNames as $tableName)
    {
      $this->removeColumn($tableName, 'is_show_in_orgchart');
    }
  }  
  
}
