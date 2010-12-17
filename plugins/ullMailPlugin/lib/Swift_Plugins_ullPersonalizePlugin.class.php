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
  public function __construct()
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');
  }
  
  /**
   * @param Swift_Events_SendEvent $evt
   */
  public function beforeSendPerformed(Swift_Events_SendEvent $evt)
  {
    $mail = $evt->getMessage();
    
    if ($mail instanceof ullsfMail && $mail->getRecipientUllUserId())
    {
      $user = UllEntityTable::findById($mail->getRecipientUllUserId());
      
      $dictionary = array();
      
      if ($editionId = $mail->getNewsletterEditionId())
      {
        $edition = Doctrine::getTable('UllNewsletterEdition')->find($editionId);
        
        $lists = $edition->getMailingListsForUser($user['id']);

        $unsubscribe = array();
        
        foreach ($lists as $list)
        {
          $unsubscribe[] = link_to(
            __('Unsubscribe from list "%list%"', array('%list%' => $list['name']), 'ullMailMessages'),
            'ullNewsletter/unsubscribe?list=' . $list['slug'] . '&email=' . base64_encode($user['email']),
            array('absolute' => true)
          );
        }
        
        $dictionary['[UNSUBSCRIBE]'] = implode('<br />', $unsubscribe);
        
      }
      
      foreach ($user as $field => $value)
      {
        $dictionary['[' .strtoupper($field) . ']'] = $value;
      }
      
      $mail->setBody(strtr($mail->getBody(), $dictionary));
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


