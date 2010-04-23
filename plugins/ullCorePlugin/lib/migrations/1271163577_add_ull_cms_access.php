<?php

class AddUllCmsAccess extends Doctrine_Migration_Base
{
  public function up()
  {
  }
  
  public function postUp()
  {
    $cmsAdmins = new UllGroup();
    $cmsAdmins->display_name = 'CmsAdmins';
    $cmsAdmins->namespace = 'ull_cms';
    $cmsAdmins->save();
    
    $p = new UllPermission;
    $p->slug = 'ull_news_list';
    $p->namespace = 'ull_news';
    $p->save();    
    
    $gp = new UllGroupPermission;
    $gp->UllGroup = $cmsAdmins;
    $gp->UllPermission = $p;
    $gp->namespace = 'ull_news';
    $gp->save(); 
    
    $p = new UllPermission;
    $p->slug = 'ull_news_edit';
    $p->namespace = 'ull_news';
    $p->save();    
    
    $gp = new UllGroupPermission;
    $gp->UllGroup = $cmsAdmins;
    $gp->UllPermission = $p;
    $gp->namespace = 'ull_news';
    $gp->save(); 
    
    $p = new UllPermission;
    $p->slug = 'ull_news_newsList';
    $p->namespace = 'ull_news';
    $p->save();    
    
    $gp = new UllGroupPermission;
    
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    $result = $dbh->query("SELECT id FROM ull_entity WHERE type='group' AND display_name = 'Everyone'");
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $gp->ull_group_id = $row['id'];
    
    $gp->UllPermission = $p;
    $gp->namespace = 'ull_news';
    $gp->save(); 
  }

  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}


 