<?php

class UllBookingsWeeklyPermission extends Doctrine_Migration_Base
{
  public function up()
  {
    $p = new UllPermission;
    $p->slug = 'ull_booking_weekly_schedule';
    $p->namespace = 'ull_booking';
    $p->save();    
  }

  public function down()
  {
  }
}
