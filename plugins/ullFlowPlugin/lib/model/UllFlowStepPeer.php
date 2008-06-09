<?php

/**
 * Subclass for performing query and update operations on the 'ull_flow_step' table.
 *
 * 
 *
 * @package plugins.ullFlowPlugin.lib.model
 */ 
class UllFlowStepPeer extends BaseUllFlowStepPeer
{
  
  public static function getStepIdBySlug($slug) {
    $c = new Criteria();
    $c->add(self::SLUG, $slug);
    return self::doSelectOne($c)->getId();
  }
  
}
