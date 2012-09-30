<?php
    
class UllContentElementTextWithImageColumnConfigCollection extends UllContentElementColumnConfigCollection
{
  protected function applyCustomSettings()
  {
    parent::applyCustomSettings();
    
    $this->disableAllExcept(array());
   
    $this->create('headline')
      ->setLabel('Headline')
      ->setMetaWidgetClassName('ullMetaWidgetString')
      ->setIsRequired(true)
    ;
    
    $this->create('body')
      ->setLabel('Body')
      ->setMetaWidgetClassName('ullMetaWidgetCKEditor')
      ->setWidgetOption('CustomConfigurationsPath', '/ullCorePlugin/js/CKeditor_config.js')
      ->setWidgetAttribute('class', 'ull_cms_content')    
      ->setWidgetOption('width', '400px')
      ->setWidgetOption('height', '150px')      
      ->setIsRequired(true)
    ;    
    
    $this->create('image')
      ->setLabel('Images')
      ->setMetaWidgetClassName('ullMetaWidgetGallery')
      ->setOption('image_width', 800)
      ->setOption('image_height', 800)
      ->setOption('generate_thumbnails', false)
      ->setOption('single', true)
    ;     
    
    
  }  
}