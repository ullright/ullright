<?php

class ullFlowFunctions 
{
  
  public static function build_ull_flow_doc_access(ullFlowDoc $doc) {
    
    $app = $doc->getUllFlowApp();
    
    $users_read = array();
    $users_write = array();
    
    // add next_user
    if ($user_id = $doc->getAssignedToUllUserId()) {
      $users_read[$user_id] = $user_id;
      $users_write[$user_id] = $user_id;
    }
    
    // add next_group
    if ($group_id = $doc->getAssignedToUllGroupId()) {
      $user_ids     = UllUserGroupPeer::retrieveUserIdsByGroupId($group_id);
      $users_read   = ullCoreTools::ull_array_merge($users_read, $user_ids);
      $users_write  = ullCoreTools::ull_array_merge($users_write, $user_ids);
    }
    
    // add global app access
    if ($app_access_group = $app->getUllAccessGroupId()) {
      $user_ids_read = UllAccessGroupGroupPeer::retrieveUserIdsByAccessGroupId($app_access_group, 'read'); 
      $user_ids_write = UllAccessGroupGroupPeer::retrieveUserIdsByAccessGroupId($app_access_group, 'write');
      $users_read   = ullCoreTools::ull_array_merge($users_read, $user_ids_read);
      $users_write  = ullCoreTools::ull_array_merge($users_write, $user_ids_write);
    }

    // add users and groups from memory
    $c = new Criteria();
    $c->add(UllFlowMemoryPeer::ULL_FLOW_DOC_ID, $doc->getId());
    $memories = UllFlowMemoryPeer::doSelect($c);
    foreach ($memories as $memory) {
      
      // add user
      if ($user_id = $memory->getCreatorUserId()) {
//        ullCoreTools::printR($user_id);
        $users_read[$user_id] = $user_id;
      }
      
//      // add next_user
//      if ($user_id = $memory->getAssignedToUllUserId()) {
//        $users_read[$user_id] = $user_id;
//      }
//      
//      // add next_group
//      if ($group_id = $memory->getAssignedToUllGroupId()) {
//        $user_ids     = UllUserGroupPeer::retrieveUserIdsByGroupId($group_id);
//        $users_read   = ullCoreTools::ull_array_merge($users_read, $user_ids);
//      }
    }
    
    // add latest memory user as write access, to allow corrections to a doc as 
    //   long as it is not edited by the next ones
    $c = new Criteria();
    $c->add(UllFlowMemoryPeer::ULL_FLOW_DOC_ID, $doc->getId());
    $c->addDescendingOrderByColumn(UllFlowMemoryPeer::CREATED_AT);
    $memory = UllFlowMemoryPeer::doSelectOne($c);
    if ($memory) {
      if ($user_id = $memory->getCreatorUserId()) {
        $users_write[$user_id] = $user_id;
      }
    }
    
    
    
    // add masteradmin
    $user_ids = UllUserGroupPeer::retrieveUserIdsByGroupId(
      UllGroupPeer::getIdByCaption('Master-Administrator'));
    $users_read   = ullCoreTools::ull_array_merge($users_read, $user_ids);
    $users_write  = ullCoreTools::ull_array_merge($users_write, $user_ids);
    
//    
//    ullCoreTools::printR($users_read);
//    ullCoreTools::printR($users_write);
//    exit();

    
    // == store read group
    $group_read_id = $doc->getReadUllGroupId();

    // create new system ull_group if none yet
    if (!$group_read_id) {
      $group_read = new UllGroup();
      $group_read->setSystem(true);
      $group_read->save();
      $group_read_id = $group_read->getId();
      
      $doc->setReadUllGroupId($group_read_id);
      
    }

    // delete old user-group memberships
    $c = new Criteria();
    $c->add(UllUserGroupPeer::ULL_GROUP_ID, $group_read_id);
    UllUserGroupPeer::doDelete($c);
    
    // create new ull_user_group entries
    foreach ($users_read as $user_read) {
      $ull_user_group = new UllUserGroup();
      $ull_user_group->setUllUserId($user_read);
      $ull_user_group->setUllGroupId($group_read_id);
      $ull_user_group->save();
    }
    
    
    
    // == store write group
    $group_write_id = $doc->getWriteUllGroupId();

    // create new system ull_group if none yet
    if (!$group_write_id) {
      $group_write = new UllGroup();
      $group_write->setSystem(true);
      $group_write->save();
      $group_write_id = $group_write->getId();
      
      $doc->setWriteUllGroupId($group_write_id);
      
    }

    // delete old user-group memberships
    $c = new Criteria();
    $c->add(UllUserGroupPeer::ULL_GROUP_ID, $group_write_id);
    UllUserGroupPeer::doDelete($c);
    
    // create new ull_user_group entries
    foreach ($users_write as $user_write) {
      $ull_user_group = new UllUserGroup();
      $ull_user_group->setUllUserId($user_write);
      $ull_user_group->setUllGroupId($group_write_id);
      $ull_user_group->save();
    }    
    
    
    // == save doc
    $doc->setUpdatedAt($doc->getUpdatedAt()); //don't change updated_at
    $doc->save();
    
  }
  
  
}

?>