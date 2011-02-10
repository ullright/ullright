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
}
