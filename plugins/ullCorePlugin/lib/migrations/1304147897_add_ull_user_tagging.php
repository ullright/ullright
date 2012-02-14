<?php

class AddUllUserTagging extends Doctrine_Migration_Base
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
      
      // Add a column if it does not exist yet:
      try
      {
        $result = $dbh->query("SELECT duplicate_tags_for_search FROM $tableName LIMIT 1");
      }
      catch (Exception $e)
      {
         $this->addColumn($tableName, 'duplicate_tags_for_search', 'string', 4000);
      }      
      
    }
  }

  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}
