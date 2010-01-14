<?php

class AddUllProjectIsActiveColumn extends Doctrine_Migration
{
  public function up()
  {
    $this->addColumn('ull_project', 'is_active', 'boolean', array('default' => true)); 
  }

  public function down()
  {
    $this->removeColumn('ull_project', 'is_active');
  }
}