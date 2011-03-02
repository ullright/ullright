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
    $mail = new ullsfMail();
    
//    $mail->setFrom($loggedInUser['email'], $loggedInUser['display_name']);
    $mail->setFrom(array(
        sfConfig::get('app_ull_newsletter_from_address') =>
          sfConfig::get('app_ull_newsletter_from_name')
    ));
    
    $mail->setSubject($this['subject']);
    $mail->setHtmlBody($this->getDecoratedBody());
    
    $mail->setNewsletterEditionId($this['id']);
    
    $mail->setReturnPath(sfConfig::get('app_ull_newsletter_bounce_mail_address'));
    
    return $mail;
  }

  /**
   * Personalizes this newsletter edition for a given user, generating
   * unsubscribe and online links, ...
   * Receives a string (the 'body') and returns a version where tags
   * (e.g. [ONLINE_LINK]) are replaced.
   * Used by mailing (see Swift_Plugins_ullPersonalizePlugin) and
   * ullNewsletter's show action.
   * 
   * @param string $body with tags to replace
   * @param ullUser $user or null (not supported atm)
   * @param boolean $onlineView if true, ONLINE_LINK gets removed instead of replaced
   */
  public function getPersonalizedBody($body, $user = null, $onlineView = false)
  {
    $dictionary = array();
    
    //if a user was given, replace user-specific tags
    if ($user !== null)
    {
      //look for UllUser column names (used as tags) and replace them
      //with the their matching value
      foreach ($user as $field => $value)
      {
        $dictionary['[' .strtoupper($field) . ']'] = $value;
      }
      
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
      $onlineLink = ($onlineView) ? '' : 
        $onlineLink = __('Having trouble viewing this message?', null, 'ullMailMessages') .
          ' ' . link_to(__('Read the newsletter online.', null, 'ullMailMessages'),
              'newsletter_edition_show',
              array('id' => $this['id'], 's_uid' => $user['id']),
              array('absolute' => true)
            );

      $dictionary['[ONLINE_LINK]'] = $onlineLink;
    }
    
    //do common replacement stuff which is not dependent on a specific user
    //(there are none atm)
    
    //TODO: we do not do this because we do not send unpersonalized mails atm
    //add unpersonalized online_link, if a personalized one was not added above
    //if (!isset($dictionary['[ONLINE_LINK]'])) ...

    //return original body with replaced tags
    return strtr($body, $dictionary);
  }
}
