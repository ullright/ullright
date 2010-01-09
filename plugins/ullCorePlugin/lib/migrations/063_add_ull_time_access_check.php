<?php
class AddUllTimeAccessCheck extends Doctrine_Migration
{
  public function up()
  {
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    $result = $dbh->query("SELECT id FROM ull_entity WHERE type='group' AND display_name = 'Logged in users'");
    $row = $result->fetch(PDO::FETCH_ASSOC);

    
    $p = new UllPermission;
    $p->slug = 'ull_time_index';
    $p->namespace = 'ull_time';
    $p->save();    
    
    $gp = new UllGroupPermission;
    $gp->ull_group_id = $row['id'];
    $gp->UllPermission = $p;
    $gp->namespace = 'ull_time';
    $gp->save();

    
    $p = new UllPermission;
    $p->slug = 'ull_time_list';
    $p->namespace = 'ull_time';
    $p->save();
    
    $gp = new UllGroupPermission;
    $gp->ull_group_id = $row['id'];
    $gp->UllPermission = $p;
    $gp->namespace = 'ull_time';
    $gp->save();    
    
    
    $p = new UllPermission;
    $p->slug = 'ull_time_edit';
    $p->namespace = 'ull_time';
    $p->save();
    
    $gp = new UllGroupPermission;
    $gp->ull_group_id = $row['id'];
    $gp->UllPermission = $p;
    $gp->namespace = 'ull_time';
    $gp->save();
    
    
    $p = new UllPermission;
    $p->slug = 'ull_time_edit_project';
    $p->namespace = 'ull_time';
    $p->save();

    $gp = new UllGroupPermission;
    $gp->ull_group_id = $row['id'];
    $gp->UllPermission = $p;
    $gp->namespace = 'ull_time';
    $gp->save();
        
    
    $p = new UllPermission;
    $p->slug = 'ull_time_delete_project';
    $p->namespace = 'ull_time';
    $p->save();  

    $gp = new UllGroupPermission;
    $gp->ull_group_id = $row['id'];
    $gp->UllPermission = $p;
    $gp->namespace = 'ull_time';
    $gp->save();
    
    
    // ignore locking
    $g = new UllGroup;
    $g->display_name = 'TimeAdmins';
    $p->namespace = 'ull_time';
    
    $p = new UllPermission;
    $p->slug = 'ull_time_ignore_locking';
    $p->namespace = 'ull_time';
    $p->save();   

    $gp = new UllGroupPermission;
    $gp->UllGroup = $g;    
    $gp->UllPermission = $p;
    $gp->namespace = 'ull_time';
    $gp->save();
  }
  
  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}