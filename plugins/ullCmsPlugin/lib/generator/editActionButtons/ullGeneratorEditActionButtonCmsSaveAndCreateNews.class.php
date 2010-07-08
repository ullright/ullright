<?php

/**
 * Represents an edit action button
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullGeneratorEditActionButtonCmsSaveAndCreateNews extends ullGeneratorEditActionButton
{
  
  /**
   * Render the button in the edit action template
   * 
   * @return string
   */
  public function render()
  {
    return ull_submit_tag(
      __('Save and create news entry', null, 'ullNewsMessages'),
      array('name' => 'submit|action_slug=save_create_news')
    );      
  }
  
  
  /**
   * Execute logic 
   * @return unknown_type
   */
  public function executePostFormBindAndSave()
  {
    if ($this->action->getRequest()->getParameter('action_slug') == 'save_create_news') 
    {
      $this->action->redirect('ullNews/create?cmsSlug=' . $this->action->generator->getRow()->slug);
    }   
  }
  
}