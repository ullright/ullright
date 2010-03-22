<?php 

class BaseUllCmsPageColumnConfigCollection extends ullColumnConfigCollection
{

  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this->disable(array(
      'link'
    ));
    
    $this['parent_ull_cms_item_id']
      ->setLabel(__('In navigation', null, 'ullCmsMessages'))
    ;
    
    $this['body']
      ->setMetaWidgetClassName('ullMetaWidgetFCKEditor')
      ->setWidgetOption('CustomConfigurationsPath', '/ullCmsPlugin/js/FCKeditor_config.js')
    ;
    
    if ($this->isListAction())
    {
//      $this->disable(array(
//        'body',
//        'ull_navigation_item_id'
//      ));
      
    }
    
  }
}