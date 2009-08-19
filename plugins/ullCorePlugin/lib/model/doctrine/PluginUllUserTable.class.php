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
      $q->from('UllUser u, u.UllGroup g')
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

    $q = new Doctrine_Query;
    $q->from('UllUser u, u.UllGroup g, g.UllPermissions p')
      ->where('u.id = ?', $userId)
      ->addWhere('p.slug = ?', $permission)      
    ;
    
    if ($q->count())
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
  public static function findChoices()
  {
    $q = new Doctrine_Query;
    $q
      ->select('u.id, u.display_name as name')
      ->from('UllUser u INDEXBY u.id')
      ->orderBy('name')
    ;
    
    $result = $q->execute(null, Doctrine::HYDRATE_ARRAY);
    
    foreach($result as $key => $value)
    {
      unset($result[$key]['id']);  
    }
    
    return $result;
  }  
  
}