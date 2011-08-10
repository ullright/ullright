<?php

/**
 * Represents an edit action button
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullGeneratorEditActionButtonCourseCancel extends ullGeneratorEditActionButton
{
  
  /**
   * Render the button in the edit action template
   * 
   * @return string
   */
  public function render()
  {
    if (!$this->action->generator->getRow()->is_canceled)
    {
      return ull_submit_tag(
        __('Cancel course', null, 'ullCourseMessages'),
        array('name' => 'submit|action_slug=cancel')
      );
    }      
  }
  
  
  /**
   * Execute logic 
   * @return unknown_type
   */
  public function executePostFormBindAndSave()
  {
    if ($this->action->getRequest()->getParameter('action_slug') == 'cancel') 
    {
      $course = $this->action->generator->getRow();
      $course->is_canceled = true;
      $course->is_active = false;
      $course->save(); 
      
      $this->action->redirect('ullCourse/cancel?slug=' . $course->slug);
    }   
  }
  
}