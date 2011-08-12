<?php

/**
 * Named Query
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullNamedQueryUllCourseBookingAll extends ullNamedQuery
{
  
  public function configure()
  {
    $this->name       = 'All bookings';
    $this->identifier = 'all';
  }
  
  public function modifyQuery($q)
  {
  }
  
}