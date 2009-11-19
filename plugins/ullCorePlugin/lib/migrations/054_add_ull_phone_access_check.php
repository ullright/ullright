<?php
class AddUllPhoneAccessCheck extends Doctrine_Migration
{
  public function up()
  {
    $p = new UllPermission;
    $p->slug = 'ull_phone_show';
    $p->namespace = 'ull_phone';
    $p->save();    
    
    $gp = new UllGroupPermission;
    $gp->UllGroup = Doctrine::getTable('UllGroup')->findOneByDisplayName('Logged in users');
    $gp->UllPermission = $p;
    $gp->namespace = 'ull_phone';
    $gp->save();
    
  }
  
  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}