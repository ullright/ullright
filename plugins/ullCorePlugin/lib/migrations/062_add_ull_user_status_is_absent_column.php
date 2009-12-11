<?php

class AddUllUserStatusIsAbsentColumn extends Doctrine_Migration
{
  

  public function up()
  {
    $this->addColumn('ull_user_status', 'is_absent', 'boolean'); 
  }

  public function down()
  {
    $this->removeColumn('ull_user_status', 'is_absent');
  }
}