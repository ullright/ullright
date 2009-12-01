<?php
class AddUllPhoneAccessCheck extends Doctrine_Migration
{
  public function up()
  {
    $p = new UllPermission;
    $p->slug = 'ull_phone_list';
    $p->namespace = 'ull_phone';
    $p->save();    
    
    $gp = new UllGroupPermission;
    
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    $result = $dbh->query("SELECT id FROM ull_entity WHERE type='group' AND display_name = 'Everyone'");
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $gp->ull_group_id = $row['id'];    

    $gp->UllPermission = $p;
    $gp->namespace = 'ull_phone';
    $gp->save();
    
  }
  
  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}