<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class InsertUserStatus extends Doctrine_Migration
{
  public function up()
  {
    $us = new UllUserStatus();
    $us->is_active = true;
    $us->namespace = 'ullCore';
    $us->Translation[0]->lang = 'en';
    $us->Translation[0]->name = 'Active';
    $us->Translation[1]->lang = 'de';
    $us->Translation[1]->name = 'Aktiv';
    $us->save();
    
    $us = new UllUserStatus();
    $us->is_active = false;
    $us->namespace = 'ullCore';
    $us->Translation[0]->lang = 'en';
    $us->Translation[0]->name = 'Separated';
    $us->Translation[1]->lang = 'de';
    $us->Translation[1]->name = 'Ausgetreten';
    $us->save();
    
    $us = new UllUserStatus();
    $us->is_active = true;
    $us->namespace = 'ullCore';
    $us->Translation[0]->lang = 'en';
    $us->Translation[0]->name = 'Maternity leave';
    $us->Translation[1]->lang = 'de';
    $us->Translation[1]->name = 'Karenz';
    $us->save();
    
    $us = new UllUserStatus();
    $us->is_active = false;
    $us->namespace = 'ullCore';
    $us->Translation[0]->lang = 'en';
    $us->Translation[0]->name = 'Military service';
    $us->Translation[1]->lang = 'de';
    $us->Translation[1]->name = 'Präsenzdienst';
    $us->save();
    
    $us = new UllUserStatus();
    $us->is_active = false;
    $us->namespace = 'ullCore';
    $us->Translation[0]->lang = 'en';
    $us->Translation[0]->name = 'Civilian service';
    $us->Translation[1]->lang = 'de';
    $us->Translation[1]->name = 'Zivildienst';
    $us->save();  
  }

  public function down()
  {
    //004 drops the table anyway... we won't need a down() here
  }
}