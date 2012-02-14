<?php

class AddUllNewsletterMailingListIsDefault extends Doctrine_Migration_Base
{
  public function up()
  {
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    
    // Add a column if it does not exist yet:
    try
    {
      $result = $dbh->query("SELECT is_default FROM ull_newsletter_mailing_list LIMIT 1");
    }
    catch (Exception $e)
    {
       $this->addColumn('ull_newsletter_mailing_list', 'is_default', 'boolean');
    }  
    
    
   
  }

  public function down()
  {
    $this->removeColumn('ull_newsletter_mailing_list', 'is_default');
  }
}