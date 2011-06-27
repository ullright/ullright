<?php

/**
 * Extends the sfValidatorFile. Resizes an image
 *
 */
class ullValidatorFile extends sfValidatorFile
{
  protected static
    $webImages = array(
      'image/jpeg',
      'image/pjpeg',
      'image/png',
      'image/x-png',
      'image/gif',
    ),
    
    $officeFiles = array(
      'application/msexcel',
      'application/mspowerpoint',
      'application/msword',
      'application/rtf',
      'application/vnd.oasis.opendocument.spreadsheet',
      'application/vnd.oasis.opendocument.presentation',
      'application/vnd.oasis.opendocument.text',
      'text/rtf',
    ),

    $textFiles = array(
      'application/pdf',
      'text/plain',
    ),
    
    $otherFiles = array(
      'application/zip',
    )
  ;    
  
  /**
   * (non-PHPdoc)
   * @see sfValidatorFile#configure($options, $messages)
   */
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options = array(), $messages = array());
    
    $this->addOption('imageWidth');
    $this->addOption('imageHeight');
    $this->addOption('validated_file_class', 'ullValidatedFile');
    
    $this->addOption('mime_categories', array(
      'web_images'    => self::getWebImageMimeTypes(),
      'common_files'  => self::getCommonFileMimeTypes()
    ));    
  }
  
  /**
   * (non-PHPdoc)
   * @see sfValidatorFile#doClean($value)
   */
  protected function doClean($value)
  {
    $file = parent::doClean($value);
    
    $file->setImageWidth($this->getOption('imageWidth'));
    $file->setImageHeight($this->getOption('imageHeight'));
    
    return $file;
  }
  
  public static function getWebImageMimeTypes()
  {
    return self::$webImages;
  }
  
  public static function getOfficeMimeTypes()
  {
    return self::$officeFiles;
  }

  public static function getTextMimeTypes()
  {
    return self::$textFiles;
  }  
  
  public static function getOtherMimeTypes()
  {
    return self::$otherFiles;
  }
  
  public static function getCommonFileMimeTypes()
  {
    return array_merge(
      self::getWebImageMimeTypes(),
      self::getOfficeMimeTypes(),
      self::getTextMimeTypes(),
      self::getOtherMimeTypes()
    );
  }
}
 