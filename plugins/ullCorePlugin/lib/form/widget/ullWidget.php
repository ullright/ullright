<?php

/**
 * Base class for all read-only widgets
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullWidget extends sfWidgetForm
{
  public function __construct($options = array(), $attributes = array())
  {
    $this->addOption('suffix');
    $this->addOption('nowrap');
    $this->addOption('decode_mime');
    
    parent::__construct($options, $attributes);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (is_array($value) && isset($value['value']))
    {
      $value = $value['value'];
    }
    
    $value = $this->handleOptions($value);
    
    $value = $this->encloseInSpanTag($value, $attributes);
    
    return (string) $value;
  }
  
  
  /**
   * ullWidgets can modifiy the object here
   * 
   * Example: used for ullWidgetInformationUpdate
   *  
   * @param Doctrine_Record $object
   * @param unknown_type $values
   * @param unknown_type $fieldName
   * @return unknown_type
   */
  public function updateObject(Doctrine_Record $object, $values, $fieldName)
  {
    return $values;
  }

  
  /**
   * Handle common functionality for all ullWidgets
   * 
   * @param string $value
   * @return string
   */
  protected function handleOptions($value)
  {
    $value = $this->handleSuffixOption($value);
    
    //widgets have to escape their output because sfForm
    //is exempted from output escaping
    
    sfContext::getInstance()->getConfiguration()->loadHelpers('Escaping');
    
    $value = esc_entities($value);
    
    $value = $this->handleDecodeMimeOption($value);
    
    $value = $this->handleNowrapOption($value);
    
    return $value;
  }
  
  
  /**
   * Handle suffix option
   * 
   * @param string $value
   * @return string
   */  
  protected function handleSuffixOption($value)
  {
    if ($suffix = $this->getOption('suffix'))
    {
      $value = $value . ' ' . $suffix;
    }
    
    return $value;
  }
  
  
  /**
   * Handle nowrap option
   * 
   * @param string $value
   * @return string
   */  
  protected function handleNowrapOption($value)
  {
    if ($this->getOption('nowrap'))
    {
      $value = '<span style="white-space: nowrap;">' . $value . '</span>';
    } 

    return $value;
  }
  
  
  /**
   * Handle decode_mime option
   
   * @param string $value
   * @return string
   */
  protected function handleDecodeMimeOption($value)
  {
    if ($this->getOption('decode_mime'))
    {
      $value = imap_utf8($value);
    }
    
    return $value;
  } 
  
  /**
   * Enclose the value in span tags to make use of the widget's attributes
   * 
   * Only for id or class attribute
   * 
   * @param string $value
   * @param array $attributes
   * @return string
   */
  protected function encloseInSpanTag($value, $attributes)
  {
    // merge custom attributes with constructor attributes
    $attributes = array_merge($this->attributes, $attributes);
    
    $spanAttributes = array();
    
    // whitelist attributes which make sense for the span enclosement
    foreach ($attributes as $attribute => $attributeValue)
    {
      if (in_array($attribute, array('id', 'class')))
      {
        $spanAttributes[$attribute] = $attributeValue;
      }
    }
    
    $value = '<span' . 
      implode('', array_map(array($this, 'attributesToHtmlCallback'), array_keys($spanAttributes), array_values($spanAttributes))) .
      '>' .
      $value .
      '</span>'
    ;
    
    return $value;
  }
  
}
