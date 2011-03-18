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
  
}