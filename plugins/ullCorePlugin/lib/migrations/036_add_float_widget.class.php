<?php

class AddFloatWidget extends Doctrine_Migration
{
	public function up()
	{
    //add ullMetaWidgetFloat to the list of available column types
    $ct = new UllColumnType();
    $ct->namespace = 'ullCore';
    $ct->class = 'ullMetaWidgetFloat';
    $ct->label = 'Float';
    $ct->save();
	}

  public function down()
  {
    Doctrine::getTable('UllColumnType')->findOneByClass('ullMetaWidgetFloat')->delete();
  }
}