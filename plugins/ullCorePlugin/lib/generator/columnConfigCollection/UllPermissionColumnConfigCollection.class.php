<?php

class UllPermissionColumnConfigCollection extends UllEntityColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this->enable(array('slug'));
  }
}