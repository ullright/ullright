<?php 

class BaseUllCmsPageColumnConfigCollection extends UllCmsItemColumnConfigCollection
{
  
  protected 
    $contentType
  ;
  
  /**
   * Check for a content type specific column config collection 
   * 
   * Example class name: UllCmsPageContactColumnConfigCollection
   * where "Contact" is the classified (=camelcase) slug name
   * 
   * 
   * @param UllCmsContentType $contentType
   * @param string $defaultAccess
   * @param string $requestAction
   */
  public static function build($contentType = null, $defaultAccess = null, $requestAction = null)
  {
    // Check for custom content type column config collection
    // Example: 
    // apps/frontend/lib/generator/columnConfigCollection/UllCmsPageContactColumnConfigCollection.class.php
    // <?php
    //
    // class UllCmsPageContactColumnConfigCollection extends UllCmsPageColumnConfigCollection
    // {
    //   protected function applyCustomSettings()
    //   {
    //     parent::applyCustomSettings();
    //    
    //     // add custom code here... 
    //   }  
    // }
    
    $className = '';

    if ($contentType !== null)
    {
      $className = 'UllCmsPage' . sfInflector::classify($contentType->slug) . 'ColumnConfigCollection'; 
    }
    
    // Fallback
    if (!class_exists($className))
    {
      $className = 'UllCmsPageColumnConfigCollection';
    }
    
    $c = new $className($contentType, $defaultAccess, $requestAction);
    $c->buildCollection();
    
    return $c;
  } 
  

  /**
   * Constructor
   * 
   * @param string $contentType
   * @param unknown_type $defaultAccess
   * @param unknown_type $requestAction
   */
  public function __construct($contentType = null, $defaultAccess = null, $requestAction = null)
  {
    $this->contentType = $contentType;
    
    parent::__construct('UllCmsPage', $defaultAccess, $requestAction);
  }    

  
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    parent::applyCustomSettings();
    
    $this->disable(array(
      'link'
    ));
    
    $this['is_active']
      ->setAjaxUpdate(true);
    ;    
    
    $this['name']
      ->setHelp(__('By default the page title is used here. Change it if you want e.g. a shorter title in the menu', null, 'ullCmsMessages'))
    ;
    
    $this['parent_ull_cms_item_id']
      ->setLabel(__('Parent', null, 'ullCmsMessages'))
      ->setIsRequired(true)
    ;
    
    $this['body']
      ->setMetaWidgetClassName('ullMetaWidgetFCKEditor')
      ->setWidgetOption('CustomConfigurationsPath', '/ullCmsPlugin/js/FCKeditor_config.js')
      ->setHelp(__('[SHIFT %arrow%] - [ENTER] creates single line breaks', 
          array('%arrow%' => '&uArr;'), 'ullCoreMessages'))
    ;
    
    $this['duplicate_tags_for_search']
      ->setLabel('Tags')
      ->setMetaWidgetClassName('ullMetaWidgetTaggable')
    ;
      
    $this->order(array(
      array(
        'title',
        'name',
        'body',
      ),
      array(
        'preview_image',
        'image',
        'gallery',
      ),        
      array(
        'parent_ull_cms_item_id',
        'sequence',
        'is_active',
        'duplicate_tags_for_search',          
      ),
      array(
        'slug',
        'id',
        'ull_cms_content_type_id',          
        'creator_user_id',
        'created_at',
        'updator_user_id',
        'updated_at',
      ),
    )); 
    
  }
}