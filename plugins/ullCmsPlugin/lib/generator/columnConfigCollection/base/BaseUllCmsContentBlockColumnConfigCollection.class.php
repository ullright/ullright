<?php 

class BaseUllCmsContentBlockColumnConfigCollection extends UllCmsItemColumnConfigCollection
{
  
  protected 
    $contentType
  ;
  
  public static function build($contentType = null, $defaultAccess = null, $requestAction = null)
  {
    // Check for custom content type column config collection
    // Example: 
// apps/frontend/lib/generator/columnConfigCollection/UllCmsContentBlockTextOnlyColumnConfigCollection.class.php
//<?php
//
//class UllCmsContentBlockTextOnlyColumnConfigCollectio extends UllCmsContentBlockColumnConfigCollection
//{
//  protected function applyCustomSettings()
//  {
//    parent::applyCustomSettings();
//    
//    // add custom code here... 
//  }  
//}
    $className = '';

    if ($contentType !== null)
    {
      $className = 'UllCmsContentBlock' . sfInflector::classify($contentType->slug) . 'ColumnConfigCollection'; 
    }
    
    
    if (!class_exists($className))
    {
      $className = 'UllCmsContentBlockColumnConfigCollection';
    }
    
    $c = new $className($contentType, $defaultAccess, $requestAction);
    $c->buildCollection();

    return $c;
  } 
  

  public function __construct($contentType = null, $defaultAccess = null, $requestAction = null)
  {
    $this->contentType = $contentType;
    
    parent::__construct('UllCmsContentBlock', $defaultAccess, $requestAction);
  }  
  
  
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    parent::applyCustomSettings();
    
    $this->disable(array(
      'name',
      'link', 
      'duplicate_tags_for_search',
      'ull_cms_content_type_id'
    ));
    
    $this['parent_ull_cms_item_id']
      ->setLabel(__('Parent', null, 'ullCmsMessages'))
      ->setIsRequired(true)
    ;
    
    $this['body']
      ->setMetaWidgetClassName('ullMetaWidgetFCKEditor')
      ->setWidgetOption('CustomConfigurationsPath', '/ullCmsPlugin/js/FCKeditor_config.js')
    ;
    
    $this->order(array(
      array(
        'title',
        'body',
      ),
      array(
        'parent_ull_cms_item_id',
        'sequence',
        'is_active',
      ),
      array(
        'slug',
        'id',
        'creator_user_id',
        'created_at',
        'updator_user_id',
        'updated_at',
      ),
    )); 
    
  }
}