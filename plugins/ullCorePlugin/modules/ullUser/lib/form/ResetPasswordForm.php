<?php

class ResetPasswordForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'username'  => new sfWidgetFormInput,
      'email'    => new sfWidgetFormInput
    ));
    
    $this->widgetSchema->setNameFormat('resetPassword[%s]');
    $this->widgetSchema->setFormFormatterName('ullTable');
  
    $this->widgetSchema->setLabels(array(
      'username'    => __('Username', null, 'common'),
      'email'    => __('Email address', null, 'common')
    ));
    
    $this->setValidators(array(
        'username' => new sfValidatorPass(
            array(), 
            array(
              'required'    => 'Your username is required!',
            )),
         'email' => new sfValidatorEmail(
            array(), 
            array(
              'required'    => 'Your e-mail is required!',
            ))
    ));
  }
}
