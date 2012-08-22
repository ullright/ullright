<?php

class AddUllCmsGallery extends Doctrine_Migration_Base
{
  public function up()
  {

    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    
    // Add a column if it does not exist yet:
    try
    {
      $result = $dbh->query("SELECT gallery FROM ull_cms_item LIMIT 1");
    }
    catch (Exception $e)
    {
       $this->addColumn('ull_cms_item', 'gallery', 'string', 4000);
    }
    
  }
  
  public function postUp()
  {
  } 

  public function down()
  {
    
  }
}
