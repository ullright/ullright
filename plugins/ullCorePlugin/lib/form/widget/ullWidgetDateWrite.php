<?php

/**
 * This widget renders a JS-datepicker provided by jQuery UI
 * 
 * Options:
 * 
 * 'year_range' specifies the range of years selectable:
 *   '-nn:+nn'   - relative to today's year
 *   'c-nn:c+nn' - relative to the currently selected year
 *   'nnnn:nnnn' - absolute
 *   'nnnn:-nn'  - combinations are allowed
 *   The default is c-10:c+10.
 *   Note: This does not limit the date, it only influences the
 *   list of selectable years. Use min_ and max_date for
 *   restricting user input.
 * 
 * 'min_date' specifies the minimum allowed/selectable date:
 * 'max_date' specifies the maximum allowed/selectable date:
 *   '-3y -2m' - string of periods from today (minus 3 years and 2 months)
 * 'new Date(...)' a JS Date (constructed from multiple values, milliseconds, ...)
 * The default for both is null (= not set).
 * 
 * 'default_date' sets the date which is selected in the datepicker by default.
 *   If this option is not set, the current date is used.
 *   An example where this option makes sense is the 'birthday' field.
 *   Format is same as for min_date and max_date.
 *   Important note: This option will only take effect if the widget
 *   does not receive a value (i.e empty fields, create actions, ...)
 *
 * At the moment, this widget only supports English and German.
 */
class ullWidgetDateWrite extends sfWidgetFormInput
{
  
  /**
   * Constructor
   * 
   * @param array $options
   * @param array $attributes
   */
  public function __construct($options = array(), $attributes = array())
  {
    $this->addOption('enable_date_picker', true);
    //10 years +/- to currently selected date
    $this->addOption('year_range', 'c-10:c+10');
    $this->addOption('max_date');
    $this->addOption('min_date');
    $this->addOption('default_date');
    
    parent::__construct($options, $attributes);
  }

  
  /**
   * Render
   * 
   * @param unknown_type $name
   * @param unknown_type $value
   * @param unknown_type $attributes
   * @param unknown_type $errors
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    // Get field html id
    if (!$this->getAttribute('name'))
    {
      $this->setAttribute('name', $name);
    }
    $this->setAttributes($this->fixFormId($this->getAttributes()));
    $id = $this->getAttribute('id');
    
    $return = parent::render($name, $value, $attributes, $errors);
    
    if ($this->getOption('enable_date_picker'))
    {
      // Put options in quotes
      // we need to fix min, max and default dates in
      // case of 'new Date()...', where surrounding ' are
      // not allowed
      // and then we generate our datepicker options
      $datepickerOptions = '';
      $varNames = array(
        'min_date' => 'minDate',
        'max_date' => 'maxDate',
        'default_date' => 'defaultDate'
      );
      
      foreach($varNames as $optionName => $varName)
      {
        $option = $this->getOption($optionName);
        if ($option != null)
        {
          //make this string/object detection more reliable?
          if (!(strpos($option, 'new Date(') === 0))
          {
            $option =  '\'' . $option . '\'';
          }
          $datepickerOptions .= $varName . ': ' . $option . ', ';
        }
      }
  
      // use this value with care:
      // strtotime pays respect to the timezone set at the server,
      // but e.g. new Date(ms) does not,
      // so we correct with the offset to UTC
      // (we could probably do this more simple directly with DateTime)
      $timestamp = strtotime($value);
      $dateTime = new DateTime();
      // Add timezone offset
      $utcTime = $timestamp + $dateTime->getOffset();
      
      //if this is a postback with invalid input, don't set the date
      if (!empty($errors))
      {
        $datepickerSetDate = '';
      }
      else
      {
        //did the widget receive a value? if not, use empty string (= today)
        if ($timestamp == 0)
        {
          $datepickerSetDate = '';
        }
        else
        {
          $datepickerSetDate = '$("#' . $id . '").datepicker("setDate", new Date(' . ($utcTime * 1000) . '));';
        }
      }
      
      
      //showAnim: \'\', for firefox ?
      $return .= javascript_tag('

$(function() {
  $("#' . $id . '").datepicker({
    changeYear: true,
    yearRange: "' . $this->getOption('year_range') . '",
    changeMonth: true,
    firstDay: 1,
    showOn: \'button\',
    ' . $datepickerOptions . '
  }); 
  
  ' . $datepickerSetDate . '
});

  // KU 2011-06-01 what ist this?
  ' . $id . '_initial_date = \'' . (($timestamp == 0) ? '' : ull_format_date($value, true)) . '\';

');
  
      $culture = sfContext::getInstance()->getUser()->getCulture();
      $culture_parts = explode('_', $culture);
      $language = $culture_parts[0];
  
      switch ($language)
      {
        case 'de':
        $return .= '<script type="text/javascript">' .
        '$.datepicker.regional[\'de\']' . <<<EOF
 = {clearText: 'löschen', clearStatus: 'aktuelles Datum löschen',
            closeText: 'schließen', closeStatus: 'ohne Änderungen schließen',
            prevText: '&#x3c;zurück', prevStatus: 'letzten Monat zeigen',
            nextText: 'Vor&#x3e;', nextStatus: 'nächsten Monat zeigen',
            currentText: 'heute', currentStatus: '',
            monthNames: ['Januar','Februar','März','April','Mai','Juni',
            'Juli','August','September','Oktober','November','Dezember'],
            monthNamesShort: ['Jan','Feb','Mär','Apr','Mai','Jun',
            'Jul','Aug','Sep','Okt','Nov','Dez'],
            monthStatus: 'anderen Monat anzeigen', yearStatus: 'anderes Jahr anzeigen',
            weekHeader: 'Wo', weekStatus: 'Woche des Monats',
            dateFormat: 'dd.mm.yy',
            dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
            dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],
            dayNamesMin: ['So','Mo','Di','Mi','Do','Fr','Sa'],
            dayStatus: 'Setze DD als ersten Wochentag', dateStatus: 'Wähle D, M d',
            initStatus: 'Wähle ein Datum', isRTL: false};
            
EOF
             . '$.datepicker.setDefaults($.datepicker.regional[\'de\']);'
             . '</script>';
               break;
   
        default: 
      }
    } // end of js datepicker code
        
    return $return;
  }
  
  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
  public function getJavaScripts()
  {
    return array(
      '/ullCorePlugin/js/jq/jquery-min.js', 
      '/ullCorePlugin/js/jq/jquery-ui-min.js'
    );   
  }
  
  /**
   * Gets the stylesheet paths associated with the widget.
   *
   * The array keys are files and values are the media names (separated by a ,):
   *
   *   array('/path/to/file.css' => 'all', '/another/file.css' => 'screen,print')
   *
   * @return array An array of stylesheet paths
   */  
  public function getStylesheets()
  {
    return array(
      '/ullCorePlugin/css/jqui/jquery-ui.css' => 'all'
    );  
  }
}
