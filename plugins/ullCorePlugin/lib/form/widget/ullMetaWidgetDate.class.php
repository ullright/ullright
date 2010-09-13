<?php

/**
 * This meta widget represents a date.
 * That includes year/month/date, but no time components (hour/minute/second).
 * 
 * In write mode, ullWidgetDateWrite supports rendering a JS datepicker and
 * multiple options for handling allowed date ranges, validation, ...
 * 
 * In both read and write modes the user's culture is respected. At the
 * moment, only 'de' receives special handling, every other culture is
 * treated neutrally.
 * 
 * Important note: In write mode, the date-limiting options use different
 * syntax for the widget and the validator. The first uses jQuery's
 * datepicker syntax, the second one PHP's strttime.
 * 
 * Example column configuration:
 *  ->setWidgetOption('min_date', '-80y')
 *  ->setWidgetOption('max_date', '0')
 *  ->setValidatorOption('min', strtotime('-80 years midnight'))
 *  ->setValidatorOption('max', strtotime('today'))
 *  
 *  See the ullWidgetDateWrite for detailed option documentation.
 */
class ullMetaWidgetDate extends ullMetaWidget
{
  protected function configureWriteMode()
  {
    if ($this->columnConfig->getWidgetAttribute('size') == null)
    {
      $this->columnConfig->setWidgetAttribute('size', '10');
    }

    //since we are the ullMetaWidgetDate, we can assume error display without time
    $fixedValidatorOptions = array('date_format_range_error' => ull_date_pattern(true, true));
    if ($this->columnConfig->getOption('use_inclusive_error_messages'))
    {
      $fixedValidatorOptions['use_inclusive_error_messages'] = true;
    }
    
    $this->addWidget(new ullWidgetDateWrite($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new ullValidatorDate(array_merge($fixedValidatorOptions, $this->columnConfig->getValidatorOptions())));
  }

  protected function configureReadMode()
  {
    if ($this->columnConfig->getOption('show_weekday'))
    {
      $this->columnConfig->setWidgetOption('show_weekday', true);
    }

    $this->columnConfig->removeWidgetOption('min_date');
    $this->columnConfig->removeWidgetOption('max_date');
    $this->columnConfig->removeWidgetOption('year_range');
    $this->columnConfig->removeWidgetOption('default_date');
    $this->columnConfig->removeWidgetOption('enable_date_picker');

    $this->addWidget(new ullWidgetDateRead($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass());
  }

  public function getSearchType()
  {
    return 'rangeDate';
  }
}
