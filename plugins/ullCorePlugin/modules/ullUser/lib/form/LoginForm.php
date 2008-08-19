<?php

class LoginForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'username'  => new sfWidgetFormInput,
      'password'  => new sfWidgetFormInputPassword,
      'js_check'  => new sfWidgetFormInputHidden(),
    ));
    
    $this->widgetSchema->setNameFormat('login[%s]');
    $this->widgetSchema->setFormFormatterName('ullTable');
  
    $this->widgetSchema->setLabels(array(
      'username'    => __('Username') . ':',
      'password'    => __('Password') . ':',
    ));
    
    $this->setValidators(array(
        'username' => new sfValidatorString(
            array('min_length' => 4), 
            array(
              'required'    => 'Your username is required!',
              'min_length'  => 'Username must be 4 or more characters',
            )),
        'password' => new sfValidatorString(
            array(),
            array(
              'required'    => 'Your password is required!',
            )),
        'js_check' => new sfValidatorPass(),  //empty validator
    ));

    
  }
}
