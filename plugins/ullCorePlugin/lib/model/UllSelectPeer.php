<?php

/**
 * Subclass for performing query and update operations on the 'ull_select' table.
 *
 * 
 *
 * @package plugins.ullCorePlugin.lib.model
 */ 
class UllSelectPeer extends BaseUllSelectPeer
{
  
  public static function retrieveIdBySlug($slug) {
    
    $c = new Criteria();
    $c->add(self::SLUG, $slug);
    
    return self::doSelectOne($c)->getId();
    
  }
  
}
