<?php 

/**
 * Allows giving attributes for the choices
 * 
 * $choices = array(
 *  1 => array(
 *    name = 'My first option'
 *    attributes = array(
 *      style = 'background-color: blue;'
 *    )
 *  )
 * );
 * 
 * Note: currently removes the support for optgroups
 * 
 * Adds support for js search box which filters the select options
 *    
 * @author klemens.ullmann-marx@ull.at
 *
 */
class sfWidgetFormSelectWithOptionAttributes extends sfWidgetFormSelect
{
  
  /**
   * Constructor.
   *
   * @see sfWidgetFormSelect
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);
    
    $this->addOption('show_search_box', false);
//    $this->addOption('show_ull_entity_popup', false);
  }  

  /**
   * @see plugins/ullCorePlugin/lib/vendor/symfony/lib/widget/sfWidgetFormSelect#render()
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $return = '<div style="white-space: nowrap; display: inline;">';
    
    if ($this->getOption('show_search_box') == true)
    {
      if (!$this->getAttribute('name'))
      {
        $this->setAttribute('name', $name);
      }
      
      $this->setAttributes($this->fixFormId($this->getAttributes()));
      $id = $this->getAttribute('id');
      
      $return .= javascript_tag('
$(document).ready(function()
{
  $("#' . $id . '").addSelectFilter();
});
      ');      
    }

    $return .= parent::render($name, $value, $attributes, $errors);
    
    $return .= '</div>';
    
    return $return;
  }  
  
  /**
   * Returns an array of option tags for the given choices
   *
   * @param  string $value    The selected value
   * @param  array  $choices  An array of choices
   *
   * @return array  An array of option tags
   */
  protected function getOptionsForSelect($value, $choices)
  {
//    // compatibility mode for original choices format
//    if (!is_array(reset($choices)))
//    {
//      return parent::getOptionsForSelect($value, $choices);
//    }
    
    $mainAttributes = $this->attributes;
    $this->attributes = array();

    if (!is_array($value))
    {
      $value = array($value);
    }

    $value = array_map('strval', array_values($value));
    $value_set = array_flip($value);
    
    $options = array();
    foreach ($choices as $key => $option)
    {
      if (isset($option['attributes']))
      {
        $attributes = array_merge($option['attributes'], array('value' => self::escapeOnce($key)));
      }
      else
      {
        $attributes = array('value' => self::escapeOnce($key));
      }
      
      if (isset($value_set[strval($key)]))
      {
        $attributes['selected'] = 'selected';
      }
      
      if (isset($option['name']))
      {
        $name = $option['name'];
      }
      else
      {
        $name = '';
      }
      
      $options[] = $this->renderContentTag('option', self::escapeOnce($name), $attributes);
    }

    $this->attributes = $mainAttributes;

    return $options;    
  }
  
  
}
