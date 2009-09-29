<?php

class AddUllUserPhotoII extends Doctrine_Migration
{
  public function up()
  {
    $this->addColumn('ull_user_version', 'photo', 'string', array('length' => 255));
    $this->addColumn('ull_group_version', 'photo', 'string', array('length' => 255));
  }

  public function down()
  {
    $this->removeColumn('ull_user_version', 'photo');
    $this->removeColumn('ull_group_version', 'photo');
  }
}