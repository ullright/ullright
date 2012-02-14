<?php

class AddUllCmsPageTagging extends Doctrine_Migration_Base
{
  public function up()
  {
   
    
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    
    // Add a column if it does not exist yet:
    try
    {
      $result = $dbh->query("SELECT duplicate_tags_for_search FROM ull_cms_item LIMIT 1");
    }
    catch (Exception $e)
    {
       $this->addColumn('ull_cms_item', 'duplicate_tags_for_search', 'string', 3000);
    }    
  }

  public function down()
  {
    $this->removeColumn('ull_cms_item', 'duplicate_tags_for_search');
  }
}
