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
        $lastName = substr($lastName, 0, $spaceForLastName) . '.$firstName';
      }
    }  
    
    $parts = array();
    if ($firstName)
    {
      $parts[] = $firstName;
    }
    if ($lastName)
    {
      $parts[] = $lastName;
    }
    $this->display_name = implode(' ', $parts); 
  }
  
  
  /**
   * Check for a future entry date.
   * 
   * If so, deactivate the user for the moment, and schedule the activation for the entry date
   * 
   * Also, reset the bounce counter, if the email address is updated
   * 
   * @see lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/vendor/doctrine/Doctrine/Doctrine_Record#postSave($event)
   */
  public function preSave($event)
  {
    if
    (
      $this->entry_date > date('Y-m-d') //future entry date?
      && $this->UllUserStatus->slug != 'inactive'   // prevent looping
      && !$this->hasMappedValue('scheduled_update_date')  // prevent looping for SuperVersionable behaviour
    )
    {
      $ullUserStatusInactive = Doctrine::getTable('UllUserStatus')->findOneBySlug('inactive');

      // Special handling for dev fixture loading: we don't have UllUserStatus
      // objects yet in the database, so skip the whole functionality
      if ($ullUserStatusInactive)
      {
        //set the user status to inactive and save the record
        //this has the following two (necessary!) side effects:
        //
        // - if it is a new record, it gets inserted
        //   (SuperVersionable does only act on update, not insert)
        //
        // - the scheduled update we create later on will
        //   reference this new version, not the old (unmodified) one
        $this->UllUserStatus = $ullUserStatusInactive;
        $this->save();

        //cause a scheduled update which will set the user to active
        $this->UllUserStatus = Doctrine::getTable('UllUserStatus')->findOneBySlug('active');
        $this->mapValue('scheduled_update_date', $this->entry_date);
        $this->save();

        //someone might continue working with the record
        //reset values to what they should be (i.e., are
        //'now' => an inactive user record)
        $this->UllUserStatus = $ullUserStatusInactive;
        $this->mapValue('scheduled_update_date', null);

        //skip the actual save operation, since we already
        //handled it above
        $event->skipOperation();
      }
    }
    
    //reset bounce counter if the email address is updated
    
    $old = $this->getModified(true);
    if(isset($old['email']) && ($old['email'] != $this['email']))
    {
      $this['num_email_bounces'] = 0;
    }
    
    // Set tags in taggable behaviour
    $this->setTags($this->duplicate_tags_for_search);
    
    $this->createUsername();
  }
  

  /**
   * Auto create a username if none given
   */
  public function createUsername()
  {
    // create username
    if (!$this->username)
    {
      if ($this->first_name || $this->last_name)
      {
        $proposal = $this->first_name . ' ' . $this->last_name;
      }
      else
      {
        $parts = explode('@', $this->email);
        $proposal = $parts[0];
      }  
      
      $proposal = str_replace(
        array('Ä', 'ä', 'Ö', 'ö', 'Ü', 'ü', 'ß'),
        array('Ae', 'ae', 'Oe', 'oe', 'ue', 'ue', 'ss'),
        $proposal
      );
      $proposal = ullCoreTools::sluggify($proposal);
      $proposal = str_replace('-', '_', $proposal);
      while (strstr($proposal, '__'))
      {
        $proposal = str_replace('__', '_', $proposal);
      }
      
      $this->username = $this->createUniqueUsername($proposal);
    }
    
  }
  
  
  /**
   * Create a unique username
   * 
   * @param string $proposal
   * @param mixed $suffix      null or integer
   */
  protected function createUniqueUsername($proposal, $suffix = null)
  {
    if (!$proposal)
    {
      $proposal = 'user';
    }
    
    if (UllUserTable::findIdByUsername($proposal . $suffix))
    { 
      if (!$suffix)
      {
        $suffix = 0;
      }
      
      return $this->createUniqueUsername($proposal, $suffix + 1);
    }
    
    return $proposal . $suffix;
  }
    
  
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
  
  /**
   * Check wether the user is currently logged in
   * 
   * @return boolean
   */
  public function isLoggedIn()
  {
    if ($this->id === sfContext::getInstance()->getUser()->getAttribute('user_id'))
    {
      return true;
    }
    
    return false;
  } 
  

  /**
   * Check if the user is active
   * 
   * @return boolean
   */
  public function isActive()
  {
    return (boolean) $this->UllUserStatus->is_active;
  }
  
  
  /**
   * Sets a user inactive
   * 
   * You have to manually save the record afterwards
   */
  public function setInactive()
  {
    $this->UllUserStatus = Doctrine::getTable('UllUserStatus')->findOneBySlug('inactive');
  }
  
  /**
   * Return the correct format for an email to: field
   * 
   * Example: "Master Admin <admin@example.com>"
   */
  public function getEmailTo()
  {
    return $this->first_name . 
      ' ' .
      $this->last_name . 
      ' <' .
      $this->email .
      '>';
  }
  
}