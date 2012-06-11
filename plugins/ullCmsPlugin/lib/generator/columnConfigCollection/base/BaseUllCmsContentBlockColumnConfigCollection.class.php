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
    
    $this['parent_ull_cms_item_id']
      ->setLabel(__('Parent', null, 'ullCmsMessages'))
      ->setHelp(__('Select the page on which this content block is most frequently used', null, 'ullCmsMessages'))
      ->setIsRequired(true)
    ;
    
    $this['body']
      ->setMetaWidgetClassName('ullMetaWidgetFCKEditor')
      ->setWidgetOption('CustomConfigurationsPath', '/ullCmsPlugin/js/FCKeditor_config.js')
    ;

    $this->disableAllExceptCommonHiddenAnd(array(
      'title',
      'body',
      'parent_ull_cms_item_id',
    ));
    
    if ($this->isEditAction())
    {
      $this['parent_ull_cms_item_id']
        ->markAsAdvancedField(true)
      ;
    }
    
    $this->order(array(
      array(
        'title',
        'body',
      ),
      array(
        'sequence',
        'is_active',
      ),
      array(
        'parent_ull_cms_item_id',
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