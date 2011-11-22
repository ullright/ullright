<?php

/**
 * This class is a plugin for Swift Mailer which
 * which personalises entities in square brakets for the recipient
 * 
 * Currently supported are
 *  * [UNSUBSCRIBE] - A unsubscribe link for ullright
 *  * [ULL_USER_FIELD] - e.g. [LAST_NAME] a UllUser field
 * 
 */
class Swift_Plugins_ullPersonalizePlugin
  extends Swift_Plugins_ullPrioritizedPlugin
  implements Swift_Events_SendListener
{ 
  /**
   * @param Swift_Events_SendEvent $evt
   */
  public function beforeSendPerformed(Swift_Events_SendEvent $evt)
  {
    $mail = $evt->getMessage();
    
    if (
      // Only ullsfMails can support this
      $mail instanceof ullsfMail
      // The plugin was already executed before putting into the queue
      && $mail->getIsQueued() == false 
      // We can only perfom personalization when we have a single UllUser recipient
      && $mail->getRecipientUllUserId())
    {
      sfContext::getInstance()->getConfiguration()->loadHelpers(
        array('I18N', 'Url', 'Tag')
      );
      
      ullCoreTools::fixRoutingForCliAbsoluteUrls();
      
      $user = UllEntityTable::findById($mail->getRecipientUllUserId());
      
      // Generic personalization, not newsletter specific
      $mail->setBody(self::personalizeBody($mail->getBody(), $user));
      
      if ($editionId = $mail->getNewsletterEditionId())
      {
        $edition = Doctrine::getTable('UllNewsletterEdition')->find($editionId);
        
        $newBody = $edition->getPersonalizedBody($mail->getBody(), $user);
        $mail->setBody($newBody);
      }
    }
  }
  
  /**
   *
   * @param Swift_Events_SendEvent $evt
   */
  public function sendPerformed(Swift_Events_SendEvent $evt)
  {
     
  } 
  
  
  /**
   * Personalizes the email body for the current UllUser
   * replacing tags like [FIRST_NAME] with the Users first_name
   * 
   * @param string $body
   * @param ullUser $user
   */
  public static function personalizeBody($body, ullUser $user)
  {
    $dictionary = array();
    
    //look for UllUser column names (used as tags) and replace them
    //with the their matching value
    foreach ($user as $field => $value)
    {
      $dictionary['[' .strtoupper($field) . ']'] = $value;
    }
    
    return strtr($body, $dictionary);
  }    
  
  /**
   * Remove online link tag
   *
   * @param string $body
   */
  public static function removeOnlineLinkTag($body)
  {
    // unpersonalized
    $body = str_replace('[ONLINE_LINK]', '', $body);
    
    // personalized
    $body = preg_replace(
    '/<span.*?id\s*=\s*"ull_newsletter_show_online_link".*?>.*?<\/span>/',
      '', $body);
    
    return $body;
  }
  
  /**
   * Remove unsubscribe tag
   *
   * @param string $body
   */  
  public static function removeUnsubscribeTag($body)
  {
    $body = str_replace('[UNSUBSCRIBE]', '', $body);
    
    $body = preg_replace(
    '/<span.*?id\s*=\s*"ull_newsletter_unsubscribe".*?>.*?<\/span>/',
      '', $body);
    
    return $body;
  }

  /**
   * Remove tracking beacon tag
   *
   * @param string $body
   */  
  public static function removeTrackingBeaconTag($body)
  {
    $body = str_replace('[TRACKING]', '', $body);
    
    $body = preg_replace(
    '/<img.*?id\s*=\s*"ull_newsletter_beacon".*?\/>/',
      '', $body);    
    
    return $body;
  }    
  
  /**
   * Remove all personalization fields except UllUser fields.
   *
   * @param unknown_type $body
   */
  public static function removePersonalisationTags($body)
  {
    $body = self::removeTrackingBeaconTag($body);
    $body = self::removeOnlineLinkTag($body);
    $body = self::removeUnsubscribeTag($body);
    
    return $body;
  }  
}
