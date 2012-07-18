<?php

class AddUllGroupIsActiveData extends Doctrine_Migration_Base
{
  public function up()
  {
    $q = new Doctrine_Query;
    $q
      ->update('UllEntity e')
      ->set('is_active', '?', true)
    ;
    $q->execute();
  }  

  public function down()
  {
    
  }
}
