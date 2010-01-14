<?php
class AddUllTimeAccessCheckII extends Doctrine_Migration
{
  public function up()
  {
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    $result = $dbh->query("SELECT id FROM ull_entity WHERE type='group' AND display_name = 'TimeAdmins'");
    $row = $result->fetch(PDO::FETCH_ASSOC);
    
    // allow to enter as any user
    $p = new UllPermission;
    $p->slug = 'ull_time_act_as_user';
    $p->namespace = 'ull_time';
    $p->save();   

    $gp = new UllGroupPermission;
    $gp->ull_group_id = $row['id'];
    $gp->UllPermission = $p;
    $gp->namespace = 'ull_time';
    $gp->save();
  }
  
  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}