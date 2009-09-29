<?php

/**
 * ullValidatorTime
 * 
 * Valid inputs are:
 * 
 * - hours
 * - hours:minutes
 * 
 */
class ullValidatorTime extends sfValidatorString
{
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
    
    $this->setOption('empty_value', null);
  }
  
  
  protected function doClean($value)
  {
    $value = parent::doClean($value);
    
    $value = ullCoreTools::humanTimeToIsoTime($value);
    
    $validator = new Doctrine_Validator_Time;
    if ($validator->validate($value) === false)
    {
      throw new sfValidatorError($this, 'invalid', array('value' => $value));  
    }
    
    return $value;
  }
  
}
