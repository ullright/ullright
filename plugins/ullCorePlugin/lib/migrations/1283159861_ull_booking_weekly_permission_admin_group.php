<?php

class UllBookingWeeklyPermissionAdminGroup extends Doctrine_Migration_Base
{
  public function up()
  {
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    
    // get permission id
    $result = $dbh->query("SELECT id FROM ull_permission WHERE slug = 'ull_booking_weekly_schedule'");
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $ullPermissionId = $row['id'];
    
    // get BookingAdmins group id
    $result = $dbh->query("SELECT id FROM ull_entity WHERE type='group' AND display_name = 'BookingAdmins'");
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $bookingAdminsId = $row['id'];
    
    $gp = new UllGroupPermission;
    $gp->ull_permission_id = $ullPermissionId;
    $gp->ull_group_id = $bookingAdminsId;
    $gp->save();
    
  }

  public function down()
  {
  }
}
