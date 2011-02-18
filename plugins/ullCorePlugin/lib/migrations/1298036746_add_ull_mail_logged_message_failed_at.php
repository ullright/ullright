<?php

class AddUllMailLoggedMessageFailedAt extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->addColumn('ull_mail_logged_message', 'failed_at', 'timestamp');
  }

  public function down()
  {
    $this->removeColumn('ull_mail_logged_message', 'failed_at');
  }
}
