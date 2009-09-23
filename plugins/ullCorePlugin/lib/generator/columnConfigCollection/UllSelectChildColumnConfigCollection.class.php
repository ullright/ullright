<?php

class UllSelectChildColumnConfigCollection extends ullColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['ull_select_id']->setLabel(__('Select box', null, 'ullCoreMessages'));
  }
}