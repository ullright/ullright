<?php

/**
 * ullValidatorGallery cleans empty lines
 * and checks it the files do exists
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
        $filename = ullCoreTools::webToAbsolutePath($trimmed);
        if (file_exists($filename))
        {
          $cleanLines[] = $trimmed;
        }
      }
    }
    
    $value = implode("\n", $cleanLines);
    
    return $value;
  }

}