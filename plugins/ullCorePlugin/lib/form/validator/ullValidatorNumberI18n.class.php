<?php

class ullValidatorNumberI18n extends sfValidatorString
{
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
    
    $this->addMessage('max_decimals', '"%value%" has too many decimal places (%max_decimals% max).');

    $this->addOption('max_decimals');
    $this->setOption('max_decimals', '2');
  }
  
  /*
   * See symfony ticket #4071
   */
  protected function doClean($value)
  {
    $value = parent::doClean($value);
    
    $currentCulture = sfContext::getInstance()->getUser()->getCulture();
    $numberFormatInfo = sfNumberFormatInfo::getInstance($currentCulture);
    
    $decimalSeparator = $numberFormatInfo->getDecimalSeparator();
    $groupSeparator = $numberFormatInfo->getGroupSeparator();
    
    $translatedValue = str_replace($groupSeparator, '', $value);
    $translatedValue = str_replace($decimalSeparator, '.', $translatedValue);
    
    $clean = floatval($translatedValue);
    //thanks php.net
    $rightDecPlaces = (($decPos = strpos($clean, '.')) === false) ? 0 : strlen(substr($clean, $decPos + 1));
    
    if ($rightDecPlaces > $this->getOption('max_decimals'))
    {
      throw new sfValidatorError($this, 'max_decimals', array('value' => $value, 'max_decimals' => $this->getOption('max_decimals')));
    }
    
    if (strval($clean) != $translatedValue)
    {
      throw new sfValidatorError($this, 'invalid', array('value' => $value));
    }
    
    //we do not return the converted float value here but the original string
    //to prevent format troubles later on
    return $translatedValue;
  }
}
