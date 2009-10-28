<?php

class AddUllUserPhotoIII extends Doctrine_Migration
{
  public function up()
  {
    $this->addColumn('ull_ventory_origin_dummy_user_version', 'photo', 'string', array('length' => 255));
    $this->addColumn('ull_ventory_status_dummy_user_version', 'photo', 'string', array('length' => 255));
  }

  public function down()
  {
    $this->removeColumn('ull_ventory_origin_dummy_user_version', 'photo');
    $this->removeColumn('ull_ventory_status_dummy_user_version', 'photo');
  }
}