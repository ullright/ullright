<?php

class AddStringWidget extends Doctrine_Migration
{
	public function up()
	{
    //add ullMetaWidgetFloat to the list of available column types
    $ct = new UllColumnType();
    $ct->namespace = 'ullCore';
    $ct->class = 'ullMetaWidgetString';
    $ct->label = 'String';
    $ct->save();
	}

  public function down()
  {
    Doctrine::getTable('UllColumnType')->findOneByClass('ullMetaWidgetString')->delete();
  }
}