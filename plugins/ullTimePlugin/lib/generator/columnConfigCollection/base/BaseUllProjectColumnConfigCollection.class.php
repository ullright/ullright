<?php 

class BaseUllProjectColumnConfigCollection extends ullColumnConfigCollection
{

  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    if ($this->isListAction())
    {
      $this['created_at']->enable();
    }
  }
}