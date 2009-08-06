<?php

class AddAddressWidgets extends Doctrine_Migration
{
	public function up()
	{
    //add ullMetaWidgetMacAddress to the list of available column types
    $ct = new UllColumnType();
    $ct->namespace = 'ullCore';
    $ct->class = 'ullMetaWidgetMacAddress';
    $ct->label = 'MAC address';
    $ct->save();
	 
    $ct = new UllColumnType();
    $ct->namespace = 'ullCore';
    $ct->class = 'ullMetaWidgetIpAddress';
    $ct->label = 'IP address';
    $ct->save();
	}

  public function down()
  {
    Doctrine::getTable('UllColumnType')->findOneByClass('ullMetaWidgetMacAddress')->delete();
    Doctrine::getTable('UllColumnType')->findOneByClass('ullMetaWidgetIpAddress')->delete();
  }
}