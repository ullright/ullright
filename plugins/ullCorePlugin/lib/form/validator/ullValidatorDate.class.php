<?php

/**
 * This validator extends sfValidatorDate and enhances the parsing
 * of numeric values. While the original symfony validator
 * interprets such values as unix timestamps, this validator assumes
 * human dates without separators, e.g. 19102020 = 19.10.2020
 * 
 * Note: Relies on ull_parse_date_without_separators which uses
 * a function not available on Windows systems. PHP 5.3.0
 * would provide alternatives.
 */
class ullValidatorDate extends sfValidatorDate
{
  
  /**
   * Overrides the handling of numerical values when checking
   * a date to provide support for human dates without separators.
   * 
   * If the numeric value cannot be parsed by ull_parse_date_without_separators,
   * a sfValidatorError is thrown.
   * 
   * If the value is not numeric, parent::doClean is called.
   * 
   * @param string $value the value to clean
   * @return string the cleaned value
   */
  protected function doClean($value)
  {
    if (is_numeric($value))
    {
      //see function doc for compatibility issues
      $date = ull_parse_date_without_separators($value);
      
      //was the value parsed completely?
      if (is_array($date) && empty($date['unparsed']))
      {
        //PHP 5.3.0 would provide better functions
        $timestamp = mktime(0, 0, 0, 1, $date['tm_yday'] + 1, $date['tm_year'] + 1900);
        return date($this->getOption('date_output'), $timestamp);
      }
      else
      {
        throw new sfValidatorError($this, 'invalid', array('value' => $value));
      }
    }
    
    return parent::doClean($value);
  }
}
