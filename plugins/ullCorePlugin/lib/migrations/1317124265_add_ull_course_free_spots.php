<?php

class AddUllCourseFreeSpots extends Doctrine_Migration_Base
{
  public function up()
  {
    
    $this->addColumn('ull_course', 'proxy_number_of_spots_free', 'integer');
  }
  

  public function down()
  {
  }
}
