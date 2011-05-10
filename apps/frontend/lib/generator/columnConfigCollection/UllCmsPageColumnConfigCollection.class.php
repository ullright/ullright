<?php 

/**
 * Custom UllCmsPage columnsConfig
 * Extends/overrides the plugins' columnsConfig
 * 
 * Put your custom config here
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllCmsPageColumnConfigCollection extends BaseUllCmsPageColumnConfigCollection
{
  protected function applyCustomSettings()
  {
    parent::applyCustomSettings();
    
    //$this->enable(array('duplicate_tags_for_search'));
  }
}