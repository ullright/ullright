<?php

/**
 * Subclass for performing query and update operations on the 'ull_user' table.
 *
 * 
 *
 * @package plugins.ullCorePlugin.lib.model
 */ 
class UllUserPeer extends BaseUllUserPeer
{
 /**
 * Check if a user is member of a group
 * @param group_id
 * @param user_id       optional, use currently logged in user as default
 * @return bool
 */ 
  
  public static function userHasGroup($group_id, $user_id = 0) {
    
    // use session user_id as default
    if (!$user_id) {
      $user_id = sfContext::getInstance()->getUser()->getAttribute('user_id'); 
    }
    
    $c = new Criteria();
    $c->add(UllUserGroupPeer::ULL_GROUP_ID, $group_id);
    $c->add(UllUserGroupPeer::ULL_USER_ID, $user_id);
    if (self::doCount($c)) {
       return true;    
    }
  }
  
  public static function selectOrdered() {
    $c = new Criteria();
    $c->addAscendingOrderByColumn(UllUserPeer::FIRST_NAME);
    $c->addAscendingOrderByColumn(UllUserPeer::LAST_NAME);
    return UllUserPeer::doSelect($c);
    
  }
  

}
