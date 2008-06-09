<?php

/**
 * Subclass for performing query and update operations on the 'ull_flow_action' table.
 *
 * 
 *
 * @package plugins.ullFlowPlugin.lib.model
 */ 
class UllFlowActionPeer extends BaseUllFlowActionPeer
{
  
  public static function getActionIdBySlug($slug) {
    $c = new Criteria();
    $c->add(self::SLUG, $slug);
    $action = self::doSelectOne($c);
    if ($action) {
      return $action->getId();   
    }
    
  }
  
  public static function retrieveBySlug($slug) {
    $c = new Criteria();
    $c->add(self::SLUG, $slug);
    return self::doSelectOne($c);
  }
  
}
