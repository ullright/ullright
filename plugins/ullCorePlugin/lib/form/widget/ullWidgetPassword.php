<?php

/**
 * Special ullright password widget
 * 
 * Hides the actual md5 password and uses "********" instead
 * 
 * @author klemens.ullmann-marx@ull.at
 */
class ullWidgetPassword extends ullWidget
{
  
  /**
   * Constructor
   * 
   * Configures options
   * 
   * @param array $options
   * @param array $attributes
   * @return none
   */
  public function __construct($options = array(), $attributes = array())
  {
    // Compatibility with sfWidgetFormInputPassword
    $this->addOption('always_render_empty', true);
    $this->addOption('render_pseudo_password', false);
    
    parent::__construct($options, $attributes);
  }
  
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/form/widget/ullWidget#render($name, $value, $attributes, $errors)
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if ($value)
    {
      $value = '********';
    }
    else
    {
      $value = '';
    }
    
    return $value;
  }

}
