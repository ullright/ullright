<?php

class LoginForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'username'  => new sfWidgetFormInput,
      'password'  => new sfWidgetFormInputPassword,
      'js_check'  => new sfWidgetFormInputHidden(),
      // Stores POST values of the last request to prevent data loss when the session timed out
      'original_request_params' => new sfWidgetFormInputHidden(),
      // Distinguished between request to the login input and the login procedure itself
      'login_request' => new sfWidgetFormInputHidden(),
    ));
    
    $this->widgetSchema->setNameFormat('login[%s]');
    $this->widgetSchema->setFormFormatterName('ullTable');
  
    $this->widgetSchema->setLabels(array(
      'username'    => __('Username', null, 'common'),
      'password'    => __('Password', null, 'common'),
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
        'original_request_params' => new sfValidatorString(
            array('required'    => false),
            array()),
        'login_request' => new sfValidatorString(
            array('required'    => false),
            array()),
    ));
    
    $this->setDefault('login_request', true);

    
  }
}
