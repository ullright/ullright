<?php

/**
 * Represents an edit action button
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullGeneratorEditActionButtonCmsSaveAndShow extends ullGeneratorEditActionButton
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
      // Return to correct page for content- and sidebarblocks
      if ($this->getObject()->Parent->slug == 'content-blocks' || $this->getObject()->Parent->slug == 'sidebar-blocks' ) 
      {
        $this->action->redirect($this->action->getUriMemory()->get('show'));
      }
      
      $this->action->redirect('@ull_cms_show?slug=' . $this->action->generator->getRow()->slug);
    }   
    
    
  }
  
}