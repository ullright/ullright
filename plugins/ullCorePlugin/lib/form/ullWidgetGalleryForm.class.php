<?php
/**
 * Form for ullWidgetGallery
 *
 */
class ullWidgetGalleryForm extends sfForm
{
  /**
   * Configures the form
   *
   */
  public function configure()
  {
    $this->getWidgetSchema()->setNameFormat('fields[%s]');
    
    $this->widgetSchema['file'] = new sfWidgetFormInputFile(array(
    ));

    $this->validatorSchema['file'] = new sfValidatorFile(array(
      'required'   => true,
      'path'       => sfConfig::get('sf_web_dir') . '/uploads/xxx',
      //'mime_types' => array('web_images', 'application/zip')
    ));
  }
  
  
}
