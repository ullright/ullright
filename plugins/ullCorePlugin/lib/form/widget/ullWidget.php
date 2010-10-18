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
    
    parent::__construct($options, $attributes);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (is_array($value))
    {
      $value = $value['value'];
    }
    
    $value = $this->handleOptions($value);
    
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
    
    // why do escaping in the widget?!?
    $value = esc_entities($value);
    
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
  
}
