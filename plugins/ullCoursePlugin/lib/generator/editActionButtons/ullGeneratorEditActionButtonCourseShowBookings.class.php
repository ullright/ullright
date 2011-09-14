<?php

/**
 * Represents an edit action button
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullGeneratorEditActionButtonCourseShowBookings extends ullGeneratorEditActionButton
{
  
  /**
   * Render the button in the edit action template
   * 
   * @return string
   */
  public function render()
  {
    return ull_submit_tag(
      __('Show bookings', null, 'ullCourseMessages'),
      array('name' => 'submit|action_slug=show_bookings')
    );
  }
  
  
  /**
   * Execute logic 
   * @return unknown_type
   */
  public function executePostFormBindAndSave()
  {
    if ($this->action->getRequest()->getParameter('action_slug') == 'show_bookings') 
    {
      $course = $this->action->generator->getRow();
      $this->action->redirect('ullCourseBooking/list?filter[ull_course_id]=' . $course->id);
    }   
  }
  
}