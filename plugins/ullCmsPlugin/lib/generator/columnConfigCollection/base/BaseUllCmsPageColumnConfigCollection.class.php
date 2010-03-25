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
      'link',
    ));
    
    $this['parent_ull_cms_item_id']
      ->setLabel(__('Higher menu entry', null, 'ullCmsMessages'))
    ;
    
    $this['body']
      ->setMetaWidgetClassName('ullMetaWidgetFCKEditor')
      ->setWidgetOption('CustomConfigurationsPath', '/ullCmsPlugin/js/FCKeditor_config.js')
    ;

    $this->order(array(
      'id',
      'title',
      'body',
      'name',
      'parent_ull_cms_item_id',
      'sequence',
    ));
    
  }
}