<?php

/**
 * Subclass for performing query and update operations on the 'ull_group' table.
 *
 * 
 *
 * @package plugins.ullCorePlugin.lib.model
 */ 
class UllGroupPeer extends BaseUllGroupPeer
{
  
  public static function getIdByCaption($name) {
    
    $c = new Criteria();
    $c->add(self::CAPTION, $name);
    
    return self::doSelectOne($c)->getId();
    
  }
  
}
