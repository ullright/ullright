<?php

class ullWidgetForeignKey extends sfWidgetFormInput
{
  
  protected function configure($options = array(), $attributes = array())
  {
    // render a input type hidden field in read mode
    $this->addOption('render_additional_hidden_field', false);
    
    $this->addRequiredOption('model');
    $this->addOption('method', '__toString');
    
    parent::configure($options, $attributes);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $return = '';
    
    if ($this->getOption('render_additional_hidden_field'))
    {
      $attributes['type'] = 'hidden';
      $return .= parent::render($name, $value, $attributes, $errors);  
    }
    
    if (empty($value))
    {
      return $return;
    }
    
    //This is a temporary solution to reduce the
    //query count in ullVentory list view.
    $q = new Doctrine_Query();
    $q
      ->from($this->getOption('model') . ' x')
      ->where('x.' . implode(' = ? AND x.', (array) Doctrine::getTable($this->getOption('model'))->getIdentifier()) . ' = ?', $value)
      ->useResultCache(true)
      //Test different settings
      //->setResultCacheLifeSpan(1)
    ;

    $object = $q->fetchOne();

    $method = $this->getOption('method');

    try
    {
      $return .= $object->$method();
    }
    catch (Exception $e)
    {
      // This is necessary for translated columns. Why?
      $object = Doctrine::getTable($this->getOption('model'))->find($value);
      $return .= $object->$method();
    }
    
    return $return;
  }
  
}
