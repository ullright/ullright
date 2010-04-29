<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class PluginUllUser extends BaseUllUser
{
  
  /**
   * Record hook to generate the display_name
   *
   * @param unknown_type $event
   */
  public function preInsert($event)
  {
    $this->preUpdate($event);    
  }
  
  /**
   * Record hook to generate the display_name
   *
   * @param unknown_type $event
   */
  public function preUpdate($event)
  {
    $firstName  = $this->first_name;
    $lastName   = $this->last_name;
    
    $this->last_name_first = trim($lastName . ' ' . $firstName);
    
    $firstNameLength = strlen($firstName);
    $lastNameLength = strlen($lastName);
    
    $limit = sfConfig::get('app_ull_user_display_name_length_limit', 22);
    $firstNameLimit = sfConfig::get('app_ull_user_display_name_first_name_length_limit', 10);
    
    if ($firstNameLength + $lastNameLength > $limit)
    { 
      if ($firstNameLength > $firstNameLimit)
      {
        $firstName = substr($firstName, 0, $firstNameLimit) . '.';
        $firstNameLength = strlen($firstName);
      }
      
      $spaceForLastName = $limit - $firstNameLength;
      
      if ($lastNameLength > $spaceForLastName)
      {
        $lastName = substr($lastName, 0, $spaceForLastName) . '.';
      }
    }  
    
    $this->display_name = $firstName . ' ' . $lastName;
  }
  
  
  /**
   * Check for a future entry date.
   * 
   * If so, deactivate the user for the moment, and schedule the activation for the entry date
   * 
   * @see lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/vendor/doctrine/Doctrine/Doctrine_Record#postSave($event)
   */
  
  // an issue with editing the entry_date must be fixed before activating
  
//  public function postSave($event)
//  {
//    if (
//      $this->entry_date > date('Y-m-d') 
//      && $this->UllUserStatus->slug != 'inactive'   // prevent looping 
//      && !$this->hasMappedValue('scheduled_update_date')  // prevent looping for superversionable behaviour
//    )
//    {
//      
//      $ullUserStatusInactive = Doctrine::getTable('UllUserStatus')->findOneBySlug('inactive');
//
//      // Special handling for dev fixture loading: we don't have UllUserStatus
//      // objects yet in the database, so skip the whole functionality
//      if ($ullUserStatusInactive)
//      {
//        $this->UllUserStatus = $ullUserStatusInactive;
//        $this->save();
//        
//        $this->UllUserStatus = Doctrine::getTable('UllUserStatus')->findOneBySlug('active');
//        $this->mapValue('scheduled_update_date', $this->entry_date);
//        $this->save();
//      }
//    }  
//  }
  
  /**
   * get User's Shortname
   *
   * @return string
   */
  public function getShortName() {

    $return = '';

    $first_name = substr($this->getFirstName(), 0, 1);

    if ($first_name) {
      $return .= $first_name . '.';
    }

    $last_name = substr($this->getLastName(), 0, 7);
    if (strlen($this->getLastName()) > 7) {
       $last_name .= '.';
    }

    $return .= $last_name;

    return $return;
  }
  
  public function getPhoto()
  {
    //overridePhotoAccessor is a mapped value
    return ($this->_get('is_photo_public') === false && !isset($this->overridePhotoAccessor))
      ? null : $this->_get('photo');
  }
  
  public function getPhoneExtension()
  {
    //overrideContactDataAccessor is a mapped value
    if ($this->_get('is_show_extension_in_phonebook') === false && !isset($this->overrideContactDataAccessor))
    {
      $alternativeExtension = $this->_get('alternative_phone_extension');
      return !empty($alternativeExtension) ? $alternativeExtension : null;
    }
    
    return $this->_get('phone_extension');
  }
  
 public function getMobileNumber()
  {
    //overrideContactDataAccessor is a mapped value
    return ($this->_get('is_show_mobile_number_in_phonebook') === false && !isset($this->overrideContactDataAccessor))
      ? null : $this->_get('mobile_number');
  }
}