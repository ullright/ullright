<?php

/**
 * ullValidatorPhoneNumber validates a string as a phone number,
 * and performs cleanup. See unit tests for examples.
 */
class ullValidatorPhoneNumber extends sfValidatorRegex
{

  protected function configure($options = array(), $messages = array())
  {
    $this->addOption('default_country_code');
    parent::configure($options, $messages);
    
    // regex pattern for a valid phonenumber
    $pattern = 
      '/' .           // start delimiter
      '^([+]|[00])' . // begin with + or 00
      '([0-9]+)' .    // followed by a digit block = country code 
      '( )' .         // followed by a space
      '([0-9]+)' .    // followed by a digit block = area code
      '( )' .         // followed by a space
      '([0-9]+)' .    // followed by a digit block = base number
      '(-?)' .        // followed optionally by a dash
      '([0-9]*)' .    // followed optionally by a digit block = extension
      '/i' // end delimiter
    ;
    
    $this->setOption('pattern', $pattern);

  }

  protected function doClean($value)
  {
    // cleanup and normalize allowed alternative notation 
    $value = str_replace('(', '', $value);
    $value = str_replace(')', '', $value);
    $value = str_replace('/', ' ', $value);
    
    // remove all dashes except the last one (extension)
    
    // replace the last dash with a replacement string
    $pattern = '/(.+)(-)([0-9]+$)/';  
    $value = preg_replace($pattern, '$1TheLastDash$3', $value);
    // cleanup remaining dashes
    $value = str_replace('-', ' ', $value);
    // recreate the last dash
    $value = str_replace('TheLastDash', '-', $value);

    // normalize alternative "00" country code notation
    if (substr($value, 0, 2) == '00')
    {
      $value = '+' . substr($value, 2);
    }

    // handle default country code 
    $parts = explode(' ', $value);
    if ((substr($value, 0, 1) != '+') && $this->getOption('default_country_code'))
    {
      $value = $this->getOption('default_country_code') . ' ';
    }
    else
    {
      $value = array_shift($parts) . ' ';
    }
    $value .= ltrim(array_shift($parts), '0') . ' ' . implode('', $parts);

    $validatedValue = parent::doClean($value);

    return $validatedValue;
  }
}
