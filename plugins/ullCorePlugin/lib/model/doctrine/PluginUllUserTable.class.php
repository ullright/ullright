<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginUllUserTable extends UllEntityTable
{

  /**
   * Check if a user is member of a group.
   * 
   * Special handling for 'LoggedIn': returns true if the user is logged in
   *
   * This method uses the Doctrine result cache since repeated calling
   * is a possibility.
   * 
   * @param mixed $group group_id, group name or array of group ids/names (not mixed!)
   * @param integer $userId  
   * @return boolean
   */
  public static function hasGroup($group, $userId = null, $checkMasterAdmin = true) 
  {
    // use session user_id as default user
    if ($userId === null) 
    {
      $userId = sfContext::getInstance()->getUser()->getAttribute('user_id');
    }
    
    // We don't need to check for anything if nobody's logged in
    if (!$userId)
    {
      return false;
    }
    
    if ($group == 'LoggedIn')
    {
      return ($userId) ? true : false;
    }
    
    else
    {
      $q = new Doctrine_Query;
      $q
        ->from('UllUser u, u.UllGroup g')
        ->where('u.id = ?', $userId)
        ->useResultCache(true)
      ;
  
      if (!is_array($group))
      {
        $group = array($group);
      }
  
      if (is_numeric($group[0]))
      {
        $q->whereIn('g.id', $group);
      }
      else
      {
        $q->whereIn('g.display_name', $group);
      }
  
      if ($q->count())
      {
        return true;
      }
    }
    
    // prevent looping
    if ($checkMasterAdmin === true)
    {
      // MasterAdmins are members of all groups
      if (self::hasGroup('MasterAdmins', $userId, false))
      {
        return true;
      }
    }    

  }

  /**
   * Check if a user has a certain permission.
   * 
   * This method uses the Doctrine result cache since repeated calling
   * is a possibility.
   * 
   * @param string $permission the slug of the permission
   * @param integer $userId
   * @return boolean
   */
  public static function hasPermission($permission, $userId = null) 
  {
    // use session user_id as default entity
    if ($userId === null) 
    {
      $userId = sfContext::getInstance()->getUser()->getAttribute('user_id');
    }
    
    // Check everyone access
    $q = new Doctrine_Query;
    $q
      ->from('UllGroupPermission gp, gp.UllPermission p, gp.UllGroup g')
      ->addWhere('p.slug = ?', $permission)
      ->addWhere('g.display_name = ?', 'Everyone')
      ->useResultCache(true)
    ;
    
    if ($q->count())
    {
      return true;
    }
    else
    {
      // Skip the other checks in case nobody's logged in
      if ($userId === null)
      {
        return false;
      } 
    }     
    
    // Check access by permission / group / user
    $q = new Doctrine_Query;
    $q
      ->from('UllUser u, u.UllGroup g, g.UllPermissions p')
      ->where('u.id = ?', $userId)
      ->addWhere('p.slug = ?', $permission)
      ->useResultCache(true)
    ;
    
    if ($q->count())
    {
      return true;
    }
    
    // Check logged in access
    $q = new Doctrine_Query;
    $q
      ->from('UllGroupPermission gp, gp.UllPermission p, gp.UllGroup g')
      ->addWhere('p.slug = ?', $permission)
      ->addWhere('g.display_name = ?', 'Logged in users')
      ->useResultCache(true)
    ;
    
    if ($q->count() && $userId)
    {
      return true;
    }

    // MasterAdmins have all permissions
    if (self::hasGroup('MasterAdmins', $userId))
    {
      return true;
    }
  }  
  
  /**
   * Return choices for UllMetaWidgetEntity
   * 
   * @return array
   */
  public static function findChoices($filterUsersByGroup = null)
  {
    $q = new Doctrine_Query;
    $q
      ->select('u.id, u.last_name_first as name, us.is_active, us.is_absent')
      ->from('UllUser u INDEXBY u.id, u.UllUserStatus us')
      ->orderBy('name, us.id')
    ;
    
    if ($filterUsersByGroup !== null)
    {
      $q->addWhere('u.UllGroup.display_name = ?', $filterUsersByGroup);
    }

    $result = $q->execute(null, Doctrine::HYDRATE_ARRAY);
    
    foreach($result as $key => $value)
    {
      unset($result[$key]['id']);
      if(!$result[$key]['UllUserStatus']['is_active'])
      {
        $result[$key]['attributes']['class'] = 'color_inactiv_bg_ull_entity_widget';
      } 
      if($result[$key]['UllUserStatus']['is_absent'])
      {
        $result[$key]['attributes']['class'] = 'color_absent_bg_ull_entity_widget';
      } 
      unset($result[$key]['UllUserStatus']);
    }
    
    return $result;
  }  
  
  
  /**
   * Find username by id
   * 
   * @param $id UllUser->id
   * @return mixed
   */
  public static function findUsernameById($id)
  {
    $q = new Doctrine_Query;
    $q
      ->select('u.username')
      ->from('UllUser u')
      ->where('u.id = ?', $id)
    ;

    $result = $q->fetchOne(null, Doctrine::HYDRATE_NONE);

    return $result[0];
  }
  

  /**
   * Find id by username
   * 
   * @param $username
   * @return mixed
   */
  public static function findIdByUsername($username)
  {
    $q = new Doctrine_Query;
    $q
      ->select('u.id')
      ->from('UllUser u')
      ->where('u.username = ?', $username)
    ;

    $result = $q->fetchOne(null, Doctrine::HYDRATE_NONE);

    return $result[0];
  }  

  
  /**
   * Get the username of the logged in user
   * 
   * @return mixed
   */
  public static function findLoggedInUsername()
  {
    $user = self::findLoggedInUser();
    
    if ($user)
    {
      return $user->username;
    }
    
    return false;
  }
  
  /**
   * Get the currently logged in user
   * 
   * @return mixed
   */
  public static function findLoggedInUser()
  {
    $userId = sfContext::getInstance()->getUser()->getAttribute('user_id');
    
    if (!$userId)
    {
      return false;
    }
    
    $q = new Doctrine_Query;
    $q
      ->from('UllUser u')
      ->where('u.id = ?', $userId)
      ->useResultCache(true)
    ;

    $result = $q->fetchOne();

    return $result;   
  }
  
  public static function isActiveByUserId($userId)
  {
    if (!$userId)
    {
      return false;
    }
    
    // symfony performance test. Firing up Doctrine seems to take 500msecs
    
//    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
//    $result = $dbh->query("
//      SELECT COUNT(*) as count
//      FROM ull_entity u
//      LEFT JOIN ull_user_status s ON (u.ull_user_status_id = s.id)
//      WHERE 
//        type='user' 
//        AND u.id=$userId
//        AND s.is_active=1
//    ");
//    $row = $result->fetch(PDO::FETCH_ASSOC);
//
//    if ($row['count'])
//    {
//      return true;
//    }
    
    $q = new Doctrine_Query();
    $q
      ->from('UllUser u, u.UllUserStatus s')
      ->where('u.id = ?', $userId)
      ->addWhere('s.is_active = ?', true)
      ->useResultCache(true)
    ;
    
    if ($q->count())
    {
      return true;
    }

    return false;
  }
  
  /**
   * Returns user with active mail delivery errors
   * 
   * @return ullUser
   */
  public static function findWithBounces()
  {
    $q = new Doctrine_Query;
    $q
      ->from('UllUser u')
      ->where('u.num_email_bounces > 0')
    ;
  
    $result = $q->execute();
  
    return $result;
  }
  
  /**
   * Returns all user which mail delivery error counter has reached the maximum
   * 
   * @return ullUser
   */
  public static function findWithExceededBounceCounterLimit()
  {
    $q = new Doctrine_Query;
    $q
      ->from('UllUser u')
      ->where('u.num_email_bounces >= ?', sfConfig::get('app_ull_mail_bounce_deactivation_threshold', 3))
    ;
  
    $result = $q->execute();
  
    return $result;
  }
  
}