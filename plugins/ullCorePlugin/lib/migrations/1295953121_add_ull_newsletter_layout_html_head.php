<?php

class AddUllNewsletterLayoutHtmlHead extends Doctrine_Migration_Base
{
  public function up()
  {
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    
    // Add a column if it does not exist yet:
    try
    {
      $result = $dbh->query("SELECT html_head FROM ull_newsletter_layout LIMIT 1");
    }
    catch (Exception $e)
    {
      $this->addColumn('ull_newsletter_layout', 'html_head', 'string', 3000);
    }  
  }

  public function down()
  {
    $this->removeColumn('ull_newsletter_layout', 'html_head');
  }
}
