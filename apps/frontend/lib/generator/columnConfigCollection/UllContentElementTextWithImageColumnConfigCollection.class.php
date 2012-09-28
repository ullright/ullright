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
      ->setMetaWidgetClassName('ullMetaWidgetstring')
//       ->setMetaWidgetClassName('ullMetaWidgetCKEditor')
//       ->setWidgetOption('CustomConfigurationsPath', '/js/CKeditor_config.js')
//       ->setWidgetAttribute('class', 'ull_cms_content')    
//       ->setWidgetOption('width', '400px')
//       ->setWidgetOption('height', '150px')      
      ->setIsRequired(true)
    ;    
    
    $this->create('image')
      ->setLabel('Image')
      ->setMetaWidgetClassName('ullMetaWidgetstring')
//       ->setMetaWidgetClassName('ullMetaWidgetSimpleUpload')
//       ->setValidatorOption('imageWidth', 243)
//       ->setValidatorOption('imageHeight', 182)
    ;
    
    
  }  
}