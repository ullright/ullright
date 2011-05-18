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
  }
  
  
}
