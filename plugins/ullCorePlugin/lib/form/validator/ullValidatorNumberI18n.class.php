<?php

class ullValidatorNumberI18n extends sfValidatorString
{
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

    if (strval($clean) != $translatedValue)
    {
      throw new sfValidatorError($this, 'invalid', array('value' => $value));
    }

    return $clean;
  }
}
