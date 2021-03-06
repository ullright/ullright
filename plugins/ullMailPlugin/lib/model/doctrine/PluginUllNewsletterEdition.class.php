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
   * Post update hook
   * 
   * Update proxy fields
   * 
   * @see lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/vendor/doctrine/Doctrine/Doctrine_Record::postUpdate()
   */
  public function postUpdate($event)
  {
    $this->updateNumSentRecipients();
    $this->updateNumReadEmails();
    $this->updateNumFailedEmails();
    $this->save();
  }
  
  /**
   * Create a new Newsletter with some data copied from the current
   */
  public function copyAsNew()
  {
    $new = new UllNewsletterEdition();
    $new->subject = $this->subject; 
    $new->body = $this->body;
    
    return $new;
  }
  
  /**
   * Decorate the body with the selected template
   * 
   * @return string
   */
  public function getDecoratedBody()
  {
    $body = $this->cleanBody($this['body']);
    
    $layoutHead = $this['UllNewsletterLayout']['html_head'];
    $layoutBody = $this['UllNewsletterLayout']['html_body'];
    
    if ($layoutBody)
    {
      $body = str_replace('[CONTENT]', $body, $layoutBody);
    }
    
    $body = "<html>\n" .
      "<head>\n" . $layoutHead . "\n</head>\n" . 
      "\n<body class=\"newsletter_" . $this['UllNewsletterLayout']['slug'] . "\">\n" . $body . "\n</body>" .
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
   * Perform cleaning
   * 
   * @param string $body
   */
  public function cleanBody($body)
  {
    //Remove ullWidgetContentElement input hidden tags
    $body = ullHTMLPurifier::removeInputTags($body);
    
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
      ->from('UllUser u, u.UllUserStatus s, u.UllNewsletterMailingLists ml, ml.UllNewsletterEdition e')
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
   * Update the num_failed_emails proxy field by recounting the failed logged messages
   */
  public function updateNumFailedEmails()
  {
    $this->num_failed_emails = $this->countLoggedMessagesFailed();
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
    $result = $q->count();
    
    return ($result) ? $result : null;
  }  
  
  
  /**
   * Update the num_sent_recipients proxy field by recounting the sent logged messages
   */
  public function updateNumSentRecipients()
  {
    $this->num_sent_recipients = $this->countLoggedMessagesSent();
  }  

  
  /**
   * Count the sent logged messages for this edition
   */
  public function countLoggedMessagesSent()
  {
    $q = new Doctrine_Query;
    $q
      ->from('UllMailLoggedMessage m')
      ->where('m.ull_newsletter_edition_id = ?', $this->id)
      ->addWhere('m.transport_sent_status = ?', true)
      // Exclude test emails (Send preview to me)
      ->addWhere('m.subject NOT LIKE ?', '%#Test#')
    ;
    
    $result = $q->count();
    
    return ($result) ? $result : null; 
  }    
  
  
  
  /**
   * Update the num_read_emails proxy field by recounting the read logged messages
   */
  public function updateNumReadEmails()
  {
    $this->num_read_emails = $this->countLoggedMessagesRead();
  }  

  
  /**
   * Count the read logged messages for this edition
   */
  public function countLoggedMessagesRead()
  {
    $q = new Doctrine_Query;
    $q
      ->from('UllMailLoggedMessage m')
      ->where('m.ull_newsletter_edition_id = ?', $this->id)
      ->addWhere('m.subject NOT LIKE ?', '%#Test#')
      ->addWhere('m.first_read_at IS NOT NULL')
    ;
    $result = $q->count();
    
    return ($result) ? $result : null;
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
//      $lists = $this->findMailingListsForUser($user['id']);
      $lists = $user->UllNewsletterMailingLists;
      
      $editionLists = array();
      foreach ($this->UllNewsletterMailingLists as $list)
      {
        $editionLists[] = $list->id;
      }
      
      $unsubscribe = array();
      
      //note: s_uid as param name results in encrypted user ids (=secure params)
      foreach ($lists as $list)
      {
        // Skip lists that are not used for this newsletter
        if (!in_array($list->id, $editionLists))
        {
          continue;
        }
        
        $unsubscribeLink = link_to(
          __('Unsubscribe from list "%list%"', array('%list%' => $list['name']), 'ullMailMessages'),
          'ullNewsletter/unsubscribe?list=' . $list['slug'] . '&s_uid=' . $user['id'],
          array('absolute' => true)
        );
          
        $unsubscribeLink = '<span id="ull_newsletter_unsubscribe">'
          . $unsubscribeLink . '</span>';
          
        $unsubscribe[] = $unsubscribeLink;
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
        array('absolute' => true)) . 
        '" id="ull_newsletter_beacon" alt="" width="1" height="1" />';

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
   * Listen to mail log post_save event
   * 
   * @param sfEvent $event
   */
  public static function listenToUllMailLoggedMessagePostSaveEvent(sfEvent $event)
  {
    $loggedMessage = $event->getSubject();
    
    if ($loggedMessage->ull_newsletter_edition_id && $loggedMessage->UllNewsletterEdition->exists())
    {
      $loggedMessage->UllNewsletterEdition->updateNumFailedEmails();
      $loggedMessage->UllNewsletterEdition->updateNumSentRecipients();
      $loggedMessage->UllNewsletterEdition->save();
    }
  }
  
  
  /**
   * Listen to mail log read_detected event
   * 
   * @param sfEvent $event
   */
  public static function listenToUllMailLoggedMessageReadDetectedEvent(sfEvent $event)
  {
    $loggedMessage = $event->getSubject();
    
    if ($loggedMessage->ull_newsletter_edition_id && 
      $loggedMessage->UllNewsletterEdition->exists()) 
    {
      $loggedMessage->UllNewsletterEdition->updateNumReadEmails();
      $loggedMessage->UllNewsletterEdition->save();
    }
  }  
}
