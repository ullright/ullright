<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginUllUserTable extends UllEntityTable
{

  /**
   * check if a user is member of a group
   * 
   * special handling for 'LoggedIn': returns true if the user is logged in
   *
   * @param mixed $group      group_id, group name or array of group ids/names (not mixed!)
   * @param integer $user_id  
   * @return boolean
   */
  public static function hasGroup($group, $user_id = null) 
  {
    // use session user_id as default entity
    if ($user_id === null) {
      $user_id = sfContext::getInstance()->getUser()->getAttribute('user_id');
    }

    if ($group == 'LoggedIn' && $user_id)
    {
      return true;
    }
    else
    {
      $q = new Doctrine_Query;
      $q
        ->from('UllUser u, u.UllGroup g')
        ->where('u.id = ?', $user_id)
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
  }

  /**
   * check if a user has a certain permission
   * 
   * @param string $permission
   * @param integer $user_id
   * @return boolean
   */
  public static function hasPermission($permission, $userId = null) 
  {
    // use session user_id as default entity
    if ($userId === null) 
    {
      $userId = sfContext::getInstance()->getUser()->getAttribute('user_id');
    }
    
    // MasterAdmins have all permissions
    if (self::hasGroup('MasterAdmins', $userId))
    {
      return true;
    }        

    // Check access by permission / group / user
    $q = new Doctrine_Query;
    $q
      ->from('UllUser u, u.UllGroup g, g.UllPermissions p')
      ->where('u.id = ?', $userId)
      ->addWhere('p.slug = ?', $permission)      
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
    ;
    
    if ($q->count() && $userId)
    {
      return true;
    }       
    
    // Check everyone access
    $q = new Doctrine_Query;
    $q
      ->from('UllGroupPermission gp, gp.UllPermission p, gp.UllGroup g')
      ->addWhere('p.slug = ?', $permission)
      ->addWhere('g.display_name = ?', 'Everyone')      
    ;
    
    if ($q->count())
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
      ->select('u.id, u.last_name_first as name')
      ->from('UllUser u INDEXBY u.id')
      ->orderBy('name')
    ;
    
    if ($filterUsersByGroup !== null)
    {
      $q->addWhere('u.UllGroup.display_name = ?', $filterUsersByGroup);
    }

    $result = $q->execute(null, Doctrine::HYDRATE_ARRAY);
    
    foreach($result as $key => $value)
    {
      unset($result[$key]['id']);  
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
  public static function getLoggedInUsername()
  {
    $userId = sfContext::getInstance()->getUser()->getAttribute('user_id');
    
    return self::findUsernameById($userId);
  }
  
}