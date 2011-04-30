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
      ->setLabel(__('Parent', null, 'ullCmsMessages'))
    ;
    
    $this['body']
      ->setMetaWidgetClassName('ullMetaWidgetFCKEditor')
      ->setWidgetOption('CustomConfigurationsPath', '/ullCmsPlugin/js/FCKeditor_config.js')
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