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
