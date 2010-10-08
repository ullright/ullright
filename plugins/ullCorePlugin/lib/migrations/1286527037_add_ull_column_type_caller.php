<?php

class AddUllColumnTypeCaller extends Doctrine_Migration_Base
{
  public function up()
  {
    //Check if it already exists
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    $result = $dbh->query("SELECT * FROM ull_column_type WHERE label='Caller'");
    
    if (!$result->fetch(PDO::FETCH_ASSOC)){    
      $o = new UllColumnType();
      $o['namespace'] = 'ullCore';
      $o['class'] = 'ullMetaWidgetCaller';
      $o['label'] = 'Caller';
      $o->save();
    }
  }

  public function down()
  {
  }
}
