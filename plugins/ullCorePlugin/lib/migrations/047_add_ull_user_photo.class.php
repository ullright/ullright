<?php

class AddUllUserPhoto extends Doctrine_Migration
{
  public function up()
  {
    $this->addColumn('ull_entity', 'photo', 'string', array('length' => 255));
  }

  public function down()
  {
    $this->removeColumn('ull_entity', 'photo');
  }
}