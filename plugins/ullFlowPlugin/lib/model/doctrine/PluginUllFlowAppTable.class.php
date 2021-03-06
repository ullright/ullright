<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginUllFlowAppTable extends UllRecordTable
{
  
  /**
   * find by slug
   *
   * @param string $slug
   * @return mixed
   */
  public static function findBySlug($slug)
  {
    if (!is_string($slug))
    {
      throw new InvalidArgumentException('slug must be a string: ' . $slug);
    }
    $q = new Doctrine_Query;
    $q
      ->from('UllFlowApp')
      ->where('slug = ?', $slug)
    ;
    
    return $q->execute()->getFirst();
  }
  
  
  /**
   * Find by Id
   *
   * @param integer $id
   * @return Doctrine_Record
   */  
  public static function findById($id)
  {
    $q = new Doctrine_Query;
    $q
      ->from('UllFlowApp')
      ->where('id = ?', $id)
      ->useResultCache(true)
    ;
    
    return $q->execute()->getFirst();
  }
  
  
  /**
   * Find all active apps ordered by name
   * 
   * @return Doctrine_Collection
   */
  public static function findAllOrderByName() 
  {
    $q = new UllQuery('UllFlowApp');
    $q
      ->addSelect('Translation->label')
      ->addSelect('Translation->doc_label')
      ->addSelect('slug')
      ->addOrderBy('Translation->label')
      ->addWhere('Translation->lang = ?', 
        substr(sfContext::getInstance()->getUser()->getCulture(), 0, 2))
      ->addWhere('is_active = 1') 
    ;
      
    $result = $q->execute();

    return $result;
  }
  
  /**
   * Check if the currently logged in user has global write access for the given app
   * @param $ullFlowAppId
   * 
   * @return boolean
   */
  public static function hasLoggedInUserGlobalWriteAccess($ullFlowAppId)
  {
    $userId = sfContext::getInstance()->getUser()->getAttribute('user_id');
    
    $q = new Doctrine_Query;
    
    $q
      ->from('UllFlowApp x')
      ->leftJoin('x.UllFlowAppAccess acc')
      ->leftJoin('acc.UllPrivilege priv')
      ->leftJoin('acc.UllGroup.UllUser privu')
      
      ->where('x.id = ?', $ullFlowAppId)
    
      // moved all where clauses into one statement to properly set the braces
      ->addWhere('priv.slug = ?', 'write')
      ->addWhere('privu.id = ?', $userId)
    ;
    
    return (boolean) $q->count();
  }  

}