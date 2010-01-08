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
    //if a user wants to remove a time entry, the empty
    //select value results in a string like 
    //':30' or '3:' or even ':'.
    //we need to correct this so the doctrine
    //validator does not complain later on.
    
    if (substr($value, 0, 1) == ':')
    {
      //case ':' => '0:0'
      if (strlen($value) == 1)
      {
        $value = null;
      }
      //case ':30' => '0:30' 
      else
      {
        $value = '0' . $value;
      }
    }
    
    //case '3:' => '3:0'
    if (substr($value, -1) == ':')
    {
      $value .= '0';
    }
    
    //only do string validation and time humanization
    //if we have a real timestamp (i.e. not null)       
    if ($value !== null)
    {
      $value = parent::doClean($value);
      $value = ullCoreTools::humanTimeToIsoTime($value);
    }
    
    $validator = new Doctrine_Validator_Time;
    if ($validator->validate($value) === false)
    {
      throw new sfValidatorError($this, 'invalid', array('value' => $value));  
    }
    
    return $value;
  }
  
}
