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
 * 
 * Also adds support for 'inclusive' error messages, e.g.
 * 'max or earlier' instead of 'before max'. Set use_inclusive_error_messages
 * to true if this behavior is desired.
 */
class ullValidatorDate extends sfValidatorDate
{
  /**
   * Returns a new ullValidatorDate instance.
   *
   * @param array $options validator options
   * @param array $messages validator messages
   * @param boolean 
   */
  public function __construct($options = array(), $messages = array())
  {
    $this->addOption('use_inclusive_error_messages', false);
    
    parent::__construct($options, $messages);
    
    if (!empty($options['use_inclusive_error_messages']))
    {
      unset($options['use_inclusive_error_messages']);
      //instead of:
      //$this->addMessage('max', 'The date must be before %max%.');
      //$this->addMessage('min', 'The date must be after %min%.');
      $this->setMessage('max', __('The date must be %max% or earlier.', null, 'common'));
      $this->setMessage('min', __('The date must be %min% or later.', null, 'common'));
    }
  }
  
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
