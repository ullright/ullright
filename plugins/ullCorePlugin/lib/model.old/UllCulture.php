<?php

/**
 * Subclass for representing a row from the 'ull_culture' table.
 *
 * 
 *
 * @package plugins.ullCorePlugin.lib.model
 */ 
class UllCulture extends BaseUllCulture
{
  public function __toString() {
    
    return $this->getName();
    
  }
}
