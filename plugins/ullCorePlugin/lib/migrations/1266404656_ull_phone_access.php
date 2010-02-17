<?php

class UllPhoneAccess extends Doctrine_Migration_Base
{
  public function up()
  {
    
  }
  
  public function postUp()
  {
    $p = new UllPermission;
    $p->slug = 'ull_phone';
    $p->namespace = 'ullPhone';
    $p->save();    
    
    $gp = new UllGroupPermission;
    
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    $result = $dbh->query("SELECT id FROM ull_entity WHERE type='group' AND display_name = 'UserAdmins'");
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $gp->ull_group_id = $row['id'];
    
    $gp->UllPermission = $p;
    $gp->namespace = 'ullPhone';
    $gp->save(); 
  }

  public function down()
  {
  }
}
