<?php

/**
 * Extends the sfValidatorFile. Resizes an image
 *
 */
class ullValidatorFile extends sfValidatorFile
{
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
}
 