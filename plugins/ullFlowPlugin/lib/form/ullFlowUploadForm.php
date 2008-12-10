<?php

class ullFlowUploadForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'file'    => new sfWidgetFormInputFile(),
      'value'   => new sfWidgetFormInputHidden(),
    ));
    
    $this->widgetSchema->setNameFormat('fields[%s]');
  
    $this->setValidators(array(
        'file'  => new sfValidatorFile(),
        'value' => new sfValidatorString(),
    ));
  }
}
