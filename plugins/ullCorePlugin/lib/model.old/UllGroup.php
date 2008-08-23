<?php

/**
 * Subclass for representing a row from the 'ull_group' table.
 *
 * 
 *
 * @package plugins.ullCorePlugin.lib.model
 */ 
class UllGroup extends BaseUllGroup
{
  
  public function __toString() {
    
    return $this->getCaption();
    
  }
  
}
