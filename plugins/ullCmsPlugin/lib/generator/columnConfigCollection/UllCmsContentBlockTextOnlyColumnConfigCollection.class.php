<?php 

/**
 * Empty class to be overridden by customer's class in app/...
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllCmsContentBlockTextOnlyColumnConfigCollection extends UllCmsContentBlockColumnConfigCollection
{

  protected function applyCustomSettings()
  {
    parent::applyCustomSettings();
    
    $this->disable(array(
      'parent_ull_cms_item_id',
      'sequence',
      'is_active',
    ));
  }    
  
  
}