<?php

/**
 * ullValidatorMobileNumber validates a string as a mobile number,
 * and performs cleanup. See unit tests for examples.
 */
class ullValidatorMobileNumber extends sfValidatorRegex
{

  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->setOption('pattern', '/^([+])([0-9]+)([ ])([0-9]+)([ ])([0-9]+)/i');
  }

  protected function doClean($value)
  {

    $value = str_replace('(', '', $value);
    $value = str_replace(')', '', $value);
    $value = str_replace('/', ' ', $value);

    if (substr($value, 0, 2) == '00')
    {
      $value = '+' . substr($value, 2);
    }

    $parts = explode(' ', $value);
    $value = array_shift($parts) . ' ' . array_shift($parts) . ' ' . implode('', $parts);

    $validatedValue = parent::doClean($value);

    return $validatedValue;
  }
}
