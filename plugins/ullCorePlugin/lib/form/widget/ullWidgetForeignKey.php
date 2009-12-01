<?php

class ullWidgetForeignKey extends ullWidget
{
  
  protected function configure($options = array(), $attributes = array())
  {
    // render a input type hidden field in read mode
    $this->addOption('render_additional_hidden_field', false);
    
    $this->addRequiredOption('model');
    $this->addOption('method', '__toString');
    $this->addOption('show_ull_entity_popup', false);
    
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
        //Test different settings
        //->setResultCacheLifeSpan(1)
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
        $return .= $object->$method();
      }
      catch (Exception $e)
      {
        // This is necessary for translated columns. Why?
        $object = Doctrine::getTable($this->getOption('model'))->find($value);
        $return .= $object->$method();
      }
      
      if ($this->getOption('show_ull_entity_popup'))
      {
        $primaryKey = $object->id;
      }

    }
    
    if ($this->getOption('show_ull_entity_popup') == true)
    {
      $uri = 'ullUser/show?username=' . $primaryKey;

      $verticalSize = sfConfig::get('app_ull_user_user_popup_vertical_size', 720);
      
      if (!is_int($verticalSize))
      {
        throw new RuntimeException('user_popup_vertical_size in app.yml must be an integer.');
      }
      
      $return = link_to($return, $uri, array(
        'title' => __('Show business card', null, 'ullCoreMessages'),
        'onclick' => 'this.href="#";popup(
          "' . url_for($uri) . '",
          "Popup ' . $primaryKey . '",
          "width=720,height=' . $verticalSize . ',scrollbars=yes,resizable=yes"
        );'
      )); 
    }    
    
    return $return;
  }
  
}
