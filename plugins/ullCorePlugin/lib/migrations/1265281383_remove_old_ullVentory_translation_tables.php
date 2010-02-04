<?php

class RemoveOldUllVentoryTranslationTables extends Doctrine_Migration_Base
{
  public function up()
  {
    //drop old tables
    $this->dropTable('ull_ventory_status_dummy_user_translation');
    $this->dropTable('ull_ventory_origin_dummy_user_translation');
  }

  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}
