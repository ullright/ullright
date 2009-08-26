<?php

class UllVentoryStatusDummyUserColumnConfigCollection extends UllEntityColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this->disableAllExcept(array('id', 'username', 'display_name', 'comment',
      'creator_user_id', 'created_at', 'updator_user_id', 'updated_at'));

    if ($this->isListAction())
    {
      $this->disableAllExcept(array('id', 'username', 'display_name', 'comment'));
    } 
  }
}



