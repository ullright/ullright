<?php

/**
 * Subclass for representing a row from the 'ull_access_group' table.
 *
 * 
 *
 * @package plugins.ullCorePlugin.lib.model
 */ 
class UllAccessGroup extends BaseUllAccessGroup
{
  
  public function __toString() {
    
    return $this->getId();
    
  }
  
}
