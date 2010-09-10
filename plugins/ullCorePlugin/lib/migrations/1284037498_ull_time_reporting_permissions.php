<?php

class ullTimeReportingPermissions extends Doctrine_Migration_Base
{
  public function up()
  {
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    
    // ull_time_report permission is now ull_time_report_all (the permission for admins)
    $dbh->query("UPDATE ull_permission SET slug = 'ull_time_report_all' WHERE slug = 'ull_time_report'");
    
    /* now recreate the ull_time_report permission, but for logged-in users */
    
    $result = $dbh->query("SELECT id FROM ull_entity WHERE type='group' AND display_name = 'Logged in users'");
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $loggedUsersId = $row['id'];
    
    //add new ull_time_report permission (general access to reports)
    $p = new UllPermission;
    $p->slug = 'ull_time_report';
    $p->namespace = 'ull_time';
    $p->save();

    $gp = new UllGroupPermission;
    $gp->ull_permission_id = $p->id;
    $gp->ull_group_id = $loggedUsersId;
    $gp->save();
  }

  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException();
  }
}
