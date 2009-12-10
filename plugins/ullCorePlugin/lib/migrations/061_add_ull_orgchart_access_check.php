<?php
class AddUllOrchartAccessCheck extends Doctrine_Migration
{
  public function up()
  {
    $p = new UllPermission;
    $p->slug = 'ull_orgchart_list';
    $p->namespace = 'ullCore';
    $p->save();    
    
    $gp = new UllGroupPermission;
    
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    $result = $dbh->query("SELECT id FROM ull_entity WHERE type='group' AND display_name = 'Everyone'");
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $gp->ull_group_id = $row['id'];      
    
    $gp->UllPermission = $p;
    $gp->namespace = 'ullCore';
    $gp->save();
    
  }
  
  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}