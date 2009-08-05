<?php 

class UllVentoryItemAttributeColumnConfigCollection extends ullColumnConfigCollection
{

  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    if ($this->isListAction())
    {
      $this['help']->disable();
    }
  }
}