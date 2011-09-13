<?php

class DefaultUllCourseIsBookable extends Doctrine_Migration_Base
{
  public function up()
  {
    
   $q = new Doctrine_Query;
   $q
    ->update('UllCourse c')
    ->set('c.is_bookable', '?', true)
  ;
  
   $num = $q->execute(); //$num = number of updated rows   
    
  }

  public function down()
  {
    
  }
}
