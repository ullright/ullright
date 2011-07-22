<?php

class DropUllFlowAppPermission extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->dropTable('ull_flow_app_permission');
  }

  public function postUp()
  {
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    
    $dbh->query("DELETE FROM ull_permission WHERE slug='ullFlow_trouble_ticket_global_read'");
    $dbh->query("DELETE FROM ull_permission WHERE slug='ullFlow_trouble_ticket_global_write'");
    
    RecreateForeignKeysTask::createAllForeignKeysFromModel();
  }  
  
  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}
