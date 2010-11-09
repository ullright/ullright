<?php

/**
 * Base class for ullFlow emails
 *
 */
class ullFlowMail extends ullsfMail
{
  protected
    $doc,
    $user
  ;
  
  public function __construct(UllFlowDoc $doc, $user = null)
  {
    parent::__construct();
    
    $this->doc = $doc;
    
    if ($user === null) 
    {
      // set Master Admin as default if not logged in
      $userId = sfContext::getInstance()->getUser()->getAttribute('user_id', 1);
      $this->user = Doctrine::getTable('UllUser')->findOneById($userId);
    }
    else
    {
      $this->user = $user;
    }
  }
  
  /**
   * Get current user
   *
   * @return UllUser
   */
  public function getUser()
  {
    return $this->user;
  }
  
  /**
   * Builds and returns the URL to edit the current UllFlowDoc
   *
   * @return string edit URL
   */
  public function getEditLink() 
  {
    return __('Link') .  ': ' . url_for('ullFlow/edit?doc=' . $this->doc->id, true);
  }
}
