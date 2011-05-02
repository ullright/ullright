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
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    
    $columns = array(
      array('title', 'string', 100),
      array('birth_date', 'date'),
      array('street', 'string', 200),
      array('post_code', 'string', 10),
      array('city', 'string', 100),
      array('country', 'string', 10),
      array('phone_number', 'string', 20),
      array('fax_number', 'string', 20),
      array('website', 'string', 255),
    );    
    
    // Fix leftover error from failed migration
    foreach ($this->tableNames as $tableName)
    {
      foreach ($columns as $column)
      {    
        try
        {
          $result = $dbh->query("SELECT $column[0] FROM $tableName LIMIT 1");
        }
        catch (Exception $e)
        {
          // If the column does not exist yet -> add it
          if (!isset($column[2]))
          {
            $this->addColumn($tableName, $column[0], $column[1]);
          }
          else
          {
            $this->addColumn($tableName, $column[0], $column[1], $column[2]);
          }
          var_dump('added column ' . $column[0] . ' for ' . $tableName);
        }    
      }
    }      
    
  }

  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}
