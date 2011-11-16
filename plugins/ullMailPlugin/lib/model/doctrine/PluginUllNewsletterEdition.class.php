<?php

/**
 * PluginUllNewsletterEdition
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class PluginUllNewsletterEdition extends BaseUllNewsletterEdition
{
  
  /**
   * Decorate the body with the selected template
   * 
   * @return string
   */
  public function getDecoratedBody()
  {
    $body = $this['body'];
    $layoutHead = $this['UllNewsletterLayout']['html_head'];
    $layoutBody = $this['UllNewsletterLayout']['html_body'];
    
    $body = "<html>\n" .
      "<head>\n" . $layoutHead . "\n</head>\n" . 
      "\n<body>\n" . strtr($layoutBody, array('[CONTENT]' => $body)) . "\n</body>" .
      "\n</html>";

    // Create absolute image urls
    $body = str_replace(
      ' src="/',
      ' src="http://' . sfConfig::get('app_server_name') . '/',
      $body 
    ); 
    
    return $body; 
  }
  
  
  /**
   * Get query for a list of recipients
   * 
   * @return Doctrine_Query
   */
  public function queryRecipients()
  {
    $q = new Doctrine_Query;
    $q
      ->select('DISTINCT u.email')
      ->from('UllUser u, u.UllUserStatus s, u.UllNewsletterMailingList ml, ml.UllNewsletterEdition e')
      ->addWhere('s.is_active = ?', true)
      ->addWhere('e.id = ?', $this->id)
      ->addWhere('u.email IS NOT NULL')
      ->addWhere('u.email <> ?', '')
      ->addOrderBy('u.email')
    ;
    
    return $q;
  }  
  
  
  /**
   * Return number of recipients
   * 
   * @return integer
   */
  public function countRecipients()
  {
    $q = $this->queryRecipients();
    
    return $q->count();
  }
  
  
  /**
   * Get a list of recipients
   * 
   * @return Doctrine_Collection
   */
  public function findRecipients($hydrationMode = null)
  {
    $q = $this->queryRecipients();
    
    return $q->execute(array(), $hydrationMode);
  }
  

  /**
   * Count the failed logged messages for this edition
   */
  public function countLoggedMessagesFailed()
  {
    $q = new Doctrine_Query;
    $q
      ->from('UllMailLoggedMessage m')
      ->where('m.ull_newsletter_edition_id = ?', $this->id)
      ->addWhere('m.failed_at IS NOT NULL')
    ;
    
    return $q->count();
  }  
  
  /**
   * Update the num_failed_emails proxy field by recounting the failed logged messages
   */
  public function updateNumFailedEmails()
  {
    $this->num_failed_emails = $this->countLoggedMessagesFailed();
  }

  
  /**
   * Get mailing lists for the current edition and the given user
   * 
   * @param integer $ullUserId
   * 
   * @return Doctrine_Collection
   */
  public function findMailingListsForUser($ullUserId)
  {
    $q = new Doctrine_Query;
    $q
      ->select('ml.id, ml.name')
      ->from('UllNewsletterMailingList ml, ml.Subscribers u, u.UllUserStatus s, ml.UllNewsletterEdition e')
      ->addWhere('s.is_active = ?', true)
      ->addWhere('e.id = ?', $this->id)
      ->addWhere('u.id = ?', $ullUserId)
      ->addOrderBy('ml.name')
    ;
    
    return $q->execute();
  }  
  
  /**
   * Create message from the current newletter edition record
   * 
   * @param UllUser $loggedInUser
   * 
   * @return ullsfMail
   */
  public function createMailMessage(UllUser $loggedInUser)
  {
    $mail = new ullsfMail('ull_newsletter');
    
//    $mail->setFrom($loggedInUser['email'], $loggedInUser['display_name']);
    $mail->setFrom(array(
        sfConfig::get('app_ull_newsletter_from_address') =>
          sfConfig::get('app_ull_newsletter_from_name')
    ));
    
    $mail->setSubject($this['subject']);
    $mail->setHtmlBody($this->getDecoratedBody());
    
    $mail->setNewsletterEditionId($this['id']);
    
    $mail->setReturnPath(sfConfig::get('app_ull_mail_bounce_mail_address'));
    
    return $mail;
  }

  /**
   * Personalizes this newsletter edition for a given user, generating
   * unsubscribe and online links, a tracking beacon ...
   * Receives a string (the 'body') and returns a version where tags
   * (e.g. [ONLINE_LINK]) are replaced.
   * Used by mailing (see Swift_Plugins_ullPersonalizePlugin).
   * 
   * @param string $body with tags to replace
   * @param ullUser $user or null (not supported atm)
   */
  public function getPersonalizedBody($body, $user = null)
  {
    $dictionary = array();
    
    //if a user was given, replace user-specific tags
    if ($user !== null)
    {
      //[UNSUBSCRIBE]
      $lists = $this->findMailingListsForUser($user['id']);
      $unsubscribe = array();
      
      //note: s_uid as param name results in encrypted user ids (=secure params)
      foreach ($lists as $list)
      {
        $unsubscribe[] = link_to(
          __('Unsubscribe from list "%list%"', array('%list%' => $list['name']), 'ullMailMessages'),
            'ullNewsletter/unsubscribe?list=' . $list['slug'] . '&s_uid=' . $user['id'],
            array('absolute' => true)
          );
      }
        
      $dictionary['[UNSUBSCRIBE]'] = implode('<br />', $unsubscribe);
      
      //[ONLINE_LINK]
      $onlineLink = __('Having trouble viewing this message?', null, 'ullMailMessages') .
          ' ' . link_to(__('Read the newsletter online.', null, 'ullMailMessages'),
        'newsletter_edition_show',
         array('mid' => '_-_LOGGED_MESSAGE_ID_-_'),
         array('absolute' => true)
       );

      $dictionary['[ONLINE_LINK]'] = '<span id="ull_newsletter_show_online_link">'
        . $onlineLink . '</span>';
        
      //[TRACKING]
      $trackingTag = '<img src="' . url_for('newsletter_web_beacon',
          array('mid' => '_-_LOGGED_MESSAGE_ID_-_'),
          array('absolute' => true)) . '" id="ull_newsletter_beacon" alt="" />';

      $dictionary['[TRACKING]'] = $trackingTag;
    }
    
    //do common replacement stuff which is not dependent on a specific user
    //(there are none atm)
    
    //TODO: we do not do this because we do not send unpersonalized mails atm
    //add unpersonalized online_link, if a personalized one was not added above
    //if (!isset($dictionary['[ONLINE_LINK]'])) ...

    //return original body with replaced tags
    return strtr($body, $dictionary);
  }
  
  /**
   * Recount num_failed_emails proxy field
   * 
   * @param sfEvent $event
   */
  public static function listenToUllMailLoggedMessagePostSaveEvent(sfEvent $event)
  {
    $loggedMessage = $event->getSubject();
    
    if ($loggedMessage->ull_newsletter_edition_id)
    {
      $loggedMessage->UllNewsletterEdition->updateNumFailedEmails();
      $loggedMessage->UllNewsletterEdition->save();
    }
  }
}
