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
  
}
