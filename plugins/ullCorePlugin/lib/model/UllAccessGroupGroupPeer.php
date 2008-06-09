<?php

/**
 * Subclass for performing query and update operations on the 'ull_access_group_group' table.
 *
 * 
 *
 * @package plugins.ullCorePlugin.lib.model
 */ 
class UllAccessGroupGroupPeer extends BaseUllAccessGroupGroupPeer
{
  
  public static function retrieveUserIdsByAccessGroupId($ull_access_group_id, $type = 'read') {
    
    $c = new Criteria;
    $c->add(self::ULL_ACCESS_GROUP_ID, $ull_access_group_id);
    if ($type == 'read') {
      $c->add(self::READ_FLAG, true);
    } else {
      $c->add(self::WRITE_FLAG, true);
    }
    $access_groups = self::doSelect($c);

    $users = array();
    
    foreach ($access_groups as $access_group) {
      
      $ull_group_id = $access_group->getUllGroupId();
      $ull_user_ids = UllUserGroupPeer::retrieveUserIdsByGroupId($ull_group_id);
      
      foreach ($ull_user_ids as $ull_user_id) {
        $users[$ull_user_id] = $ull_user_id;
      } 
      
    }
    
    // return array of users
    return $users;
  }
  
}
