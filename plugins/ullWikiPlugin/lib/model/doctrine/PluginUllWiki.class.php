<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class PluginUllWiki extends BaseUllWiki
{
  
  /**
   * Check access for the current model
   * 
   * returns 'w' for write access, 'r' for read access or false for no access
   *
   * @return mixed 'w', 'r' or false
   */
  public function checkAccess()
  {
    if ($userId = sfContext::getInstance()->getUser()->getAttribute('user_id'))
    {
      if (UllUserTable::hasGroup('MasterAdmins'))
      {
        return 'w';
      }

      if ($this->hasWriteAccess())
      {
        return 'w';
      }  
    }
    
    if ($this->hasReadAccess())
    {
      return 'r';
    }    
  }    
  
  
  protected function hasWriteAccess()
  {
    $userId = sfContext::getInstance()->getUser()->getAttribute('user_id');
    
    $q = new Doctrine_Query;
    $q
      ->select('x.id')
      ->from('UllWiki x')
      ->addWhere('x.id = ?', $this->id)
    ;

    $q->leftJoin('x.UllWikiAccessLevel.UllWikiAccessLevelAccess a');
    $q->leftJoin('a.UllGroup ag');
  
    // check access for any "logged in user"
    $where = '
      a.UllPrivilege.slug = ?
        AND ag.display_name = ?  
    ';
    $values = array('write', 'Logged in users');      
    
    // check group membership
    $where .= '
      OR a.UllPrivilege.slug = ? 
        AND ag.UllUser.id = ?
    ';     
    $values = array_merge($values, array('write', $userId));
    
    $q->addWhere($where, $values);
    
//    printQuery($q->getQuery());
//    var_dump($q->getParams());
//    die;      
    
    if ($q->count())
    {
      return true;
    }
  }

  protected function hasReadAccess()
  {
    // read-only access check uses the same query as the list action
    $q = new Doctrine_Query;
    $q
      ->select('x.id')
      ->from('UllWiki x')
      ->addWhere('x.id = ?', $this->id)
    ;       
    $q = BaseUllWikiActions::queryReadAccess($q);
    
    if ($q->count())
    {
      return true;
    }
  }
}