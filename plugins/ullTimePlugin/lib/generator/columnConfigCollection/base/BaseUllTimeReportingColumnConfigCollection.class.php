<?php 

class BaseUllTimeReportingColumnConfigCollection extends ullColumnConfigCollection
{

  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['id']->disable();
    $this['ull_user_id']->setAccess('r');
    $this['date']->setAccess('r');
    
    if ($this->isCreateAction())
    {
      $this['total_work_seconds']->disable();
      $this['total_break_seconds']->disable();
    }

    if ($this->isEditAction())
    {
      $this['total_work_seconds']->setAccess('r');
      $this['total_break_seconds']->setAccess('r');      
    }
    
    $this['created_at']->disable();
    $this['creator_user_id']->disable();
    $this['updated_at']->disable();
    $this['updator_user_id']->disable();
  }
}