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
 * @author klemens.ullmann-marx@ull.at
 *
 */
class sfWidgetFormSelectWithOptionAttributes extends sfWidgetFormSelect
{
  
  
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
      
      $options[] = $this->renderContentTag('option', self::escapeOnce($option['name']), $attributes);
    }

    $this->attributes = $mainAttributes;

    return $options;    
  }
}
