<?php

/**
 * Subclass for performing query and update operations on the 'ull_user_group' table.
 *
 * 
 *
 * @package plugins.ullCorePlugin.lib.model
 */ 
class UllUserGroupPeer extends BaseUllUserGroupPeer
{
  
  public static function retrieveUserIdsByGroupId($ull_group_id) {
    
    $c = new Criteria;
    $c->add(self::ULL_GROUP_ID, $ull_group_id);
    $usergroups = self::doSelect($c);
    
    $users = array();
    
    foreach ($usergroups as $usergroup) {
      $user_id = $usergroup->getUllUserId();
      $users[$user_id] = $user_id;
    }

    // return array of users
    return $users;
  }
  
}
