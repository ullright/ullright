<?php

class ullWidgetTimeWrite extends sfWidgetFormInput
{
  
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/vendor/symfony/lib/widget/sfWidgetFormInput#configure($options, $attributes)
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);

    // replace time input field with js select boxes?
    $this->addOption('show_select_boxes', true);
    
    // configure the minute format. e.g. fragmentation = 15min results in list 0, 15, 30, 45 min. 
    $this->addOption('fragmentation', 15);
    
    // enable start and end hours, both inclusive 
    $this->addOption('startHour', 0);
    $this->addOption('endHour', 23);
  }
    
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/vendor/symfony/lib/widget/sfWidgetFormInput#render($name, $value, $attributes, $errors)
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (!$this->getAttribute('name'))
    {
      $this->setAttribute('name', $name);
    }
    $this->setAttributes($this->fixFormId($this->getAttributes()));
    $id = $this->getAttribute('id');
    
    $return = '';
    
    // replace time input field with js select boxes?
    if ($this->getOption('show_select_boxes'))
    {
      $return .= javascript_tag('
$(document).ready(function()
{
  $("#' . $id . '").replaceTimeDurationSelect(' . $this->getOption('fragmentation') .
      ', ' . $this->getOption('startHour') . ', ' . $this->getOption('endHour') . ');
});
      ');
    }
    
    if ($value && !$errors)
    {
      $value = ullCoreTools::isoTimeToHumanTime($value);
    }
    
    $return .= parent::render($name, $value, $attributes, $errors);
          
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
      '/ullCorePlugin/js/jq/jquery.replace_time_duration_select.js',
    );   
  }  
}