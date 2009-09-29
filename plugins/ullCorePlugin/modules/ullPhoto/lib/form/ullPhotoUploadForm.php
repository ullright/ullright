<?php
/**
 * form for ullPhoto
 *
 */
class ullPhotoUploadForm extends sfForm
{
  /**
   * Configures the form
   *
   */
  public function configure()
  {
    $this->getWidgetSchema()->setNameFormat('fields[%s]');
    $this->getWidgetSchema()->setFormFormatterName('ullTable');
    
    $this->widgetSchema['photo'] = new sfWidgetFormInputFile(array(
      'label' => __('Photo', null, 'common'),
    ));

    $this->widgetSchema->setHelp('photo', nl2br(__('Select a photo or multiple photos in a zip archive from your computer.
Valid filetypes are jpg and png.', null, 'ullCoreMessages')));
    
    $this->validatorSchema['photo'] = new sfValidatorFile(array(
      'required'   => true,
      'path'       => sfConfig::get('app_ull_photo_upload_path', '/uploads/userPhotos'),
//      'mime_types' => array('web_images', 'application/zip')
    ));
  }
  
  
}
