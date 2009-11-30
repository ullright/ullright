<?php

class UpdateLastNameFirstColumnData extends Doctrine_Migration
{
  
  public function up()
  {
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    $dbh->exec("UPDATE ull_entity SET last_name_first = CONCAT(IFNULL(last_name, ' '), ' ', IFNULL(first_name, ' ')) WHERE type='user'");
  }

  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}