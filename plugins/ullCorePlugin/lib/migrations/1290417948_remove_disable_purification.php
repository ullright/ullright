<?php

class RemoveDisablePurification extends Doctrine_Migration_Base
{
  public function up()
  {
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    $result = $dbh->query('SELECT * FROM ull_flow_column_config');
    
    foreach ($result as $row)
    {
      if ($row['options'] !== null)
      {
        $options = sfToolkit::stringToArray($row['options']);
        if (array_key_exists('disablePurification', $options))
        {
          unset($options['disablePurification']);
          $newOptions = array();
          foreach ($options as $key => $value)
          {
            $newOptions[] = $key . '=' . $value;
          }
          
          $stm = $dbh->prepare('UPDATE ull_flow_column_config SET options = :options WHERE id = :id');
          $stm->bindValue(':options', implode(' ', $newOptions), PDO::PARAM_STR);
          $stm->bindValue(':id', $row['id'], PDO::PARAM_INT);
          $stm->execute();
        }
      }
    }
    
  }

  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException();
  }
}
