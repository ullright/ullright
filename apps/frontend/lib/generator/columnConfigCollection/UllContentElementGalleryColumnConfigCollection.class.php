<?php
    
class UllContentElementGalleryColumnConfigCollection extends UllContentElementColumnConfigCollection
{
  protected function applyCustomSettings()
  {
    parent::applyCustomSettings();
   
    $this->create('gallery')
      ->setLabel('Gallery')
      ->setMetaWidgetClassName('ullMetaWidgetGallery')
      ->setOption('image_width', 800)
      ->setOption('image_height', 800)
      ->setOption('generate_thumbnails', false)
      ->setHelp('Drag images around to sort.')
    ;    
  }  
}