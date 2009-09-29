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
    if ($value)
    {
      $photoPath = sfConfig::get('app_ull_photo_upload_path', '/uploads/userPhotos');
      $photo = $photoPath . '/' . $value;
    }
    else
    {
      $photo =   '/ullCoreTheme' . sfConfig::get('app_theme_package', 'NG') .'Plugin/images/nobody.png';
    }
    
    $return = '<img src="' . $photo . '" alt="User photo" />';    
    
    if ($this->getOption('show_edit_link') == true)
    {
      $return .= ' ';
      if (isset($attributes['identifier']))
      {
        $username = UllUserTable::findUsernameById($attributes['identifier']);
        $return .= link_to(__('Edit photo', null, 'ullCoreMessages'), 'ullPhoto/index?username=' . $username);
      }
      else
      {
        $return .= __('Please save once. Afterwards the photo can be edited.', null, 'ullCoreMessages');
      }
        
    }
      
      return $return;    
  } 
  
}