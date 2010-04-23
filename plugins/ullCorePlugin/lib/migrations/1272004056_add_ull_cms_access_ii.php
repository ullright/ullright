<?php

class AddUllCmsAccessIi extends Doctrine_Migration_Base
{
  public function up()
  {
  }
  
  public function postUp()
  {
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    $result = $dbh->query("SELECT id FROM ull_entity WHERE type='group' AND display_name = 'CmsAdmins'");
    $row = $result->fetch(PDO::FETCH_ASSOC);

    $p = new UllPermission;
    $p->slug = 'ull_cms_list';
    $p->namespace = 'ull_cms';
    $p->save();    
    
    $gp = new UllGroupPermission;
    $gp->ull_group_id = $row['id'];
    $gp->UllPermission = $p;
    $gp->namespace = 'ull_cms';
    $gp->save(); 
    
    $p = new UllPermission;
    $p->slug = 'ull_cms_edit';
    $p->namespace = 'ull_cms';
    $p->save();    
    
    $gp = new UllGroupPermission;
    $gp->ull_group_id = $row['id'];
    $gp->UllPermission = $p;
    $gp->namespace = 'ull_cms';
    $gp->save(); 
    
    $p = new UllPermission;
    $p->slug = 'ull_cms_delete';
    $p->namespace = 'ull_cms';
    $p->save();    
    
    $gp = new UllGroupPermission;
    $gp->ull_group_id = $row['id'];
    $gp->UllPermission = $p;
    $gp->namespace = 'ull_cms';
    $gp->save();    

    // missing ull_news_delete permission
    $p = new UllPermission;
    $p->slug = 'ull_news_delete';
    $p->namespace = 'ull_news';
    $p->save();    
    
    $gp = new UllGroupPermission;
    $gp->ull_group_id = $row['id'];
    $gp->UllPermission = $p;
    $gp->namespace = 'ull_news';
    $gp->save();      
    
    // every one can show a cms page
    $p = new UllPermission;
    $p->slug = 'ull_cms_show';
    $p->namespace = 'ull_cms';
    $p->save();    
    
    $gp = new UllGroupPermission;
    
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    $result = $dbh->query("SELECT id FROM ull_entity WHERE type='group' AND display_name = 'Everyone'");
    $row = $result->fetch(PDO::FETCH_ASSOC);
    
    $gp->ull_group_id = $row['id'];
    $gp->UllPermission = $p;
    $gp->namespace = 'ull_cms';
    $gp->save(); 
  }  

  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}
