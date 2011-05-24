<?php
/**
 */
class PluginUllCmsPageTable extends UllCmsItemTable
{
  /**
   * Find active pages by given parent slug
   * 
   * @param string $slug
   * 
   * @return Doctrine_Collection
   */
  public static function findByParentSlug($slug, $limit = null)
  {
    $q = new UllQuery('UllCmsPage');
    $q
      ->addSelect(array('*'))
      ->addWhere('Parent->slug = ?', $slug)
      ->addWhere('is_active = ?', true)
      ->addOrderBy('sequence, name')
    ;
    
    if ($limit)
    {
      $q->limit($limit);
    }
    
    $result = $q->execute();
    
    return $result;
  }    

  /**
   * Returns random active cms pages
   * 
   * @param integer $limit number of pages
   * @param Doctrine_Query $q give a doctrine query object for additional clauses
   * 
   * @return Doctrine_Collection
   */
  public static function findRandomPages($limit = 4, $q = null)
  {
    $q = ($q) ? $q : new Doctrine_Query;
    $q
      ->addSelect('p.*, RANDOM() AS rand')
      ->addFrom('UllCmsPage p')
      ->addOrderby('rand')
      ->addWhere('is_active = ?', true)
      ->limit($limit)
    ;
    
    $result = $q->execute();
    
    return $result;
  }
  
}