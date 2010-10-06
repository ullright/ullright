<?php

class UllOrgchartAccess extends Doctrine_Migration_Base
{
  public function up()
  {
  }
  
  public function postUp()
  {
    //Check if there is no permission for ull_orgchart_list
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    $result = $dbh->query("SELECT * FROM ull_permission WHERE slug='ull_orgchart_list'");
    
    if (!$result->fetch(PDO::FETCH_ASSOC)){
      $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
      $result = $dbh->query("SELECT id FROM ull_entity WHERE type='group' AND display_name = 'Everyone'");
      $row = $result->fetch(PDO::FETCH_ASSOC);
    
      $p = new UllPermission;
      $p->slug = 'ull_orgchart_list';
      $p->namespace = 'ull_orgchart';
      $p->save();    
      
      $gp = new UllGroupPermission;
      $gp->ull_group_id = $row['id'];
      $gp->UllPermission = $p;
      $gp->namespace = 'ull_orgchart';
      $gp->save(); 
    }
  }

  public function down()
  {
  }
}
