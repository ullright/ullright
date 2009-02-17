<?php

class BaseUllUserComponents extends sfComponents
{
  /**
   * Checks if there's a user logged in,
   * if yes, retrieves and saves the username.
   */
  public function executeHeaderLogin() 
  {
    $this->username = null;
    
    if ($logged_in_user_id = $this->getUser()->getAttribute('user_id'))
    {
      $user = Doctrine::getTable('UllUser')->find($logged_in_user_id);
      
      if ($user !== null)
      {
        $this->username = $user->username;
      }
    }
  }
  
}
