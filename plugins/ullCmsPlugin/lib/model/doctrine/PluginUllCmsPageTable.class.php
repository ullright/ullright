<?php
/**
 */
class PluginUllCmsPageTable extends UllRecordTable
{

  /**
   * Find pages by given parent slug
   * 
   * @param string $slug
   * 
   * @return Doctrine_Collection
   */
  public static function findByParentSlug($slug)
  {
    $q = new UllQuery('UllCmsPage');
    $q
      ->addSelect(array('*'))
      ->addWhere('Parent->slug = ?', $slug)
      ->addOrderBy('sequence, name')
    ;
    
    $result = $q->execute();
    
    return $result;
  }    
  
  /**
   * Returns random cms pages
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
      ->limit($limit)
    ;
    
    $result = $q->execute();
    
    return $result;
  }
  
}