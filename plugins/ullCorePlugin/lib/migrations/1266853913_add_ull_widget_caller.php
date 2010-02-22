<?php

class AddUllWidgetCaller extends Doctrine_Migration_Base
{
  public function up()
  {
  }
  
  public function postUp()
  {
    $columnType = new UllColumnType();
    $columnType['namespace'] = 'ullFlow';
    $columnType['class'] = 'ullMetaWidgetCaller';
    $columnType['label'] = 'Caller';
    $columnType->save();
  }
  

  public function down()
  {
  }
}
