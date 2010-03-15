<?php

class ullWidgetForeignKey extends ullWidget
{
  
  protected function configure($options = array(), $attributes = array())
  {
    $this->addRequiredOption('model');
    $this->addOption('method', '__toString');
    // render a input type hidden field in read mode
    $this->addOption('render_additional_hidden_field', false); 
    // deprecated!  
    $this->addOption('show_ull_entity_popup', false);
    $this->addOption('link_name_to_popup', false);
    $this->addOption('link_icon_to_popup', false);
    // Example: 'ullUser/edit?id=%d' 
    // overrides 'link_name_to_popup'
    $this->addOption('link_name_to_url', false);
    
    parent::configure($options, $attributes);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $return = '';
    
    if ($this->getOption('render_additional_hidden_field'))
    {
      $attributes['type'] = 'hidden';
      $hiddenField = new sfWidgetFormInput(array(), $attributes);
      $return .= $hiddenField->render($name, $value, $attributes, $errors);  
    }
    
    if (empty($value))
    {
      return $return;
    }
    
    if (is_array($value))
    {
      $primaryKey = $value['id'];
      $value = $value['value'];
      
      $value = $this->handleOptions($value);
      
      $return .= $value;
    }
    else
    {
      //This is a temporary solution to reduce the
      //query count in ullVentory list view.
      $q = new Doctrine_Query();
      $q
        ->from($this->getOption('model') . ' x')
        ->where('x.' . implode(' = ? AND x.', (array) Doctrine::getTable($this->getOption('model'))->getIdentifier()) . ' = ?', $value)
        ->useResultCache(true)
      ;
  
      $object = $q->fetchOne();
      
      // Don't die with an invalid id
      if (!$object)
      {
        return 'Invalid id: ' . $value;
      }
  
      $method = $this->getOption('method');
  
      try
      {
        $return .= esc_entities($object->$method());
      }
      catch (Exception $e)
      {
        // This is necessary for translated columns. Why?
        $object = Doctrine::getTable($this->getOption('model'))->find($value);
        $return .= esc_entities($object->$method());
      }
      
      if ($this->getOption('show_ull_entity_popup'))
      {
        $primaryKey = $object->id;
      }

    }
    
    // POPUP
    if (
      $this->getOption('show_ull_entity_popup') ||
      $this->getOption('link_name_to_popup') ||
      $this->getOption('link_icon_to_popup') ||
      $this->getOption('link_name_to_url')
    )
    {
      if ($url = $this->getOption('link_name_to_url'))
      {
        $return = link_to($return, sprintf($url, $primaryKey));
      }
      else
      {
        $return = ull_link_entity_popup($return, $primaryKey);
      }

      if ($this->getOption('link_icon_to_popup'))
      {
        $return .= ull_link_entity_icon_popup($primaryKey);
      }
    }    
    
    return $return;
  }
  
}
