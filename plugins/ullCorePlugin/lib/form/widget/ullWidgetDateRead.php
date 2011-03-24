<?php

/**
 * This class renders a date in a read only context, respecting
 * user culture in the process.
 * 
 * Options:
 * show_weekday - adds the weekday the date represents
 * show_only_year - displays only the year part of the date 
 * add_span_if_before - if date is before key: timestamp, value: class_name is used for a span-wrap
 */
class ullWidgetDateRead extends ullWidget
{
  public function __construct($options = array(), $attributes = array())
  {
    $this->addOption('show_weekday', false);
    $this->addOption('show_only_year', false);
    $this->addOption('add_span_if_before');
    
    parent::__construct($options, $attributes);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    //ull_format_date renders the current date when $value is NULL
    //but we actually need an empty field
    
    if (!$value)
    {
      return '';
    }
    
    if ($this->getOption('show_only_year'))
    {
      $value = date('Y', strtotime($value));
    }
    else
    {
      $value = ull_format_date($value, true, $this->getOption('show_weekday'));
    }
    
    $dates = $this->getOption('add_span_if_before');
    
    if (is_array($dates) && !empty($dates))
    {
      if (!isset($attributes['class']))
      {
        $attributes['class'] = '';
      }
      
      //ascending dates
      ksort($dates);
      foreach ($dates as $dateTimestamp => $dateClass)
      {
        if (strtotime($value) < $dateTimestamp)
        {
          $attributes['class'] .= ' ' . $dateClass;
          break;
        }
      }
    }
    
    $value = $this->encloseInSpanTag($value, $attributes);
    return $value;
  } 
}