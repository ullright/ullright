<?php

/**
 * ullValidatorMacAddress validates a string as a mac address,
 * also transforms the result to lower case.
 */
class ullValidatorUsername extends sfValidatorString
{
  
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
    
    $this->addMessage('wrong_username', __('Username does not exist', null, 'ullCoreMessages'));
  }

  protected function doClean($value)
  {
    $validatedValue = parent::doClean($value);

    $user = Doctrine::getTable('UllUser')->findOneByUsername($validatedValue);
    if (!$user)
    {
      throw new sfValidatorError($this, 'wrong_username');
    }
      
    return $validatedValue;
  }
}
