<?php

/**
 * ullWidgetLink2
 *
 * Supply the following widgetOptions:
 *   'name'    column name to display the link name
 *   'uri'     symfony uri containing strtr variables 
 *             Example: 'myModule/myAction?slug=%slug%'
 *   'params'  strtr params for the uri. The value has to be a column name of the given model 
 *             Example: array('%slug%' => 'slug')
 */
class ullWidgetLink2 extends ullWidget
{
  
  public function __construct($options = array(), $attributes = array())
  {
    $this->addOption('name');
    $this->addRequiredOption('model');
    $this->addRequiredOption('uri');
    $this->addRequiredOption('params');
  
    parent::__construct($options, $attributes);
  }  
  
 
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (is_array($value))
    {
      $id = $value['id'];
      $value = $value['value'];
    }
    
    if ($value)
    {
      $name = $this->getOption('name');
      $model = $this->getOption('model');
      $uri = $this->getOption('uri');
      $params = $this->getOption('params');
      
      $object = Doctrine::getTable($model)->findOneById($id);
      
      if ($name)
      {
        $label = $object->$name;
      }
      else
      {
        $label = parent::render($name, $value, $attributes, $errors);
      }
      
      $params = $this->fillInParams($params, $object);
      
      $link = strtr($uri, $params);
      
      return link_to($label, $link);
    }
    
  }
  
  /**
   * Get data for the current record
   * 
   * @param array $params
   * @param Doctrine_Record $object
   */
  protected function fillInParams($params, Doctrine_Record $object)
  {
    foreach ($params as $key => $value)
    {
      $params[$key] = $object->$value;
    }
    
    return $params;
  }  

}
