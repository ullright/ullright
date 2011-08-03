<?php

/**
 * This class is an extension of Swift_Message.
 * It used to extend sfMail and provided support for
 * debug rerouting, ... - stuff which is now handled
 * by the Swift Mailer system and matching plugins
 * (see ullMailPluginConfiguration).
 * 
 * Unique functionality added by this class is the ability
 * to pass an UllEntity instead of an address - if the
 * entity has more than one address, multiple mails are sent.
 */
class ullsfMail extends Swift_Message
{
  protected 
    $slug,
    //TODO: enhance for multiple recipients and batch sending
    $recipientUllUserId,
    $newsletterEditionId,
    $isHtml = false,
    $isQueued = false
  ;
  
  /**
   * Returns a new instance of the ullsfMail class.
   * 
   * @param string $slug a unique identifer for the "type" of email
   *                     Example: registration_confirmation
   *                     This e.g. used to blacklist bcc debug mails for
   *                     certain types of emails
   */
  public function __construct($slug = null) 
  {
    //construct with empty subject, body, content-type and
    //set the charset to utf-8
    parent::__construct(null, null, null, 'utf-8');
    
    $this->slug = $slug;
  }
  
  /**
   * Perform a deep clone
   * 
   * Used for preparing a mail for multiple recipients.
   * 
   * Without it, the previous recipients are remembered 
   */
//  function __clone() 
//  {
//    foreach ($this as $key => $val) 
//    {
//      if (is_object($val) || (is_array($val))) 
//      {
//        $this->{$key} = unserialize(serialize($val));
//      }
//    }
//  }  
  
  /**
   * Adds an address and a matching name, either to the
   * recipient list, the carbon copy list or the blind
   * carbon copy list. UllEntities are resolved to their
   * addresses.
   * 
   * @param mixed $address a mail address or an UllEntity
   * @param string $name a matching name - will be generated if possible (if it is null)
   * @param string $type 'To', 'Cc' or 'Bcc' - 'To' is default
   * @throws UnexpectedValueException in case the type argument is invalid
   */
  protected function addAddressForType($address, $name = null, $type = 'To')
  {
    if (!in_array($type, array('To', 'Cc', 'Bcc')))
    {
      throw new UnexpectedValueException("type must be 'To', 'Cc' or 'Bcc'.");
    }

    //resolve UllEntity addresses and add all of them
    if ($address instanceof UllEntity)
    {
      $entityAddresses = $this->getUllEntityEmail($address);
      
      foreach ($entityAddresses as $entityAddress)
      {
        $this->addAddressForType($entityAddress['email'], $entityAddress['name'], $type);
      }
    }
    else 
    {
      //try to generate a name if none was given
      if ($name == null) 
      {
        list($address, $name) = ullCoreTools::splitMailAddressWithName($address);
      }
      
      //call the appropriate method in Swift_Message ...
      $methodName = 'add' . $type;
      //.. but also fix empty name string for compat with Swift Mailer
      parent::$methodName($address, (strlen($name) === 0) ? null : $name);
    }
  }
  
  /**
   * @param mixed $address UllEntity or string
   * @param string $name
   */
  public function addAddress($address, $name = null)
  {
    $this->addAddressForType($address, $name, 'To');
  }

  /**
   * @param mixed $address UllEntity or string
   * @param string $name
   */
  public function addCc($address, $name = null)
  {
    $this->addAddressForType($address, $name, 'Cc');
  }

  /**
   * @param mixed $address UllEntity or string
   * @param string $name
   */  
  public function addBcc($address, $name = null)
  {
    $this->addAddressForType($address, $name, 'Bcc');
  }
  
  /**
   * Returns true if there is at least one 'To' address, false otherwise.
   *
   * @return boolean true if at least one 'To' address is available
   */
  public function hasAddresses() 
  {
    return (count($this->getTo()) > 0);
  }
  
  /**
   * Legacy recipient address getter.
   */
  public function getAddresses()
  {
    return $this->getTo();
  }
  
  /**
   * Returns the slug of this message.
   */
  public function getSlug()
  {
    return $this->slug;
  }

  /**
   * Resolves an UllEntity to its email addresses and returns
   * them in an array. Also supports UllGroups without group email
   * address -> returns all addresses of its members.
   *
   * @param UllEntity $entity
   * @return array array(array('email' => 'me@example.com', 'name' => 'me'), ...)
   */
  protected function getUllEntityEmail(UllEntity $entity)
  {
    $return = array();
    
    // if no group email -> get list of users and send to their email addresses
    if ($entity instanceof UllGroup && !$entity->email) 
    {
      foreach ($entity->UllUser as $user) 
      {
        if (($email = $user->email) && $user->isActive()) 
        {
          $return[] = array('email' => $email, 'name' => (string) $user);
        }
      }
    } 
    else 
    {
      if ($email = $entity->email) 
      {
        $return[] = array('email' => $email, 'name' => (string) $entity);
      }       
      
      if ($entity instanceof UllUser)
      {
        $this->recipientUllUserId = $entity->id;
      }
    }

    return $return;
  }
  
  
  /**
   * Add multiple format bodies
   * @param string $htmlBody
   * @param string $plaintextBody
   */
  public function setBodies($htmlBody, $plaintextBody)
  {
    $this->setHtmlBody($htmlBody);
    $this->setPlaintextBody($plaintextBody);
  }
  
  
  /**
   * Sets the html body
   * 
   * @param string $body
   * 
   * @return self
   */
  public function setHtmlBody($body)
  {
    $this->setBody($body, 'text/html');
    $this->setIsHtml(true);
    
    return $this;
  }
  
  /**
   * Gets the html body
   * 
   * It is the main body per our convention
   * 
   * @return string
   */
  public function getHtmlBody()
  {
//    if ($this->getContentType() == 'text/html')
//    {
      return $this->getBody();
//    }
//    else
//    {
//      throw new UnexpectedValueException('The main body is not of type html!');
//    }
  }
  
  /**
   * Set plaintext body
   * 
   * @param string $body
   * 
   * @return self
   */
  public function setPlaintextBody($body)
  {
    $children = $this->getChildren();
    
    $isFound = false;
    
    foreach($children as $child)
    {
      if ($child->getContentType() == 'text/plain')
      {
        $child->setBody($body);
        $isFound = true;
        
        break;
      }
    }    
    
    if (!$isFound)
    {
      $this->addPart($body, 'text/plain');
    }
    
    return $this;
  }

  
  /**
   * Gets the plaintext body
   * 
   * @return string
   */
  public function getPlaintextBody()
  {
    $children = $this->getChildren();
    
    foreach($children as $child)
    {
      if ($child->getContentType() == 'text/plain')
      {
        return $child->getBody();
      }
    }
  }

  
  /**
   * Hook for custom logic which is executed before sending the mail
   * 
   * @deprecated prepareForSending should be used and extended from now on
   */
  public function prepare() {}
  
  
  /**
   * Prepare for sending template method for child classes
   * 
   * @return none
   */
  public function prepareForSending() 
  {
    return true;
  }
  
  
  /**
   * Commences mail sending process, using the realtime transport
   * by default. Optionally uses the queue.
   * 
   * @param boolean $queue true/false => common/realtime transport
   * @deprecated use sfContext::getInstance()->getMailer()->send() instead.
   */
  public function send($queue = false) 
  {
    $this->prepare();
    
    $shouldSend = $this->prepareForSending();
    
    if ($shouldSend && $this->hasAddresses()) 
    {
      if ($queue)
      {
        sfContext::getInstance()->getMailer()->sendQueue($this);
      }
      else
      {
        sfContext::getInstance()->getMailer()->send($this);
      }
    }
  }
  
  /**
   * Sets the RecipientUllUserId when sending to a single UllUser
   * 
   * @param integer $id
   * 
   * @return: self
   */
  public function setRecipientUllUserId($id)
  {
    $this->recipientUllUserId = $id;
    
    return $this;
  }

  /** 
   * Gets the RecipientUllUserId when sent to a single UllUser
   * 
   * @return: integer
   */
  public function getRecipientUllUserId()
  {
    return $this->recipientUllUserId;
  }
  
  /**
   * Mark as html email
   * 
   * @param boolean $boolean
   * 
   * @return: self
   */
  public function setIsHtml($boolean)
  {
    $this->isHtml = (boolean) $boolean;
    
    return $this;
  }

  
  /** 
   * Is the mail marked as html?
   * 
   * @return: boolean
   */
  public function getIsHtml()
  {
    return (boolean) $this->isHtml;
  }  
  
  
  /**
   * Set newsletter edition id
   * 
   * @param integer $id
   * @return self
   */
  public function setNewsletterEditionId($id)
  {
    $this->newsletterEditionId = $id;
    
    return $this;
  }
  
  
  /**
   * Get newsletter edition id
   */
  public function getNewsletterEditionId()
  {
    return $this->newsletterEditionId;
  }
  
  /**
   * 
   * @param boolean $boolean
   * @return self
   */
  public function setIsQueued($boolean)
  {
    $this->isQueued = (boolean) $boolean;
    
    return $this;
  }

  /**
   * 
   * @return boolean
   */
  public function getIsQueued()
  {
    return (boolean) $this->isQueued;
  }
  
  /**
   * Clear all recipients
   */
  public function clearRecipients()
  {
    $this->setTo(array());
    $this->setCC(array());
    $this->setBcc(array());
    $this->setRecipientUllUserId(null);
  }
  
  
  /**
   * Render a symfony partial as mail content
   * 
   * The first line is assumed to be the subject
   * 
   * @param string $partial     A valid symfony partial name e.g. ullMail/testMail 
   *                            @see get_partial()
   * @param array $vars         optional, array of partial variables
   * 
   * @return self
   */
  public function usePartial($partial, $vars = array())
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');
    
    $partial = get_partial($partial, $vars);
    
    $lines = explode("\n", $partial);
    $subject = array_shift($lines);
    
    $this->setSubject($subject);
    
    $body = implode("\n", $lines); 
    
    $this->setBody($body);

    return $this;
  }
  
}
