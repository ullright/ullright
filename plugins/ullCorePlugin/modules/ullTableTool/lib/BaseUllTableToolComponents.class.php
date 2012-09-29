<?php

class BaseUllTableToolComponents extends sfComponents
{
  
  /**
   * Comments component
   */
  public function executeComments()
  {
    $this->has_revoke_permission = 
      UllUserTable::hasPermission('ull_commentable_revoke_comments');
    
    $this->photo_widget = new ullWidgetPhoto(array(), array('width' => '100'));
    
    $this->subject = (isset($this->subject)) ? $this->subject: null; 
  }  
  
  /**
   * For ullWidgetContentElements
   */
  public function executeUllContentElementForm()
  {
    $generator = new ullContentElementGenerator(
      $this->element_data['type'],
      $this->element_data['id']
    );
    $generator->buildForm(new UllContentElement());
    
    $form = $generator->getForm();
    $form->setDefaults($this->element_data['values']);  

    $this->generator = $generator;
  }
  
}
