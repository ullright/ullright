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
    
    // POPUP
    if (
      $this->getOption('show_ull_entity_popup') ||
      $this->getOption('link_name_to_popup') ||
      $this->getOption('link_icon_to_popup') ||
      $this->getOption('link_name_to_url')
    )
    {
      $popupUri = 'ullUser/show?username=' . $primaryKey;

      $verticalSize = sfConfig::get('app_ull_user_user_popup_vertical_size', 720);
      
      if (!is_int($verticalSize))
      {
        throw new UnexpectedValueException('user_popup_vertical_size in app.yml must be an integer.');
      }
      
      if ($url = $this->getOption('link_name_to_url'))
      {
        $return = link_to($return, sprintf($url, $primaryKey));
      }
      else
      {
        $return = link_to($return, $popupUri, array(
          'title' => __('Show business card', null, 'ullCoreMessages'),
          'onclick' => 'this.href="#";popup(
            "' . url_for($popupUri) . '",
            "Popup ' . $primaryKey . '",
            "width=720,height=' . $verticalSize . ',scrollbars=yes,resizable=yes"
          );'
        ));
      }

      if ($this->getOption('link_icon_to_popup'))
      {
        $icon = '/ullCoreTheme' . sfConfig::get('app_theme_package', 'NG') .
           'Plugin/images/ull_user_16x16';
        $return .= link_to(image_tag($icon, array('class' => 'ull_user_popup_icon')), $popupUri, array(
          'title' => __('Show business card', null, 'ullCoreMessages'),
          'onclick' => 'this.href="#";popup(
            "' . url_for($popupUri) . '",
            "Popup ' . $primaryKey . '",
            "width=720,height=' . $verticalSize . ',scrollbars=yes,resizable=yes"
          );'
        ));        
      }
    }    
    
    return $return;
  }
  
}
