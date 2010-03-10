<?php

class FixUllUserStatus extends Doctrine_Migration_Base
{
  public function up()
  {
  }
  
  public function postUp()
  {
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    $dbh->exec("UPDATE ull_user_status SET slug='inactive' WHERE slug='separated'");
    $dbh->exec("UPDATE ull_user_status_translation SET name='Inactive' WHERE name='Separated'");
    $dbh->exec("UPDATE ull_user_status_translation SET name='Inaktiv' WHERE name='Ausgetreten'");
  }

  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}
