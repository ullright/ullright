<?php

/**
 * Represents an edit action button
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullGeneratorEditActionButtonCmsContentBlockSaveAndShow extends ullGeneratorEditActionButton
{
  
  /**
   * Render the button in the edit action template
   * 
   * @return string
   */
  public function render()
  {
    return ull_submit_tag(
      __('Save and show', null, 'common'),
      array('name' => 'submit|action_slug=save_show')
    );      
  }
  
  
  /**
   * Execute logic 
   * @return unknown_type
   */
  public function executePostFormBindAndSave()
  {
    if ($this->action->getRequest()->getParameter('action_slug') == 'save_show') 
    {
      $slug = $this->getObject()->Parent->slug;
      
      $this->action->redirect('ullCms/show?slug=' . $slug);
    }   
    
    
  }
  
}