<?php

/**
 * Extends the sfValidatedFile. Resizes an image
 *
 */
class ullValidatedFile extends sfValidatedFile
{
  /**
   * Set the maximum width of an image
   * 
   * @param $imageWidth
   * @return unknown_type
   */
  public function setImageWidth($imageWidth)
  {
    $this->imageWidth = $imageWidth;
  } 
  
  /**
   * Set the maximum height of an image
   * 
   * @param unknown_type $imageHeight
   * @return unknown_type
   */
  public function setImageHeight($imageHeight)
  {
    $this->imageHeight = $imageHeight;
  } 
  
  /**
   * (non-PHPdoc)
   * @see sfValidatedFile#save($file, $fileMode, $create, $dirMode)
   */
  public function save($file = null, $fileMode = 0666, $create = true, $dirMode = 0777)
  {
    if (ullCoreTools::isValidImage($this->getTempName()))
    {
      try{
        $image = new sfImage($this->getTempName(), 'image/png');
        $this->resizePhoto($image);
        $image->save();
      }
      catch (Exception $e)
      {
      }
    }
    return parent::save($file, $fileMode, $create, $dirMode);
  }
  
  /**
   * Resize the photo
   * 
   * @param sfImage $img
   * @return none
   */
  protected function resizePhoto(sfImage $img)
  {
    $maxWidth = $this->imageWidth;
    $maxHeight = $this->imageHeight;
    
    if ($img->getWidth() > $maxWidth || $img->getHeight() > $maxHeight)
    {
      // always comply to the max size
      if ($img->getWidth() > $img->getHeight())
      {
        $img->resize($maxWidth, 0);
      }
      else
      {
        $img->resize(0, $maxHeight);
      }    
    }
  }
}