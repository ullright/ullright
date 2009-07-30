<?php

class UllWikiColumnConfigCollection extends UllEntityColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    if ($this->isListAction())
    {
      $this->disableAllExcept(array('id', 'subject', 'updator_user_id', 'updated_at'));
    } 
  }
}