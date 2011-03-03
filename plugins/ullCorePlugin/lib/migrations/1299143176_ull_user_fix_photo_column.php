<?php

class UllUserFixPhotoColumn extends Doctrine_Migration_Base
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
    
    // Fix leftover error from failed migration
    foreach ($this->tableNames as $tableName)
    {    
      try
      {
        $result = $dbh->query("SELECT photo FROM $tableName LIMIT 1");
      }
      catch (Exception $e)
      {
        // If the column does not exist yet -> add it
        $this->addColumn($tableName, 'photo', 'string', 255);
        var_dump('added column for ' . $tableName);
      }    
    }    
  }

  public function down()
  {
  }
}
