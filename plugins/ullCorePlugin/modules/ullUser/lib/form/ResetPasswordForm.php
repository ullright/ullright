<?php

class ResetPasswordForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'email'    => new sfWidgetFormInput
    ));
    
    $this->widgetSchema->setNameFormat('resetPassword[%s]');
    $this->widgetSchema->setFormFormatterName('ullTable');
  
    $this->widgetSchema->setLabels(array(
      'email'    => __('Email address', null, 'common')
    ));
    
    $this->setValidators(array(
         'email' => new sfValidatorEmail(
            array(), 
            array(
              'required'    => 'Your e-mail is required!',
            ))
    ));
  }
}
