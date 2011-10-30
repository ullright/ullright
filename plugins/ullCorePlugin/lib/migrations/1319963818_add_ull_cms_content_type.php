<?php

class AddUllCmsTables extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->addColumn('ull_cms_item', 'ull_cms_content_type_id', 'integer');
  }
  
  public function postUp()
  {
    RecreateForeignKeysTask::createAllForeignKeysFromModel();
  } 

  public function down()
  {
    
  }
}
