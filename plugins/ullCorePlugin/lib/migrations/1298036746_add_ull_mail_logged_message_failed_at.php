<?php

class AddUllMailLoggedMessageFailedAt extends Doctrine_Migration_Base
{
  public function up()
  {

    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    
    // Fix leftover error from failed migration
    try
    {
      $result = $dbh->query("SELECT failed_at FROM ull_mail_logged_message LIMIT 1");
    }
    catch (Exception $e)
    {
      // If the column does not exist yet -> add it
      $this->addColumn('ull_mail_logged_message', 'failed_at', 'timestamp');        
    }    

  }

  public function down()
  {
    $this->removeColumn('ull_mail_logged_message', 'failed_at');
  }
}
