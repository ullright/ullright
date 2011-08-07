<?php

class ullWidgetCourseStatus extends ullWidget
{
  public function __construct($options = array(), $attributes = array())
  {
    $this->addOption('model', 'UllCourseStatus');
    
    parent::__construct($options, $attributes);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (is_array($value))
    {
      $primaryKey = $value['id'];
    }
    
    $attributes = $this->handleSlugAsHtmlClass($primaryKey, $attributes);
    
    return parent::render($name, $value, $attributes, $errors);
  }  
  
  protected function handleSlugAsHtmlClass($primaryKey, $attributes)
  {
    $model = $this->getOption('model');
    
    $q = new Doctrine_Query;
    $q
      ->from($model)
      ->select('slug')
      ->where('id = ?', $primaryKey)
    ;
    
    $result = $q->fetchOne(null, Doctrine::HYDRATE_NONE);
    
    $attributes['class'] = sfInflector::underscore($model) . '_' . str_replace('-', '_', $result[0]);
    
    return $attributes;
  }    
}