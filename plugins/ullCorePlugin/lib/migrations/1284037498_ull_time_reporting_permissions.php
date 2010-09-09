<?php

class ullTimeReportingPermissions extends Doctrine_Migration_Base
{
  public function up()
  {
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    
    //remove ull_time_report permission from TimeAdmin (if existing)
    $result = $dbh->query("SELECT id FROM ull_permission WHERE slug = 'ull_time_report'");
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $ullPermissionId = $row['id'];
    $result = $dbh->query("SELECT id FROM ull_entity WHERE type='group' AND display_name = 'TimeAdmins'");
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $timeAdminsId = $row['id'];

    $query = $dbh->exec("DELETE FROM ull_group_permission WHERE ull_group_id = '"
      . $timeAdminsId . "' and ull_permission_id = '" . $ullPermissionId . "'");

    //now recreate the permission, but for logged-in users
    $result = $dbh->query("SELECT id FROM ull_entity WHERE type='group' AND display_name = 'Logged in users'");
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $loggedUsersId = $row['id'];

    $gp = new UllGroupPermission;
    $gp->ull_permission_id = $ullPermissionId;
    $gp->ull_group_id = $loggedUsersId;
    $gp->save();

    //add new ull_time_report_all permission
    $p = new UllPermission;
    $p->slug = 'ull_time_report_all';
    $p->namespace = 'ull_time';
    $p->save();

    //and grant it to TimeAdmins
    $gp = new UllGroupPermission;
    $gp->ull_permission_id = $p->id;
    $gp->ull_group_id = $timeAdminsId;
    $gp->save();
  }

  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException();
  }
}
