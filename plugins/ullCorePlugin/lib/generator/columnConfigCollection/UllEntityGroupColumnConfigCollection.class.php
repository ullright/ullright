<?php

class UllEntityGroupColumnConfigCollection extends ullColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['ull_entity_id']->setLabel(__('User', null, 'common'));
  }
}