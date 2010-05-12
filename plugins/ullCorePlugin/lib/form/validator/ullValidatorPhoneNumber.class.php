<?php

/**
 * ullValidatorPhoneNumber validates a string as a phone number,
 * and performs cleanup. See unit tests for examples.
 */
class ullValidatorPhoneNumber extends sfValidatorRegex
{

  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->setOption('pattern', '/^([+]|[00])([0-9]+)([ |-|\/])([0-9]+)([ |-|\/])([0-9]+)$/i');
  }

  protected function doClean($value)
  {

    $value = str_replace('(', '', $value);
    $value = str_replace(')', '', $value);
    $value = str_replace('/', ' ', $value);
    $value = str_replace('-', ' ', $value);

    if (substr($value, 0, 2) == '00')
    {
      $value = '+' . substr($value, 2);
    }

    $parts = explode(' ', $value);
    if(substr($value, 0, 1) != '+')
    {
      $value = sfConfig::get('app_ull_user_phone_country_code') . ' ';
      $parts[0] = substr($parts[0], 1);
    }
    else
    {
      $value = array_shift($parts) . ' ';
    }
    $value .= array_shift($parts) . ' ' . implode('', $parts);

    $validatedValue = parent::doClean($value);

    return $validatedValue;
  }
}
