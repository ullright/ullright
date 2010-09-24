<?php

class UllProjectIsRoutineFix extends Doctrine_Migration_Base
{
  public function up()
  {
    $dbh->query("UPDATE ull_project SET is_routine = false WHERE is_routine IS NULL");
  }

  public function down()
  {
  }
}
