<?php

/**
 * Subclass for performing query and update operations on the 'ull_flow_app' table.
 *
 * 
 *
 * @package plugins.ullFlowPlugin.lib.model
 */ 
class UllFlowAppPeer extends BaseUllFlowAppPeer
{
  
  public static function retrieveBySlug($slug)
  {
    $c = new Criteria();
    $c->add(UllFlowAppPeer::SLUG, $slug);
    
    return UllFlowAppPeer::doSelectOne($c);
    
//    return !empty($v) > 0 ? $v[0] : null;
  }
  
}
