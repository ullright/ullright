<?php

/**
 * Photo widget
 * 
 * @author klemens.ullmann@ull.at
 *
 */
class ullWidgetPhoto extends ullWidget
{
  
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('show_edit_link', false);

    parent::configure($options, $attributes);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $id = null;
    
    if (is_array($value))
    {
      $id = $value['id'];
      $value = $value['value'];
    }    
    
    if ($value)
    {
      $photoPath = sfConfig::get('app_ull_photo_upload_path', '/uploads/userPhotos');
      $photo = $photoPath . '/' . $value;
    }
    else
    {
      $photo = sfConfig::get('app_ull_user_nobody_image', 
        '/ullCoreTheme' . sfConfig::get('app_theme_package', 'NG') .'Plugin/images/nobody.png');   
      
      $this->getOption('nobody_image');
    }
    
    $attributes['src'] = $photo;
    $attributes['alt'] = 'User photo';
    
    $return = $this->renderTag('img', $attributes);
    
    if ($this->getOption('show_edit_link') == true)
    {
      $return .= ' ';
      if ($id)
      {
        $username = UllUserTable::findUsernameById($id);
        $return .= ull_link_to(
          __('Edit photo', null, 'ullCoreMessages'), 
          'ullPhoto/index?username=' . $username,
          array('ull_js_observer_confirm' => true)
        );
      }
      else
      {
        $return .= __('Please save once. Afterwards the photo can be edited.', null, 'ullCoreMessages');
      }
        
    }
      
    return $return;    
  } 
  
}