<?php 

class BaseUllCmsPageColumnConfigCollection extends UllCmsItemColumnConfigCollection
{

  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    parent::applyCustomSettings();
    
    $this->disable(array(
      'link', 'duplicate_tags_for_search'
    ));
    
    $this['name']
      ->setHelp(__('By default the page title is used here. Change it if you want e.g. a shorter title in the menu', null, 'ullCmsMessages'))
    ;
    
    $this['parent_ull_cms_item_id']
      ->setLabel(__('Parent menu entry', null, 'ullCmsMessages'))
    ;
    
    $this['body']
      ->setMetaWidgetClassName('ullMetaWidgetFCKEditor')
      ->setWidgetOption('CustomConfigurationsPath', '/ullCmsPlugin/js/FCKeditor_config.js')
    ;
    
    
    // add this to your custom column config to enable tagging
    // configure tags
//    $this['duplicate_tags_for_search']
//      ->setLabel('Tags')
//      ->setMetaWidgetClassName('ullMetaWidgetTaggable');
      
    $this->order(array(
      'id',
      'title',
      'body',
      'name',
      'parent_ull_cms_item_id',
      'sequence',
//      'duplicate_tags_for_search',
    ));
    
  }
}