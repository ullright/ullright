<?php

class ullWidgetDateWrite extends sfWidgetForm
{

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (!$this->getAttribute('name'))
    {
      $this->setAttribute('name', $name);
    }
    
    $this->setAttributes($this->fixFormId($this->getAttributes()));
    $id = $this->getAttribute('id');

    $curdate = strtotime($value);
    $dateline = ($curdate == 0) ? '' : '$("#' . $id . '").datepicker("setDate", new Date('. ($curdate * 1000) . '));';
    
    $return = '
    <script type="text/javascript">
    $(function() {
     $("#' . $id . '").datepicker({
        changeYear: true,
        changeMonth: true,
        firstDay: 1,
        showOn: \'button\',
     });' . 
     $dateline .
    '});
    
    ' . $id . '_initial_date = \'' . (($curdate == 0) ? '' : ull_format_date($value, true)) . '\';
    
    </script>';

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

    $return .= $this->renderTag('input',
      array_merge(array('type' => 'text', 'name' => $name, 'value' => $value), $attributes));
          
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
