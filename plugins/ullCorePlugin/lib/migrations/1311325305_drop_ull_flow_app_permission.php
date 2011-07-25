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
    
    // This is kind of brutal, because we delete versions....
    // TODO: fix versionable delete 
    // http://www.ullright.org/ullFlow/edit/app/bug_tracking/order/priority/order_dir/asc/doc/1533  
    $result = $dbh->query("SELECT id FROM ull_permission WHERE slug='ullFlow_trouble_ticket_global_read'");
    $data = $result->fetch(PDO::FETCH_ASSOC);
    $readId = $data['id'];
    
    $result = $dbh->query("SELECT id FROM ull_permission WHERE slug='ullFlow_trouble_ticket_global_write'");
    $data = $result->fetch(PDO::FETCH_ASSOC);
    $writeId = $data['id'];    

    $dbh->query("DELETE FROM ull_group_permission_version WHERE ull_permission_id=$readId");
    $dbh->query("DELETE FROM ull_group_permission_version WHERE ull_permission_id=$writeId");    
    
    $dbh->query("DELETE FROM ull_group_permission WHERE ull_permission_id=$readId");
    $dbh->query("DELETE FROM ull_group_permission WHERE ull_permission_id=$writeId");
    
    $dbh->query("DELETE FROM ull_permission_version WHERE slug='ullFlow_trouble_ticket_global_read'");
    $dbh->query("DELETE FROM ull_permission_version WHERE slug='ullFlow_trouble_ticket_global_write'");    
    
    $dbh->query("DELETE FROM ull_permission WHERE slug='ullFlow_trouble_ticket_global_read'");
    $dbh->query("DELETE FROM ull_permission WHERE slug='ullFlow_trouble_ticket_global_write'");    
    
    $libPath = sfConfig::get('sf_lib_dir');
    
    // this has to be called by hand for custom installations. see update log
    
//    echo shell_exec('svn --force delete ' . $libPath . '/model/doctrine/ullFlowPlugin/base/BaseUllFlowAppPermission.class.php');
//    echo shell_exec('svn --force delete ' . $libPath . '/model/doctrine/ullFlowPlugin/UllFlowAppPermission.class.php');
//    echo shell_exec('svn --force delete ' . $libPath . '/model/doctrine/ullFlowPlugin/UllFlowAppPermissionTable.class.php');
//    
//    echo shell_exec('svn --force delete ' . $libPath . '/form/doctrine/ullFlowPlugin/base/BaseUllFlowAppPermissionForm.class.php');
//    echo shell_exec('svn --force delete ' . $libPath . '/form/doctrine/ullFlowPlugin/UllFlowAppPermissionForm.class.php');
//    
//    echo shell_exec('svn --force delete ' . $libPath . '/filter/doctrine/ullFlowPlugin/base/BaseUllFlowAppPermissionFormFilter.class.php');
//    echo shell_exec('svn --force delete ' . $libPath . '/filter/doctrine/ullFlowPlugin/UllFlowAppPermissionFormFilter.class.php');
//    
//    echo shell_exec('php symfony cache:clear');
//    echo shell_exec('php symfony doctrine:build --model --forms --filters');
    
    RecreateForeignKeysTask::createAllForeignKeysFromModel();    
  }  
  
  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}
