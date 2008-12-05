<?php

/**
 * Subclass for representing a row from the 'ull_flow_doc' table.
 *
 * 
 *
 * @package plugins.ullFlowPlugin.lib.model
 */ 
class UllFlowDoc extends BaseUllFlowDoc
{
  
  public function __toString() {
    return $this->getSubject();
  }
  
  public function getAssignedTo() {
    
    if ($group_id = $this->getAssignedToUllGroupId()) {
      $return =  __('group') . ' ' . UllGroupPeer::retrieveByPK($group_id);
    } elseif ($user_id = $this->getAssignedToUllUserId()) {
      $return = UllUserPeer::retrieveByPK($user_id)->getShortName();
    } else {
      $return = false;
    }
  
    return $return;   
      
  }
  
}

sfPropelBehavior::add('UllFlowDoc', array('sfPropelActAsTaggableBehavior'));