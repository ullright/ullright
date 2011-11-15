<?php

class AddUllMailLoggedMessageErrorFields extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->addColumn('ull_mail_logged_message', 'ull_mail_error_id', 'integer');
    $this->addColumn('ull_mail_logged_message', 'last_error_message', 'clob');
  }

  public function down()
  {
  }
}
