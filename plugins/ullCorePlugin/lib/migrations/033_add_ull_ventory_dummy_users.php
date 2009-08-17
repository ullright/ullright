<?php
class AddUllVentoryDummyUsers extends Doctrine_Migration
{
  public function up()
  {
    $x = new UllVentoryOriginDummyUser;
    $x->username = 'delivered';
    $x->Translation['en']->display_name = 'Delivered';
    $x->Translation['de']->display_name = 'Geliefert';
    $x->namespace = 'ull_ventory';
    $x->save();
    
    $x = new UllVentoryOriginDummyUser;
    $x->username = 'inventory_taking';
    $x->Translation['en']->display_name = 'Inventory taking';
    $x->Translation['de']->display_name = 'Inventarisiert';
    $x->namespace = 'ull_ventory';
    $x->save();    
    
    $x = new UllVentoryStatusDummyUser;
    $x->username = 'stored';
    $x->Translation['en']->display_name = 'Stored';
    $x->Translation['de']->display_name = 'Lagernd';
    $x->namespace = 'ull_ventory';
    $x->save(); 
      
    $x = new UllVentoryStatusDummyUser;
    $x->username = 'disposed';
    $x->Translation['en']->display_name = 'Disposed';
    $x->Translation['de']->display_name = 'Entsorgt';
    $x->namespace = 'ull_ventory';
    $x->save();     
      
    $x = new UllVentoryStatusDummyUser;
    $x->username = 'stolen';
    $x->Translation['en']->display_name = 'Stolen';
    $x->Translation['de']->display_name = 'Gestohlen';
    $x->namespace = 'ull_ventory';
    $x->save();      
      
    $x = new UllVentoryStatusDummyUser;
    $x->username = 'repair';
    $x->Translation['en']->display_name = 'Repair';
    $x->Translation['de']->display_name = 'Reparatur';
    $x->namespace = 'ull_ventory';
    $x->save();
      
    $x = new UllVentoryStatusDummyUser;
    $x->username = 'sold';
    $x->Translation['en']->display_name = 'Sold';
    $x->Translation['de']->display_name = 'Verkauft';
    $x->namespace = 'ull_ventory';
    $x->save();
        
    }
  
  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}