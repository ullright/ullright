<?php

class UllWikiDeletedAt extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->renameColumn('ull_wiki', 'deleted', 'deleted_at');
    $this->changeColumn('ull_wiki', 'deleted_at', 'timestamp');
  }

  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.'); 
  }
}
