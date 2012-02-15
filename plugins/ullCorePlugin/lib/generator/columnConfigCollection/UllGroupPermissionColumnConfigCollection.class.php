<?php

class UllGroupPermissionColumnConfigCollection extends ullColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['ull_group_id']
      ->setOption('enable_ajax_autocomplete', false)
    ; 
  }
}