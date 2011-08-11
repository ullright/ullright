<?php

/**
 * Configures the ullCourse plugin. 
 */
class ullCoursePluginConfiguration extends sfPluginConfiguration
{
  
  public function initialize()
  {
    // Check for ajax update of UllCourseBooking::is_paid and if so check
    // if we need to send a payment received email
    $this->dispatcher->connect(
      'ull_table_tool.update_single_column', 
      array('UllCourseBookingTable', 'listenToUpdateSingleColumnEvent')
    );
  }
  
}
