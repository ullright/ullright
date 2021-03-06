<?php

/**
 * PluginUllCmsContentBlockTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginUllCmsContentBlockTable extends UllCmsItemTable
{
    /**
     * Returns an instance of this class.
     *
     * @return object PluginUllCmsContentBlockTable
     */
    public static function getInstance()
    {
      return Doctrine_Core::getTable('PluginUllCmsContentBlock');
    }
    
    
    /** 
     * Find a content block by slug
     * 
     * @param string $slug
     */
    public static function findOneBySlug($slug)
    {
      $q = new Doctrine_Query;
      $q
        ->from('UllCmsContentBlock')
        ->where('slug = ?', $slug)
      ;
      
      return $q->fetchOne();
    }
    
  /**
   * Find active pages by given parent slug
   * 
   * @param string $slug
   * 
   * @return Doctrine_Collection
   */
  public static function findByParentSlug($slug, $limit = null)
  {
    $q = new UllQuery('UllCmsContentBlock');
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
   * Returns the web image base path of images uploaded by ullMetaWidgetSimpleUpload
   * Enter description here ...
   */
  public static function getImagePath()
  {
    $imagePath = sfConfig::get('sf_upload_dir') . '/tableTool/UllCmsContentBlock/';
    $imagePath = ullCoreTools::absoluteToWebPath($imagePath);

    return $imagePath;
  }    
    
}