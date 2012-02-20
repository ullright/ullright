<?php

class AddUllProjectIsVisibleOnlyForProjectManagers extends Doctrine_Migration_Base
{
  public function up()
  {
    // Get dbh
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
        
    $dbh->query("UPDATE ull_project SET is_visible_only_for_project_manager=0 WHERE ID IS NOT NULL");
  }

  public function down()
  {
  }
}
