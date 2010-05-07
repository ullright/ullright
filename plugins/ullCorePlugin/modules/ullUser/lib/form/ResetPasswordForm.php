<?php

class ResetPasswordForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'username'  => new sfWidgetFormInput,
    ));
    
    $this->widgetSchema->setNameFormat('resetPassword[%s]');
    $this->widgetSchema->setFormFormatterName('ullTable');
  
    $this->widgetSchema->setLabels(array(
      'username'    => __('Username', null, 'common'),
    ));
    
    $this->setValidators(array(
        'username' => new ullValidatorUsername(
            array(), 
            array(
              'required'    => 'Your username is required!',
            )),
    ));
  }
}
