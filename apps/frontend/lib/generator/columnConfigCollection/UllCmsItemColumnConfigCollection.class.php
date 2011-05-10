<?php 

/**
 * Custom UllCmsItem columnsConfig
 * Extends/overrides the plugins' columnsConfig
 * 
 * Put your custom config here
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllCmsItemColumnConfigCollection extends BaseUllCmsItemColumnConfigCollection
{

  protected function applyCustomSettings()
  {
    parent::applyCustomSettings();

    //$this->enable(array('preview_image', 'image'));
  }  
  
}