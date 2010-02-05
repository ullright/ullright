<?php

class UllWikiDeletedAtFix extends Doctrine_Migration_Base
{
  public function up()
  {
  }
  
  public function postUp()
  {
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    $dbh->exec("UPDATE ull_wiki SET deleted_at=NULL where deleted_at='0000-00-00 00:00:00'");
  }

  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}
