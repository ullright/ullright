<?php
class AddUllUserPopupAccessCheck extends Doctrine_Migration
{
  public function up()
  {
    $p = new UllPermission;
    $p->slug = 'ull_user_show';
    $p->namespace = 'ullCore';
    $p->save();    
    
    $gp = new UllGroupPermission;
    $gp->UllGroup = Doctrine::getTable('UllGroup')->findOneByDisplayName('Everyone');
    $gp->UllPermission = $p;
    $gp->namespace = 'ullCore';
    $gp->save();
    
  }
  
  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}