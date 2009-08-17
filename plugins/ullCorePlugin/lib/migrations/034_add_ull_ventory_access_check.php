<?php
class AddUllVentoryAccessCheck extends Doctrine_Migration
{
  public function up()
  {
    $group = new UllGroup;
    $group->display_name = 'InventoryAdmins';
    $group->namespace = 'ull_ventory';
    $group->save();
    
    $x = new UllPermission;
    $x->slug = 'ull_ventory_index';
    $x->namespace = 'ull_ventory';
    $x->save();    
    
    $gp = new UllGroupPermission;
    $gp->UllGroup = $group;
    $gp->UllPermission = $x;
    $gp->namespace = 'ull_ventory';
    $gp->save;
    
    $x = new UllPermission;
    $x->slug = 'ull_ventory_list';
    $x->namespace = 'ull_ventory';
    $x->save();
    
    $gp = new UllGroupPermission;
    $gp->UllGroup = $group;
    $gp->UllPermission = $x;
    $gp->namespace = 'ull_ventory';
    $gp->save;    
    
    $x = new UllPermission;
    $x->slug = 'ull_ventory_create';
    $x->namespace = 'ull_ventory';
    $x->save();
    
    $gp = new UllGroupPermission;
    $gp->UllGroup = $group;
    $gp->UllPermission = $x;
    $gp->namespace = 'ull_ventory';
    $gp->save;    
    
    $x = new UllPermission;
    $x->slug = 'ull_ventory_edit';
    $x->namespace = 'ull_ventory';
    $x->save();
    
    $gp = new UllGroupPermission;
    $gp->UllGroup = $group;
    $gp->UllPermission = $x;
    $gp->namespace = 'ull_ventory';
    $gp->save;    
    
    $x = new UllPermission;
    $x->slug = 'ull_ventory_show';
    $x->namespace = 'ull_ventory';
    $x->save();
    
    $gp = new UllGroupPermission;
    $gp->UllGroup = $group;
    $gp->UllPermission = $x;
    $gp->namespace = 'ull_ventory';
    $gp->save;    
            
    $x = new UllPermission;
    $x->slug = 'ull_ventory_delete';
    $x->namespace = 'ull_ventory';
    $x->save();
    
    $gp = new UllGroupPermission;
    $gp->UllGroup = $group;
    $gp->UllPermission = $x;
    $gp->namespace = 'ull_ventory';
    $gp->save;
    }
  
  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}