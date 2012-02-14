<?php

class AddUllCmsPageImages extends Doctrine_Migration_Base
{
  public function up()
  {
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    
    // Add a column if it does not exist yet:
    try
    {
      $result = $dbh->query("SELECT preview_image FROM ull_cms_item LIMIT 1");
    }
    catch (Exception $e)
    {
       $this->addColumn('ull_cms_item', 'preview_image', 'string', 255);
    }

    try
    {
      $result = $dbh->query("SELECT image FROM ull_cms_item LIMIT 1");
    }
    catch (Exception $e)
    {
       $this->addColumn('ull_cms_item', 'image', 'string', 255);
    }        
    
    
    
    
  }

  public function down()
  {
    $this->removeColumn('ull_cms_item', 'preview_image');
    $this->removeColumn('ull_cms_item', 'image');    
  }
}

