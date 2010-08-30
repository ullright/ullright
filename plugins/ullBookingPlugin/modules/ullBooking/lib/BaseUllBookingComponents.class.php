<?php

class BaseUllBookingComponents extends sfComponents
{
  
  public function executeScheduleGrid()
  {
    $this->start_hour = sfConfig::get('app_ull_booking_schedule_start_hour', 9);
    $this->end_hour = sfConfig::get('app_ull_booking_schedule_end_hour', 22);
  }
  
  
}
