<?php

/**
 * ullValidatorTimeDuration
 * 
 * Valid inputs are:
 * 
 * - hours
 * - hours:minutes
 * - hours,hour cents
 * - hours.hour cents
 * 
 */
class ullValidatorTimeDuration extends sfValidatorInteger
{
  
  protected function doClean($value)
  {
    //if the user wants to remove a value, allow it
    //without this, empty fields result in zero, not null
    if ($value == ':')
    {
			//handle this immediately to prevent vacuous message
			//from parent validator
      if ($this->options['required'])
      {
        throw new sfValidatorError($this, 'required');
      }
      else
      {
        return null;
      }
    }
    
    $value = str_replace(',', '.', $value);
    
    if (strstr($value, ':'))
    {
      $parts = explode(':', $value);
      $value = $parts[0] * 3600 + $parts[1] * 60;
    }
    elseif (strstr($value, '.'))
    {
      $value = 3600 * floatval($value);
    }
    else
    {
      $value = $value * 3600;
    }

    return parent::doClean($value);
  }
}
