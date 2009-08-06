<?php

/**
 * ullValidatorMacAddress validates a string as a mac address,
 * also transforms the result to lower case.
 */
class ullValidatorMacAddress extends sfValidatorRegex
{
  
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
    
    $this->setOption('pattern', '/^([0-9a-f]{2}([:-]|$)){6}$/i');
  }

  protected function doClean($value)
  {
    $validatedValue = parent::doClean($value);

    return str_replace('-', ':', strtolower($validatedValue));
  }
}
