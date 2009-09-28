<?php

class UpdateColumnTypes extends Doctrine_Migration
{
  public function up()
  {
    Doctrine::getTable('UllColumnType')->findOneByClass('ullMetaWidgetUllUser')->delete();
    
    $ct = new UllColumnType();
    $ct->namespace = 'ullCore';
    $ct->class = 'ullMetaWidgetTime';
    $ct->label = 'Time';
    $ct->save();
    
    $ct = new UllColumnType();
    $ct->namespace = 'ullCore';
    $ct->class = 'ullMetaWidgetTimeDuration';
    $ct->label = 'TimeDuration';
    $ct->save();
    
    $ct = new UllColumnType();
    $ct->namespace = 'ullCore';
    $ct->class = 'ullMetaWidgetUllEntity';
    $ct->label = 'UllEntity';
    $ct->save();
  }

  public function down()
  {
    Doctrine::getTable('UllColumnType')->findOneByClass('ullMetaWidgetTime')->delete();
    Doctrine::getTable('UllColumnType')->findOneByClass('ullMetaWidgetTimeDuration')->delete();
    Doctrine::getTable('UllColumnType')->findOneByClass('ullMetaWidgetUllEntity')->delete();
    
    $ct = new UllColumnType();
    $ct->namespace = 'ullCore';
    $ct->class = 'ullMetaWidgetUllUser';
    $ct->label = 'UllUser';
    $ct->save();
  }
}