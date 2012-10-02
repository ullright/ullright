<?php

/**
 * ullValidatorGallery cleans empty lines
 */
class ullValidatorGallery extends sfValidatorString
{
  
  protected function doClean($value)
  {
    $value = parent::doClean($value);
    
    $lines = ullWidgetGalleryWrite::getImagesAsArray($value);
    
    $cleanLines = array();
    
    foreach ($lines as $line)
    {
      if ($trimmed = trim($line))
      {
        $cleanLines[] = $trimmed;
      }
    }
    
    $value = implode("\n", $cleanLines);
    
    return $value;
  }

}