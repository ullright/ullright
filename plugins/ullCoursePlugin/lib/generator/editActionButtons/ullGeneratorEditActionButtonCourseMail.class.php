<?php

/**
 * Represents an edit action button
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullGeneratorEditActionButtonCourseMail extends ullGeneratorEditActionButton
{
  
  /**
   * Render the button in the edit action template
   * 
   * @return string
   */
  public function render()
  {
    return ull_submit_tag(
      __('Send email to participants', null, 'ullCourseMessages'),
      array('name' => 'submit|action_slug=mail')
    );
  }
  
  
  /**
   * Execute logic 
   * @return unknown_type
   */
  public function executePostFormBindAndSave()
  {
    if ($this->action->getRequest()->getParameter('action_slug') == 'mail') 
    {
      $course = $this->action->generator->getRow();
      $this->action->redirect('ullCourse/mail?slug=' . $course->slug);
    }   
  }
  
}