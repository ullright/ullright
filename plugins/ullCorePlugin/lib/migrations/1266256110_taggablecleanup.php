<?php

class Taggablecleanup extends Doctrine_Migration_Base
{
  public function up()
  {
  }

  public function postUp()
  {
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    $dbh->exec("DELETE FROM tagging WHERE taggable_model = 'UllWiki' AND taggable_id NOT IN (SELECT id from ull_wiki)");
    $dbh->exec("DELETE FROM tagging WHERE taggable_model = 'UllFlowDoc' AND taggable_id NOT IN (SELECT id from ull_flow_doc)");
  }

  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}
