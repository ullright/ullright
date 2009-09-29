<?php

/**
 * ullMetaWidgetUllPhoto
 *
 *
 * available options:
 * none
 */
class ullMetaWidgetPhoto extends ullMetaWidget
{

  protected function configureReadMode()
  {
    $this->addWidget(new ullWidgetPhoto());
    $this->addValidator(new sfValidatorPass());
  }
  
  protected function configureWriteMode()
  {
    $this->addWidget(new ullWidgetPhoto(array('show_edit_link' => true)));
    $this->addValidator(new sfValidatorPass());
  }
  
  public function getSearchType()
  {
    return 'string';
  }
  
}